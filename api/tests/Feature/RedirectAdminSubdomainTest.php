<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
});

it('sends an api request on the admin subdomain to the main domain, query and all', function (): void {
    $this->getJson('http://admin.perfectum.uz/api/v1/news?network=5g&page=2')
        ->assertMovedPermanently()
        ->assertRedirect('http://perfectum.uz/api/v1/news?network=5g&page=2');
});

it('keeps the scheme the visitor came with', function (): void {
    $this->getJson('https://admin.perfectum.uz/api/v1/site')
        ->assertMovedPermanently()
        ->assertRedirect('https://perfectum.uz/api/v1/site');
});

it('sends a post on without letting the client turn it into a get', function (): void {
    $this->postJson('https://admin.perfectum.uz/api/v1/news', ['lang' => 'uz'])
        ->assertStatus(308)
        ->assertRedirect('https://perfectum.uz/api/v1/news');
});

it('answers the api on the main domain as before', function (): void {
    $this->getJson('http://perfectum.uz/api/v1/site')->assertSuccessful();
});

it('leaves the panel itself alone on the admin subdomain', function (): void {
    $this->get('http://admin.perfectum.uz'.panel('/login'))->assertSuccessful();
});

it('does not touch a host that merely contains the word', function (): void {
    $this->getJson('http://myadmin.perfectum.uz/api/v1/site')->assertSuccessful();
});
