<?php

declare(strict_types=1);

it('answers the site and turns a stranger away', function (string $path): void {
    $site = (string) config('cors.allowed_origins')[0];

    $this->getJson("/api/v1/{$path}", ['Origin' => $site])
        ->assertHeader('Access-Control-Allow-Origin', $site);

    $response = $this->getJson("/api/v1/{$path}", ['Origin' => 'https://another-site.uz']);

    expect($response->headers->get('Access-Control-Allow-Origin'))->not->toBe('https://another-site.uz');
    expect($response->headers->get('Access-Control-Allow-Origin'))->not->toBe('*');
})->with(['site', 'faqs', 'offices']);

it('leaves the feeds the old site published open to anyone', function (string $path): void {
    $this->getJson("/api/v1/{$path}", ['Origin' => 'https://another-site.uz'])
        ->assertHeader('Access-Control-Allow-Origin', '*');
})->with(['documents', 'coverage']);
