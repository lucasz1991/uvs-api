<?php

namespace App\Livewire\Admin\Config;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use Throwable;

class DatabaseTester extends Component
{
    public bool $connected = false;
    public string $errorMessage = '';
    public array $tables = [];

    /**
     * Wenn true, wird die Zeilenzahl per COUNT(*) exakt ermittelt (langsamer).
     * Wenn false, wird TABLE_ROWS aus information_schema verwendet (schneller, aber geschätzt).
     */
    public bool $exactCounts = false;

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

    /** Sichere Quoting-Hilfe für Tabellennamen / Spalten */
    protected function tick(string $identifier): string
    {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }

    /** Schnelle (geschätzte) Row-Counts als Map laden */
    protected function loadEstimatedRowCounts(string $dbName): array
    {
        $rows = DB::connection('uvs')->select(
            'SELECT TABLE_NAME, TABLE_ROWS
               FROM information_schema.TABLES
              WHERE TABLE_SCHEMA = ?',
            [$dbName]
        );

        $map = [];
        foreach ($rows as $r) {
            $map[$r->TABLE_NAME] = is_null($r->TABLE_ROWS) ? null : (int) $r->TABLE_ROWS;
        }
        return $map;
    }

    /** Exakte Row-Counts (teurer) */
    protected function loadExactRowCount(string $table): int
    {
        $tn  = $this->tick($table);
        $row = DB::connection('uvs')->selectOne("SELECT COUNT(*) AS c FROM {$tn}");
        return (int)($row->c ?? 0);
    }

    /** Wähle eine sinnvolle ORDER-BY-Spalte */
    protected function chooseOrderColumn(array $columns): ?string
    {
        $names = array_column($columns, 'name');

        if (in_array('uid', $names, true)) return 'uid';
        if (in_array('id', $names, true))  return 'id';

        foreach ($names as $n) {
            if (str_ends_with($n, 'id')) return $n; // erstes *_id
        }

        // falls PK-Spalte existiert, nimm diese
        foreach ($columns as $col) {
            if (($col['key'] ?? null) === 'PRI') return $col['name'];
        }

        return null; // kein gutes Feld gefunden
    }

    /** Lade eine Beispielzeile (ORDER BY {col} DESC LIMIT 1, sonst LIMIT 1) */
    protected function loadSampleRow(string $table, ?string $orderCol): ?array
    {
        $tn  = $this->tick($table);

        try {
            if ($orderCol) {
                $oc  = $this->tick($orderCol);
                $row = DB::connection('uvs')->selectOne("SELECT * FROM {$tn} ORDER BY {$oc} DESC LIMIT 1");
            } else {
                $row = DB::connection('uvs')->selectOne("SELECT * FROM {$tn} LIMIT 1");
            }
            return $row ? (array) $row : null;
        } catch (Throwable $e) {
            // z. B. VIEW ohne Rechte → nicht abbrechen
            return null;
        }
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

            // Geschätzte Counts im Bulk (schnell)
            $estimatedMap = $this->loadEstimatedRowCounts($dbName);

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

                // Row-Count wählen
                $estimated = $estimatedMap[$tableName] ?? null;
                $rowCount  = $this->exactCounts ? $this->loadExactRowCount($tableName) : $estimated;

                // Beispielzeile laden
                $orderCol = $this->chooseOrderColumn($columns);
                $sample   = $this->loadSampleRow($tableName, $orderCol);

                $result[] = [
                    'name'           => $tableName,
                    'row_count'      => $rowCount,                          // int|null
                    'row_count_type' => $this->exactCounts ? 'exact' : 'estimated',
                    'order_by'       => $orderCol,                          // neu
                    'sample'         => $sample,                            // neu
                    'columns'        => $columns,
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
                $cntInfo = isset($table['row_count'])
                    ? " | Rows ({$table['row_count_type']}): " . ($table['row_count'] ?? 'n/a')
                    : '';
                $orderInfo = $table['order_by'] ? " | OrderBy: {$table['order_by']} DESC" : '';

                $lines[] = "Tabelle: {$table['name']} (" . count($table['columns']) . " Spalten){$cntInfo}{$orderInfo}";

                foreach ($table['columns'] as $col) {
                    $len      = $col['length'] ?? '';
                    $type     = $col['type'] . ($len !== '' ? "({$len})" : '');
                    $nullable = $col['nullable'] ? 'NULL' : 'NOT NULL';
                    $default  = is_null($col['default']) ? '' : ' DEFAULT ' . $col['default'];
                    $key      = $col['key']   ? ' KEY:' . $col['key'] : '';
                    $extra    = $col['extra'] ? ' ' . $col['extra'] : '';

                    $lines[] = "  - {$col['name']} : {$type} {$nullable}{$default}{$key}{$extra}";
                }

                // Beispielzeile ausgeben
                $sample = $table['sample'] ?? null;
                if (is_array($sample)) {
                    $lines[] = "Beispielzeile:";
                    $json = json_encode($sample, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
                    foreach (explode("\n", (string) $json) as $ln) {
                        $lines[] = "    " . $ln;
                    }
                } else {
                    $lines[] = "Beispielzeile: —";
                }

                $lines[] = ''; // Leerzeile zwischen Tabellen
            }

            // Optional: BOM für Windows-Notepad
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
