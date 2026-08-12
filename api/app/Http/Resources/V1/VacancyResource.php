<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Vacancy
 */
class VacancyResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'department' => $this->department,
            'city' => $this->city,
            'employment' => $this->employment,
            'salary' => $this->salary,
            'content' => $this->content,
        ];
    }
}
