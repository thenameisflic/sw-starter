<?php

use App\Jobs\ComputeQueryStatistics;
use App\Models\QueryLog;
use Illuminate\Support\Facades\Cache;

test('it computes and caches query statistics', function () {
    QueryLog::create(['query' => 'anakin', 'duration' => 100, 'created_at' => now(), 'category' => 'people']);
    QueryLog::create(['query' => 'anakin', 'duration' => 200, 'created_at' => now(), 'category' => 'people']);
    QueryLog::create(['query' => 'yoda', 'duration' => 150, 'created_at' => now(), 'category' => 'people']);
    QueryLog::create(['query' => 'boba', 'duration' => 150, 'created_at' => now(), 'category' => 'people']);

    $job = new ComputeQueryStatistics();
    $job->handle();

    $cachedStatistics = Cache::get('query_statistics');

    expect($cachedStatistics['top_queries']->toArray())->toBe([
        'anakin' => 50.0,
        'yoda' => 25.0,
        'boba' => 25.0,
    ])
        ->and($cachedStatistics['average_timing'])->toBe(150.0)
        ->and($cachedStatistics['popular_hour'])->toBeInt();

});

test('it handles empty query log correctly', function () {
    QueryLog::query()->delete();

    $job = new ComputeQueryStatistics();
    $job->handle();

    $cachedStatistics = Cache::get('query_statistics');

    expect($cachedStatistics['top_queries']->toArray())->toBe([])
        ->and($cachedStatistics['average_timing'])->toBeNull()
        ->and($cachedStatistics['popular_hour'])->toBeNull();
});
