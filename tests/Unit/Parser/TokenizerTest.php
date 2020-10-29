<?php
declare(strict_types=1);

namespace App\Tests\Unit\Parser;

use App\Service\Parser\Tokenizer;
use PHPUnit\Framework\TestCase;

/**
 * Class TokenizerTest
 */
class TokenizerTest extends TestCase
{
    public function testTokenizer()
    {
        $service = new Tokenizer();

        $tokens = $service->tokenize('The QUICK brown fox jumps over the lazy dog.');
        $expectedTokens = ['the', 'quick', 'brown', 'fox', 'jumps', 'over', 'the', 'lazy', 'dog'];

        self::assertEquals($expectedTokens, $tokens);
    }
}
