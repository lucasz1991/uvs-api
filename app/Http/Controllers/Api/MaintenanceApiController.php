<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MaintenanceApiController extends Controller
{
    protected const THROTTLE_HOURS = 0;

    public function pruneActivityLogs(): JsonResponse
    {
        $startedAt = microtime(true);
        $throttleKey = 'maintenance:activity-log-prune:next-allowed-at';
        $nextAllowedAt = now()->addHours(self::THROTTLE_HOURS);

        if (! Cache::add($throttleKey, $nextAllowedAt->toIso8601String(), $nextAllowedAt)) {
            $cachedNextAllowedAt = Cache::get($throttleKey);
            $nextRun = $cachedNextAllowedAt ? Carbon::parse($cachedNextAllowedAt) : now()->addHours(self::THROTTLE_HOURS);

            return response()->json([
                'ok' => false,
                'throttled' => true,
                'message' => 'Activity log prune is throttled.',
                'next_allowed_at' => $nextRun->toIso8601String(),
                'retry_after_seconds' => max(1, now()->diffInSeconds($nextRun, false)),
            ], 429);
        }

        $cutoff = now()->subMonth();
        $connectionName = config('activitylog.database_connection') ?: config('database.default');
        $tableName = config('activitylog.table_name') ?: 'activity_log';
        $safeTableName = preg_replace('/[^A-Za-z0-9_]/', '', $tableName) ?: 'activity_log';
        $cutoffValue = $cutoff->format('Y-m-d H:i:s');

        try {
            $deleted = DB::connection($connectionName)->delete(
                "DELETE FROM `{$safeTableName}` WHERE `created_at` < ?",
                [$cutoffValue]
            );
        } catch (\Throwable $e) {
            Cache::forget($throttleKey);
            throw $e;
        }

        return response()->json([
            'ok' => true,
            'deleted' => $deleted,
            'cutoff' => $cutoff->toIso8601String(),
            'next_allowed_at' => $nextAllowedAt->toIso8601String(),
            'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
            'connection' => $connectionName,
            'table' => $safeTableName,
        ]);
    }
}
