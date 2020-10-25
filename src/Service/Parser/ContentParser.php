<?php
declare(strict_types=1);

namespace App\Service\Parser;

use App\Models\TokenizeResult;

/**
 * Provides ready to process tokens from text.
 */
class ContentParser
{
    private Tokenizer $tokenizer;
    private Normalizer $normalizer;

    public function __construct(Tokenizer $tokenizer, Normalizer $normalizer)
    {
        $this->tokenizer = $tokenizer;
        $this->normalizer = $normalizer;
    }

    /**
     * @param string $text
     *
     * @return TokenizeResult
     */
    public function parse(string $text): TokenizeResult
    {
        $tokens = $this->tokenizer->tokenize($text);

        $filteredTokens = $this->normalizer->normalize($tokens);

        return new TokenizeResult($filteredTokens);
    }
}
