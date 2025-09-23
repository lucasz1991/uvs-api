<?php

namespace App\Livewire\Admin\Logs;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use SplFileObject;

class LogViewer extends Component
{
    use WithPagination;

    public string $currentFile = '';
    public string $search = '';
    public int $lines = 200; // wie viele Kopfzeilen-Einträge max. holen
    public array $files = [];
    public array $levels = ['emergency','alert','critical','error','warning','notice','info','debug'];

    protected $queryString = ['currentFile', 'search'];

    public function mount(): void
    {
        $this->files = $this->discoverLogs();

        // Standard: neueste Datei
        if (!$this->currentFile && !empty($this->files)) {
            $this->currentFile = $this->files[0]['name'];
        }
    }

    public function updatedCurrentFile(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $fileMeta = collect($this->files)->firstWhere('name', $this->currentFile);
        $entries = [];
        $fullpath = $fileMeta['path'] ?? null;

        if ($fullpath && file_exists($fullpath)) {
            $entries = $this->tailEntries($fullpath, $this->lines);

            if ($this->search) {
                $q = Str::lower($this->search);
                $entries = array_values(array_filter($entries, function ($e) use ($q) {
                    $hay = Str::lower(($e['raw'] ?? '') . ' ' . ($e['message'] ?? '') . ' ' . ($e['context'] ?? ''));
                    return Str::contains($hay, $q);
                }));
            }
        }

        // einfache Paginierung
        $perPage = 50;
        $total = count($entries);
        $page = max(1, (int) request()->query('page', 1));
        $chunks = array_chunk($entries, $perPage);
        $page = min($page, max(1, count($chunks)));
        $pageItems = $chunks[$page-1] ?? [];

        return view('livewire.admin.logs.log-viewer', [
            'files'    => $this->files,
            'fileMeta' => $fileMeta,
            'items'    => $pageItems,
            'total'    => $total,
            'perPage'  => $perPage,
            'page'     => $page,
            'pages'    => max(1, count($chunks)),
        ])->layout('layouts.master');
    }

    /** Liste der Logfiles nach Datum absteigend */
    protected function discoverLogs(): array
    {
        $dir = storage_path('logs');
        if (!is_dir($dir)) return [];

        $files = [];
        foreach (File::files($dir) as $f) {
            if ($f->getExtension() !== 'log') continue;
            $files[] = [
                'name'  => $f->getFilename(),
                'path'  => $f->getPathname(),
                'size'  => $f->getSize(),
                'mtime' => $f->getMTime(),
            ];
        }

        usort($files, fn($a,$b) => $b['mtime'] <=> $a['mtime']);
        return $files;
    }

    /** Gruppiert Kopfzeile + Stacktrace-Zeilen */
    protected function tailEntries(string $path, int $maxHeaderLines = 200): array
    {
        $f = new SplFileObject($path, 'r');
        $f->seek(PHP_INT_MAX);
        $last = $f->key();

        $entries = [];
        $current = null;
        $headerCount = 0;

        $isHeader = function (string $line): bool {
            return (bool) preg_match('/^\[[0-9\-:\s]+\]\s+\w+\.\w+: /', $line);
        };

        for ($i = $last; $i >= 0; $i--) {
            $f->seek($i);
            $raw = rtrim((string) $f->current(), "\r\n");

            if ($raw === '') {
                if ($current) {
                    $current['context_lines'][] = '';
                }
                continue;
            }

            if ($isHeader($raw)) {
                $headerCount++;
                if ($current) {
                    if (!empty($current['context_lines'])) {
                        $current['context_lines'] = array_reverse($current['context_lines']);
                        $current['context'] = implode("\n", $current['context_lines']);
                    }
                    unset($current['context_lines']);
                    $entries[] = $current;
                }

                $parsed = $this->parseLaravelHeader($raw);
                $current = [
                    'raw'          => $raw,
                    'time'         => $parsed['time'] ?? null,
                    'env'          => $parsed['env'] ?? null,
                    'level'        => $parsed['level'] ?? null,
                    'message'      => $parsed['message'] ?? $raw,
                    'context'      => null,
                    'context_lines'=> [],
                ];

                if ($headerCount >= $maxHeaderLines) {
                    break;
                }
            } else {
                if ($current) {
                    $current['context_lines'][] = $raw;
                } else {
                    $current = [
                        'raw'          => '',
                        'time'         => null,
                        'env'          => null,
                        'level'        => null,
                        'message'      => '(fortlaufender Log ohne Header)',
                        'context'      => null,
                        'context_lines'=> [$raw],
                    ];
                }
            }
        }

        if ($current) {
            if (!empty($current['context_lines'])) {
                $current['context_lines'] = array_reverse($current['context_lines']);
                $current['context'] = implode("\n", $current['context_lines']);
            }
            unset($current['context_lines']);
            $entries[] = $current;
        }

        return $entries; // neueste zuerst
    }

    /** Nur Kopfzeile parsen */
    protected function parseLaravelHeader(string $line): array
    {
        if (preg_match('/^\[(?<time>[^\]]+)\]\s+(?<env>\w+)\.(?<level>\w+):\s+(?<message>.*)$/', $line, $m)) {
            return [
                'time'    => $m['time'],
                'env'     => $m['env'],
                'level'   => Str::lower($m['level']),
                'message' => trim($m['message']),
            ];
        }
        return ['message' => $line];
    }

    /** Datei löschen */
    public function deleteFile(string $name): void
    {
        $file = collect($this->files)->firstWhere('name', $name);
        if (!$file) return;

        abort_unless(auth()->user()?->can('viewLogs'), 403);
        @unlink($file['path']);

        $this->files = $this->discoverLogs();
        if ($this->currentFile === $name) {
            $this->currentFile = $this->files[0]['name'] ?? '';
        }
        $this->dispatch('toast', type: 'success', message: 'Logdatei gelöscht.');
    }

    /** Download-Link */
    public function download(string $name)
    {
        $file = collect($this->files)->firstWhere('name', $name);
        abort_unless($file && file_exists($file['path']), 404);

        return response()->download($file['path'], $name, [
            'Content-Type' => 'text/plain; charset=utf-8'
        ]);
    }
}
