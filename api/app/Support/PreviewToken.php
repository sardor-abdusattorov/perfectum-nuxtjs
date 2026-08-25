<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Request;
use JsonException;

/**
 * A draft is a 404 for the site. The token lifts that for one record and for
 * whoever holds the link — an editor checking their own work, or a client asked
 * to look before it goes live.
 *
 * It names the record it opens, so a link forwarded further than intended still
 * opens nothing else, it carries its own expiry, and it is sealed with the
 * application key: nothing about it can be edited into a key for another
 * record. Nowhere does it widen a listing — a draft stays out of the feed and
 * out of the map; only its own address answers.
 */
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

    /**
     * Whether the address being answered carries a token for this very record.
     */
    public static function allows(Model $record): bool
    {
        $payload = self::payload(Request::query(self::PARAM));

        return $payload !== null
            && ($payload['type'] ?? null) === $record->getMorphClass()
            && (string) ($payload['key'] ?? '') === (string) $record->getKey()
            && ((int) ($payload['until'] ?? 0)) >= now()->getTimestamp();
    }

    /**
     * A token was offered at all — the cheap check that keeps the extra lookup
     * and the cache bypass off every ordinary request.
     */
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
