<?php

namespace App\Helpers;

/**
 * Drobná práce s textem – zkrácení na zadanou délku se zachováním celých slov.
 */
class Text
{
    /**
     * Zkrátí text na maximální délku a doplní výpustku. Nezalomí slovo.
     */
    public static function truncate(string $text, int $max, string $suffix = '…'): string
    {
        $text = trim($text);

        if ($max <= 0 || mb_strlen($text) <= $max) {
            return $text;
        }

        $zkraceny = mb_substr($text, 0, $max);
        $mezera   = mb_strrpos($zkraceny, ' ');

        if ($mezera !== false && $mezera > 0) {
            $zkraceny = mb_substr($zkraceny, 0, $mezera);
        }

        return rtrim($zkraceny) . $suffix;
    }
}
