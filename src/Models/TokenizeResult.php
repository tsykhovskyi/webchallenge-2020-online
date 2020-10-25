<?php
declare(strict_types=1);

namespace App\Models;

/**
 * Class TextReult
 */
class TokenizeResult
{
    private $tokens;

    private $length;

    /**
     * @param string[] $tokens
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
        $this->length = count($tokens);
    }
}
