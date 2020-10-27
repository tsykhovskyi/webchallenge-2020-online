<?php
declare(strict_types=1);

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Article
{
    /**
     * @MongoDB\Id(strategy="INCREMENT")
     */
    protected int $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $content;

    /**
     * @var int[]
     *
     * @MongoDB\Field(type="raw")
     */
    protected array $duplicateIds = [];

    /**
     * @MongoDB\Field(type="raw")
     */
    protected array $tokens;

    /**
     * @var array<string, int>
     *
     * @MongoDB\Field(type="raw")
     */
    protected array $tokensCount;

    /**
     * @MongoDB\Field(type="int")
     */
    protected int $tokensLength;

    public function getId()
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * @param string[] $tokens
     */
    public function setTokens(array $tokens): self
    {
        $this->tokens = $tokens;

        return $this;
    }

    /**
     * @return array<string, int>
     */
    public function getTokensCount(): array
    {
        return $this->tokensCount;
    }

    /**
     * @param array<string, int> $tokensCount
     */
    public function setTokensCount(array $tokensCount): self
    {
        $this->tokensCount = $tokensCount;

        return $this;
    }

    public function getTokensLength(): int
    {
        return $this->tokensLength;
    }

    public function setTokensLength(int $tokensLength): self
    {
        $this->tokensLength = $tokensLength;

        return $this;
    }

    /**
     * @param int[] $duplicateIds
     */
    public function setDuplicateIds(array $duplicateIds): self
    {
        $this->duplicateIds = $duplicateIds;

        return $this;
    }

    /**
     * @return int[]
     */
    public function getDuplicateIds(): array
    {
        return $this->duplicateIds;
    }
}
