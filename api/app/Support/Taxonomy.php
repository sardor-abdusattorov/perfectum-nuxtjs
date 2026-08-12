<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Schema\Blueprint;

class Taxonomy
{
    public static function columns(Blueprint $table): void
    {
        $table->id();
        $table->json('name');
        $table->string('slug')->unique();

        $table->string('network', 10)->default('both')->index();
        $table->unsignedInteger('sort')->default(0);
        $table->boolean('status')->default(true)->index();
        $table->timestamps();
    }
}
