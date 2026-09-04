<?php

namespace App\Models;

use App\Enums\Network;
use App\Models\Concerns\BelongsToNetwork;
use App\Models\Concerns\HasCategory;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use BelongsToNetwork;
    use HasCategory {
        BelongsToNetwork::scopeForNetwork insteadof HasCategory;
    }
    use HasTranslations;
    use Publishable;

    public const PAGE_FAQ = 'faq';

    public const PAGE_HELP = 'help';

    public const PAGE_CDMA = 'cdma';

    public const PAGES = [self::PAGE_FAQ, self::PAGE_HELP, self::PAGE_CDMA];

    protected $table = 'faqs';

    protected $fillable = [
        'category_id',
        'network',
        'question',
        'answer',
        'pages',
        'sort',
        'status',
    ];

    public $translatable = ['question', 'answer'];

    protected $casts = [
        'network' => Network::class,
        'pages' => 'array',
        'status' => 'boolean',
    ];

    protected $attributes = [
        'network' => 'both',
        'pages' => '["faq"]',
    ];

    public static function categoryModel(): string
    {
        return FaqCategory::class;
    }

    /**
     * @return array<string, string>
     */
    public static function getPageOptions(): array
    {
        return collect(self::PAGES)
            ->mapWithKeys(fn (string $page): array => [$page => __("app.faq_page.{$page}")])
            ->all();
    }

    public function scopeOnPage(Builder $query, ?string $page): Builder
    {
        if (blank($page) || ! in_array($page, self::PAGES, true)) {
            return $query;
        }

        return $query->whereJsonContains('pages', $page);
    }
}
