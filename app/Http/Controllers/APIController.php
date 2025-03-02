<?php

namespace App\Http\Controllers;

use App\Models\QueryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class APIController extends Controller
{
    public function fetchFromApi(string $url, string $cacheKey, int $cacheDurationInDays = 7)
    {
        $data = Cache::get($cacheKey);

        if (!$data) {
            $response = Http::get($url);
            $data = $response->json();

            if ($data['detail'] ?? null == 'Not found') {
                return null;
            }

            Cache::put($cacheKey, $data, now()->addDays($cacheDurationInDays));
        }

        return $data;
    }

    public function enrichItems(array $urls, string $cachePrefix)
    {
        $enrichedItems = [];

        foreach ($urls as $url) {
            $itemId = basename(rtrim($url, '/'));
            $cacheKey = "{$cachePrefix}_{$itemId}";

            $item = $this->fetchFromApi($url, $cacheKey);

            if ($item) {
                $enrichedItems[] = $item;
            }
        }

        return $enrichedItems;
    }

    public function query(Request $request): Response
    {
        $request->validate([
            'category' => 'required|in:people,films',
            'query' => 'required'
        ]);

        $startTime = microtime(true);

        try {
            $response = Http::get("https://swapi.dev/api/{$request->category}?search={$request->input('query')}");
            $duration = microtime(true) - $startTime;
            QueryLog::create([
                'category' => $request->category,
                'query' => $request->input('query'),
                'duration' => $duration,
                'created_at' => now(),
            ]);

            return Inertia::render('welcome', [
                'response' => $response->json(),
                'category' => $request->input('category'),
                'query' => $request->input('query'),
            ]);
        } catch (\Exception $e) {
            $duration = microtime(true) - $startTime;
            QueryLog::create([
                'category' => $request->category,
                'query' => $request->input('query'),
                'duration' => $duration,
                'created_at' => now(),
            ]);

            throw ValidationException::withMessages([
                'api' => 'We couldn\'t reach the Star Wars API. It could be offline, or under maintenance. Please try again later.',
            ]);
        }
    }

    public function filmDetails($id)
    {
        $cacheKey = "film_{$id}";
        $json = $this->fetchFromApi("https://swapi.dev/api/films/{$id}", $cacheKey);

        if (!$json) {
            return redirect('/404');
        }

        $json['characters'] = $this->enrichItems($json['characters'], 'people');

        return Inertia::render('film', [
            'id' => $id,
            'response' => $json
        ]);
    }

    public function peopleDetails($id)
    {
        $cacheKey = "people_{$id}";
        $json = $this->fetchFromApi("https://swapi.dev/api/people/{$id}", $cacheKey);

        if (!$json) {
            return redirect('/404');
        }

        $json['films'] = $this->enrichItems($json['films'], 'film');

        return Inertia::render('people', [
            'id' => $id,
            'response' => $json
        ]);
    }

    public function statistics()
    {
        $statistics = Cache::get('query_statistics', [
            'top_queries' => [],
            'average_timing' => 0,
            'popular_hour' => null,
        ]);

        return response()->json($statistics);
    }
}
