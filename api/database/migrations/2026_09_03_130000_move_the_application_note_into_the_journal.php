<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The note started as one field that every rewrite replaced. Whatever is written in
 * it now becomes the first entry of the journal rather than being dropped.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('applications', 'note')) {
            return;
        }

        DB::table('applications')
            ->whereNotNull('note')
            ->where('note', '!=', '')
            ->orderBy('id')
            ->chunkById(200, function ($applications): void {
                $rows = [];

                foreach ($applications as $application) {
                    $rows[] = [
                        'application_id' => $application->id,
                        'user_id' => null,
                        'author_name' => null,
                        'body' => $application->note,
                        'created_at' => $application->updated_at ?? now(),
                        'updated_at' => $application->updated_at ?? now(),
                    ];
                }

                DB::table('application_notes')->insert($rows);
            });

        Schema::table('applications', function (Blueprint $table): void {
            $table->dropColumn('note');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('applications', 'note')) {
            return;
        }

        Schema::table('applications', function (Blueprint $table): void {
            $table->text('note')->nullable();
        });

        $latest = DB::table('application_notes')
            ->orderBy('created_at')
            ->get()
            ->keyBy('application_id');

        foreach ($latest as $applicationId => $note) {
            DB::table('applications')->where('id', $applicationId)->update(['note' => $note->body]);
        }
    }
};
