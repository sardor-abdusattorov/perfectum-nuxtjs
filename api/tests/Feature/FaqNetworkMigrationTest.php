<?php

declare(strict_types=1);

use App\Enums\Network;
use App\Models\Faq;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

/**
 * Puts the faqs table back the way the running site has it: pages, but no
 * network — the column arrived after the site went live.
 */
function rollFaqsBackToTheShapeWithoutNetwork(): void
{
    Schema::dropIfExists('faqs');

    Schema::create('faqs', function (Blueprint $table): void {
        $table->id();
        $table->foreignId('category_id')->nullable()->constrained('faq_categories')->nullOnDelete();
        $table->json('question');
        $table->json('answer');
        $table->json('pages');
        $table->integer('sort')->default(0);
        $table->boolean('status')->default(true);
        $table->timestamps();
    });
}

function addNetworkToFaqs(): void
{
    $migration = require database_path('migrations/2026_09_04_120000_add_network_to_faqs.php');

    $migration->up();
}

function liveFaq(string $question, array $pages): void
{
    DB::table('faqs')->insert([
        'question' => json_encode(['ru' => $question]),
        'answer' => json_encode(['ru' => '<p>Ответ</p>']),
        'pages' => json_encode($pages),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

it('gives a live faqs table the network column', function (): void {
    rollFaqsBackToTheShapeWithoutNetwork();
    liveFaq('Как подключиться?', ['faq', 'help']);

    expect(Schema::hasColumn('faqs', 'network'))->toBeFalse();

    addNetworkToFaqs();

    expect(Schema::hasColumn('faqs', 'network'))->toBeTrue();

    $faq = Faq::query()->first();

    expect($faq->network)->toBe(Network::Both)
        ->and($faq->question)->toBe('Как подключиться?');
});

it('hands a question that lives on the cdma page alone to cdma', function (): void {
    rollFaqsBackToTheShapeWithoutNetwork();
    liveFaq('Как проверить баланс?', ['cdma']);
    liveFaq('Общий вопрос', ['faq', 'cdma']);
    liveFaq('Вопрос сайта', ['faq']);

    addNetworkToFaqs();

    $networks = Faq::query()
        ->get()
        ->mapWithKeys(fn (Faq $faq): array => [$faq->question => $faq->network->value])
        ->all();

    expect($networks)->toHaveCount(3)
        ->and($networks['Как проверить баланс?'])->toBe('cdma')
        ->and($networks['Общий вопрос'])->toBe('both')
        ->and($networks['Вопрос сайта'])->toBe('both');
});

it('runs twice without complaining, as a re-run deploy would', function (): void {
    rollFaqsBackToTheShapeWithoutNetwork();
    liveFaq('Как проверить баланс?', ['cdma']);

    addNetworkToFaqs();
    addNetworkToFaqs();

    expect(Faq::query()->first()->network)->toBe(Network::Cdma);
});
