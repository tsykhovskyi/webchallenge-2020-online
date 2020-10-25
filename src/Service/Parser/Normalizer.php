<?php
declare(strict_types=1);

namespace App\Service\Parser;

use App\Service\Parser\Contract\StemmingSource;

/**
 * Cast tokens to normal forms
 */
class Normalizer
{
    private StemmingSource $stemmingSource;

    public function __construct(StemmingSource $stemmingSource)
    {
        $this->stemmingSource = $stemmingSource;
    }

    public function normalize(array $tokens): array
    {
        $uniqueTokens = array_values(array_unique($tokens));

        $baseFormDict = $this->stemmingSource->getBaseFormForWords($uniqueTokens);

        $normalizedTokens = array_map(static fn($token) => $baseFormDict[$token] ?? $token, $tokens);

        return $normalizedTokens;
    }
}
