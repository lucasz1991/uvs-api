<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\DocumentApiController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionMethod;
use Spatie\Activitylog\ActivityLogStatus;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class DocumentApiControllerTest extends TestCase
{
    private string $uvsRoot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uvsRoot = sys_get_temp_dir().DIRECTORY_SEPARATOR.'uvs-document-api-'.bin2hex(random_bytes(8));

        mkdir($this->uvsRoot.DIRECTORY_SEPARATOR.'data/pdf/angebote', 0777, true);
        mkdir($this->uvsRoot.DIRECTORY_SEPARATOR.'data/pdf/vertraege', 0777, true);

        config([
            'app.url' => 'http://localhost',
            'uvs.root' => $this->uvsRoot,
            'uvs.document_dirs' => [
                'angebot' => 'data/pdf/angebote',
                'vertrag' => 'data/pdf/vertraege',
            ],
            'uvs.document_url_ttl' => 30,
        ]);

        URL::forceRootUrl('http://localhost');
        URL::forceScheme('http');
        app(ActivityLogStatus::class)->disable();
    }

    protected function tearDown(): void
    {
        URL::forceRootUrl(null);
        URL::forceScheme(null);
        $this->removeDirectory($this->uvsRoot);

        parent::tearDown();
    }

    public function test_it_signs_only_an_existing_readable_pdf_and_streams_all_bytes(): void
    {
        $this->enableInMemoryActivityLog();

        $filename = 'Vertrag-Test.pdf';
        $contents = "%PDF-1.4\n1 0 obj\n<<>>\nendobj\n%%EOF\n";
        file_put_contents(
            $this->uvsRoot.DIRECTORY_SEPARATOR.'data/pdf/vertraege/'.$filename,
            $contents
        );

        $signResponse = $this->postJson('/api/documents/sign', [
            'typ' => 'vertrag',
            'path' => '/uvs_dev/data/pdf/vertraege/'.$filename,
            'item_id' => '28293',
            'context' => [
                'flow' => 'teilnehmervertrag_document_sign',
                'beratung_id' => '1-00464080007',
                'tvertrag_uid' => '28293',
            ],
        ]);

        $signResponse
            ->assertOk()
            ->assertJsonPath('filename', $filename)
            ->assertJsonPath('file_size', strlen($contents));

        $signedUrl = (string) $signResponse->json('url');
        $this->assertStringContainsString('signature=', $signedUrl);
        $this->assertStringContainsString('expires=', $signedUrl);

        $downloadResponse = $this->get($signedUrl);
        $downloadResponse
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertHeader('Content-Length', (string) strlen($contents));

        $this->assertSame($contents, $downloadResponse->streamedContent());

        $activities = Activity::query()->orderBy('id')->get();
        $expectedEvents = [
            'document.signed',
            'document.delivery_started',
            'document.delivered',
        ];
        $this->assertSame($expectedEvents, $activities->pluck('event')->all());
        $this->assertSame(
            $expectedEvents,
            $activities->map(fn (Activity $activity) => $activity->getExtraProperty('event'))->all()
        );

        $signedActivity = $activities[0];
        $deliveredActivity = $activities[2];
        $this->assertSame($filename, $signedActivity->getExtraProperty('filename'));
        $this->assertTrue($signedActivity->getExtraProperty('file_exists'));
        $this->assertTrue($signedActivity->getExtraProperty('file_readable'));
        $this->assertTrue($signedActivity->getExtraProperty('pdf_header_valid'));
        $this->assertSame(
            'teilnehmervertrag_document_sign',
            $signedActivity->getExtraProperty('sync_context.flow')
        );
        $this->assertStringContainsString('redacted', $signedActivity->getExtraProperty('source_url'));
        $this->assertStringNotContainsString(
            (string) parse_url($signedUrl, PHP_URL_QUERY),
            $signedActivity->getExtraProperty('source_url')
        );
        $this->assertSame(strlen($contents), $deliveredActivity->getExtraProperty('bytes_sent'));
        $this->assertTrue($deliveredActivity->getExtraProperty('complete'));
        $this->assertTrue($deliveredActivity->getExtraProperty('download_success'));
        $this->assertSame(
            $signedActivity->getExtraProperty('source_url_hash'),
            $deliveredActivity->getExtraProperty('source_url_hash')
        );
    }

    public function test_it_rejects_a_file_with_pdf_extension_but_without_pdf_header(): void
    {
        $filename = 'Kein-echtes-PDF.pdf';
        file_put_contents(
            $this->uvsRoot.DIRECTORY_SEPARATOR.'data/pdf/vertraege/'.$filename,
            'not a pdf'
        );

        $this->postJson('/api/documents/sign', [
            'typ' => 'vertrag',
            'path' => '/uvs_dev/data/pdf/vertraege/'.$filename,
        ])
            ->assertNotFound()
            ->assertJsonPath('reason', 'document_header_not_pdf');
    }

    public function test_it_logs_a_failed_download_when_the_signed_file_disappears(): void
    {
        $this->enableInMemoryActivityLog();

        $filename = 'Vertrag-Verschwindet.pdf';
        $absolutePath = $this->uvsRoot.DIRECTORY_SEPARATOR.'data/pdf/vertraege/'.$filename;
        file_put_contents($absolutePath, "%PDF-1.4\n%%EOF\n");

        $signResponse = $this->postJson('/api/documents/sign', [
            'typ' => 'vertrag',
            'path' => '/uvs_dev/data/pdf/vertraege/'.$filename,
            'item_id' => '28294',
        ])->assertOk();

        unlink($absolutePath);

        $this->get((string) $signResponse->json('url'))->assertNotFound();

        $failedActivity = Activity::query()->orderByDesc('id')->firstOrFail();
        $this->assertSame('document.delivery_failed', $failedActivity->getExtraProperty('event'));
        $this->assertSame(
            'document_or_allowed_directory_missing',
            $failedActivity->getExtraProperty('reason')
        );
        $this->assertFalse($failedActivity->getExtraProperty('download_success'));
        $this->assertStringContainsString('redacted', $failedActivity->getExtraProperty('source_url'));
    }

    public function test_activity_log_url_masks_the_download_signature(): void
    {
        $controller = new DocumentApiController;
        $method = new ReflectionMethod($controller, 'urlForLog');
        $method->setAccessible(true);

        $loggedUrl = $method->invoke(
            $controller,
            'https://uvs.example.test:50123/api/documents/vertrag/pdf?expires=123&p=abc&signature=top-secret'
        );

        $this->assertStringContainsString('expires=123', $loggedUrl);
        $this->assertStringContainsString('p=abc', $loggedUrl);
        $this->assertStringContainsString('redacted', $loggedUrl);
        $this->assertStringNotContainsString('top-secret', $loggedUrl);
    }

    private function removeDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                rmdir($item->getPathname());
            } else {
                unlink($item->getPathname());
            }
        }

        rmdir($directory);
    }

    private function enableInMemoryActivityLog(): void
    {
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'activitylog.database_connection' => 'sqlite',
            'activitylog.table_name' => 'activity_log',
        ]);

        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');

        Schema::connection('sqlite')->create('activity_log', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('log_name')->nullable()->index();
            $table->text('description');
            $table->nullableMorphs('subject', 'subject');
            $table->string('event')->nullable();
            $table->nullableMorphs('causer', 'causer');
            $table->json('properties')->nullable();
            $table->uuid('batch_uuid')->nullable();
            $table->timestamps();
        });

        app(ActivityLogStatus::class)->enable();
    }
}
