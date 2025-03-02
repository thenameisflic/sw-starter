<?php

use App\Http\Controllers\APIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Inertia\Response;

beforeEach(function () {
    Cache::flush();
});

it('fetches data from the API and caches it', function () {
    Http::fake([
        'https://swapi.dev/api/films/1' => Http::response(['title' => 'A New Hope'], 200),
    ]);

    $controller = new APIController();

    $result = $controller->fetchFromApi('https://swapi.dev/api/films/1', 'film_1');

    expect($result)->toBe(['title' => 'A New Hope'])
        ->and(Cache::get('film_1'))->toBe(['title' => 'A New Hope']);

});

it('returns null if the API returns a "Not found" detail', function () {
    Http::fake([
        'https://swapi.dev/api/films/999' => Http::response(['detail' => 'Not found'], 404),
    ]);

    $controller = new APIController();

    $result = $controller->fetchFromApi('https://swapi.dev/api/films/999', 'film_999');

    expect($result)->toBeNull();
});

it('enriches items from URLs', function () {
    Http::fake([
        'https://swapi.dev/api/people/1' => Http::response(['name' => 'Luke Skywalker'], 200),
        'https://swapi.dev/api/people/2' => Http::response(['name' => 'Leia Organa'], 200),
    ]);

    $controller = new APIController();

    $result = $controller->enrichItems([
        'https://swapi.dev/api/people/1',
        'https://swapi.dev/api/people/2',
    ], 'people');

    expect($result)->toBe([
        ['name' => 'Luke Skywalker'],
        ['name' => 'Leia Organa'],
    ])
        ->and(Cache::get('people_1'))->toBe(['name' => 'Luke Skywalker'])
        ->and(Cache::get('people_2'))->toBe(['name' => 'Leia Organa']);

});

it('redirects to 404 if film details are not found', function () {
    Http::fake([
        'https://swapi.dev/api/films/999' => Http::response(['detail' => 'Not found'], 404),
    ]);

    $controller = new APIController();

    $response = $controller->filmDetails(999);

    expect($response->getTargetUrl())->toContain('/404');
});

it('renders the film details view with enriched characters', function () {
    Http::fake([
        'https://swapi.dev/api/films/1' => Http::response([
            'title' => 'A New Hope',
            'characters' => [
                'https://swapi.dev/api/people/1',
                'https://swapi.dev/api/people/2',
            ],
        ], 200),
        'https://swapi.dev/api/people/1' => Http::response(['name' => 'Luke Skywalker'], 200),
        'https://swapi.dev/api/people/2' => Http::response(['name' => 'Leia Organa'], 200),
    ]);

    $controller = new APIController();

    $response = $controller->filmDetails(1);

    $request = Request::create('/query', 'GET', [
        'category' => 'invalid',
        'query' => '',
    ]);

    expect($response)->toBeInstanceOf(Response::class);
});

it('validates the query request', function () {
    $controller = new APIController();

    $request = Request::create('/query', 'GET', [
        'category' => 'invalid',
        'query' => '',
    ]);

    $this->expectException(ValidationException::class);
    $controller->query($request);
});
