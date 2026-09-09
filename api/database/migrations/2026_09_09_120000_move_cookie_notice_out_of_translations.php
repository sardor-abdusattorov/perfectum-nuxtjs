<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The cookie notice used to be two translation rows, which meant it could only
 * ever be flat text — there was nowhere to put the link to the policy. It is a
 * block on the homepage now, written in the editor, so the old rows have no
 * reader left and would only confuse whoever finds them in the list.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('site_translations')
            ->whereIn('key', ['cookie.text', 'cookie.accept'])
            ->delete();
    }

    public function down(): void
    {
        //
    }
};
