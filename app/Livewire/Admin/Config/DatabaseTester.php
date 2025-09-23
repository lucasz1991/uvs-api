<?php

namespace App\Livewire\Admin\Config;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use Throwable;
use Illuminate\Support\Str;

class DatabaseTester extends Component
{
    public bool $connected = false;
    public string $errorMessage = '';
    public array $tables = [];

    protected function connectFromSettings(): void
    {
        config(['database.connections.uvs' => [
            'driver'    => 'mysql',
            'host'      => Setting::getValue('database', 'hostname'),
            'database'  => Setting::getValue('database', 'database'),
            'username'  => Setting::getValue('database', 'username'),
            'password'  => Setting::getValue('database', 'password'),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ]]);
    }

    public function testConnection(): void
    {
        $this->reset(['connected', 'errorMessage', 'tables']);

        try {
            $this->connectFromSettings();

            // Verbindung testen
            DB::connection('uvs')->getPdo();
            $this->connected = true;

            $dbName = config('database.connections.uvs.database');

            // Alle Tabellen holen
            $tables = DB::connection('uvs')->select(
                'SELECT TABLE_NAME 
                   FROM information_schema.TABLES 
                  WHERE TABLE_SCHEMA = ? 
               ORDER BY TABLE_NAME',
                [$dbName]
            );

            $result = [];
            foreach ($tables as $t) {
                $tableName = $t->TABLE_NAME;

                // Spalten der Tabelle holen
                $cols = DB::connection('uvs')->select(
                    'SELECT COLUMN_NAME,
                            DATA_TYPE,
                            CHARACTER_MAXIMUM_LENGTH,
                            NUMERIC_PRECISION,
                            NUMERIC_SCALE,
                            IS_NULLABLE,
                            COLUMN_DEFAULT,
                            COLUMN_KEY,
                            EXTRA
                       FROM information_schema.COLUMNS
                      WHERE TABLE_SCHEMA = ?
                        AND TABLE_NAME = ?
                   ORDER BY ORDINAL_POSITION',
                    [$dbName, $tableName]
                );

                $columns = [];
                foreach ($cols as $c) {
                    // Länge/Präzision aufbereiten
                    $length = null;
                    if (!is_null($c->CHARACTER_MAXIMUM_LENGTH)) {
                        $length = (string) $c->CHARACTER_MAXIMUM_LENGTH;
                    } elseif (!is_null($c->NUMERIC_PRECISION)) {
                        $scale = is_null($c->NUMERIC_SCALE) ? 0 : (int) $c->NUMERIC_SCALE;
                        $length = $scale > 0
                            ? "{$c->NUMERIC_PRECISION},{$scale}"
                            : (string) $c->NUMERIC_PRECISION;
                    }

                    $columns[] = [
                        'name'      => $c->COLUMN_NAME,
                        'type'      => $c->DATA_TYPE,
                        'length'    => $length,
                        'nullable'  => $c->IS_NULLABLE === 'YES',
                        'default'   => $c->COLUMN_DEFAULT,
                        'key'       => $c->COLUMN_KEY,   // PRI/UNI/MUL/…
                        'extra'     => $c->EXTRA,        // auto_increment etc.
                    ];
                }

                $result[] = [
                    'name'    => $tableName,
                    'columns' => $columns,
                ];
            }

            $this->tables = $result;
        } catch (Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function exportText()
    {
        try {
            // Falls noch nichts geladen ist: Verbindung + Schema laden
            if (!$this->connected || empty($this->tables)) {
                $this->testConnection();
                if (!$this->connected) {
                    throw new \RuntimeException('Keine Verbindung zur UVS-Datenbank möglich.');
                }
            }

            $dbName = config('database.connections.uvs.database');
            $now    = now()->format('Y-m-d H:i:s');

            $lines = [];
            $lines[] = 'UVS Datenbank-Schema Export';
            $lines[] = "Datenbank: {$dbName}";
            $lines[] = "Erstellt: {$now}";
            $lines[] = str_repeat('=', 70);

            foreach ($this->tables as $table) {
                $lines[] = "Tabelle: {$table['name']} (" . count($table['columns']) . ' Spalten)';
                foreach ($table['columns'] as $col) {
                    $len     = $col['length'] ?? '';
                    $type    = $col['type'] . ($len !== '' ? "({$len})" : '');
                    $nullable= $col['nullable'] ? 'NULL' : 'NOT NULL';
                    $default = is_null($col['default']) ? '' : ' DEFAULT ' . $col['default'];
                    $key     = $col['key']   ? ' KEY:' . $col['key'] : '';
                    $extra   = $col['extra'] ? ' ' . $col['extra'] : '';

                    $lines[] = "  - {$col['name']} : {$type} {$nullable}{$default}{$key}{$extra}";
                }
                $lines[] = ''; // Leerzeile zwischen Tabellen
            }

            // Optional: BOM für Notepad-Kompatibilität auf Windows
            $content  = "\xEF\xBB\xBF" . implode(PHP_EOL, $lines);

            $filename = 'uvs_schema_' . now()->format('Ymd_His') . '.txt';

            return response()->streamDownload(
                fn () => print($content),
                $filename,
                ['Content-Type' => 'text/plain; charset=UTF-8']
            );
        } catch (Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }
    }


    public function render()
    {
        return view('livewire.admin.config.database-tester');
    }
}
