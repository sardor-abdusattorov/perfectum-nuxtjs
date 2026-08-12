<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Schema\Blueprint;

class Taxonomy
{
    public static function columns(Blueprint $table, bool $withCode = false): void
    {
        $table->id();
        $table->json('name');
        $table->string('slug')->unique();

        if ($withCode) {
            $table->string('code', 32)->nullable()->index();
        }

        $table->string('network', 10)->default('both')->index();
        $table->unsignedInteger('sort')->default(0);
        $table->boolean('status')->default(true)->index();
        $table->timestamps();
    }
}
