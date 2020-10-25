<?php
declare(strict_types=1);

namespace App\Service\Parser;

/**
 * Class Tokenizer
 */
class Tokenizer
{
    /**
     * @param string $text
     *
     * @return string[]
     */
    public function tokenize(string $text): array
    {
        $words = str_word_count($text, 1);

        return array_map('strtolower', $words);
    }
}
