<?php
declare(strict_types=1);

namespace App\Tests\Functional;

/**
 * Class ArticleRepository
 */
class ArticleRepository extends \App\Repository\ArticleRepository
{
    protected function createDictionaryDiffExpr(array $tokensCount, int $tokensLength, int $deviation): string
    {
        return 'return true;';
    }
}
