<?php
declare(strict_types=1);

namespace App\Service\Parser\Contract;

/**
 * Interface StemmingSource
 */
interface StemmingSource
{
    /**
     * Creates base form dictionary for words.
     *
     * @param string[] $words
     *
     * @return array<string, string> dictionary of words with their related root form
     */
    public function getBaseFormForWords(array $words): array;
}
