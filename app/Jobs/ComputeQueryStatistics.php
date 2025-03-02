<?php

namespace App\Jobs;

use App\Models\QueryLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;

class ComputeQueryStatistics implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $logs = QueryLog::all();

        $queryCounts = $logs->groupBy('query')->map->count();
        $totalQueries = $queryCounts->sum();
        $topQueries = $queryCounts->sortDesc()->take(5)->mapWithKeys(function ($count, $query) use ($totalQueries) {
            return [$query => ($count / $totalQueries) * 100];
        });

        $averageTiming = $logs->avg('duration');

        $popularHour = $logs->groupBy(function ($log) {
            return $log->created_at->hour;
        })->sortDesc()->keys()->first();

        Cache::put('query_statistics', [
            'top_queries' => $topQueries,
            'average_timing' => $averageTiming,
            'popular_hour' => $popularHour,
        ], now()->addMinutes(5));
    }
}
