<?php
declare(strict_types=1);

namespace App\Models;

/**
 * Class TextReult
 */
class TokenizeResult
{
    private array $tokens;

    private int $length;

    /**
     * @var array<string, int>
     */
    private array $tokensCount;

    /**
     * @param string[] $tokens
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
        $this->length = count($tokens);

        $this->tokensCount = array_count_values($this->tokens);
        arsort($this->tokensCount);
    }

    public function getTokens(): array
    {
        return $this->tokens;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getTokensCount(): array
    {
        return $this->tokensCount;
    }
}
