<?php

namespace App\Support\Pagination;

use Illuminate\Pagination\Cursor;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;

class EncryptedCursorPaginator extends CursorPaginator
{
    /**
     * Get the URL for a given cursor, encrypting the cursor payload to prevent
     * leaking database schema details (e.g. column names, order directions, timestamps).
     *
     * @param  \Illuminate\Pagination\Cursor|null  $cursor
     * @return string|null
     */
    public function url($cursor)
    {
        if (is_null($cursor)) {
            return null;
        }

        $rawEncoded = $cursor instanceof Cursor ? $cursor->encode() : (string) $cursor;
        $encryptedCursor = self::encryptCursor($rawEncoded);

        $parameters = [$this->cursorName => $encryptedCursor];

        if (count($this->query) > 0) {
            $parameters = array_merge($this->query, $parameters);
        }

        return $this->path()
            . (str_contains($this->path(), '?') ? '&' : '?')
            . Arr::query($parameters)
            . $this->buildFragment();
    }

    /**
     * Encrypt an encoded cursor string into a secure, URL-safe ciphertext token.
     *
     * @param  string  $encodedCursor
     * @return string
     */
    public static function encryptCursor(string $encodedCursor): string
    {
        $encrypted = Crypt::encryptString($encodedCursor);

        // Convert base64 special characters to URL-safe characters
        return str_replace(['+', '/', '='], ['-', '_', '~'], $encrypted);
    }

    /**
     * Decrypt a URL cursor token back into an Illuminate\Pagination\Cursor object.
     * Returns null if the token is missing, invalid, or tampered with.
     *
     * @param  string|null  $rawToken
     * @return \Illuminate\Pagination\Cursor|null
     */
    public static function decryptCursor(?string $rawToken): ?Cursor
    {
        if (empty($rawToken) || !is_string($rawToken)) {
            return null;
        }

        try {
            $normalized = str_replace(['-', '_', '~'], ['+', '/', '='], $rawToken);
            $decrypted = Crypt::decryptString($normalized);

            return Cursor::fromEncoded($decrypted);
        } catch (\Throwable $e) {
            // Backward compatibility fallback for legacy unencrypted base64 cursors
            try {
                $decoded = @base64_decode($rawToken, true);
                if ($decoded !== false) {
                    $json = @json_decode($decoded, true);
                    if (is_array($json) && (isset($json['_pointsToNextItems']) || isset($json['created_at']))) {
                        return Cursor::fromEncoded($rawToken);
                    }
                }
            } catch (\Throwable $fallbackError) {
                // Silently discard fallback errors
            }

            return null;
        }
    }

    /**
     * Get the encrypted cursor token for the next page, if available.
     *
     * @return string|null
     */
    public function nextCursorEncrypted(): ?string
    {
        $next = $this->nextCursor();

        return $next ? self::encryptCursor($next->encode()) : null;
    }

    /**
     * Get the encrypted cursor token for the previous page, if available.
     *
     * @return string|null
     */
    public function previousCursorEncrypted(): ?string
    {
        $prev = $this->previousCursor();

        return $prev ? self::encryptCursor($prev->encode()) : null;
    }
}
