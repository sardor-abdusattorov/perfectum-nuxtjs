<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Request;
use JsonException;

class PreviewToken
{
    public const PARAM = 'preview';

    public const TTL = 86400;

    public static function for(Model $record): string
    {
        return Crypt::encryptString(json_encode([
            'type' => $record->getMorphClass(),
            'key' => $record->getKey(),
            'until' => now()->addSeconds(self::TTL)->getTimestamp(),
        ], JSON_THROW_ON_ERROR));
    }

    public static function allows(Model $record): bool
    {
        $payload = self::payload(Request::query(self::PARAM));

        return $payload !== null
            && ($payload['type'] ?? null) === $record->getMorphClass()
            && (string) ($payload['key'] ?? '') === (string) $record->getKey()
            && ((int) ($payload['until'] ?? 0)) >= now()->getTimestamp();
    }

    public static function requested(): bool
    {
        return filled(Request::query(self::PARAM));
    }

    /**
     * @return array<string, mixed>|null
     */
    private static function payload(mixed $token): ?array
    {
        if (! is_string($token) || blank($token)) {
            return null;
        }

        try {
            $payload = json_decode(Crypt::decryptString($token), true, flags: JSON_THROW_ON_ERROR);
        } catch (DecryptException|JsonException) {
            return null;
        }

        return is_array($payload) ? $payload : null;
    }
}
