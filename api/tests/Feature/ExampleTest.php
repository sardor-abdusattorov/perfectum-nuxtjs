<?php

declare(strict_types=1);

it('sends the backend root to the admin panel', function (): void {
    $this->get('/')->assertRedirect('/admin');
});
