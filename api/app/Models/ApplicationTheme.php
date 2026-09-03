<?php

namespace App\Models;

use App\Models\Concerns\IsTaxonomy;
use Database\Factories\ApplicationThemeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class ApplicationTheme extends Model
{
    /**
     * @use HasFactory<ApplicationThemeFactory>
     */
    use HasFactory;

    use HasTranslations;
    use IsTaxonomy;

    protected $table = 'application_themes';

    protected $fillable = [
        'name',
        'sort',
        'status',
    ];

    public $translatable = ['name'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'theme_id');
    }

    public function isInUse(): bool
    {
        return $this->applications()->exists();
    }
}
