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
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $content;

    /**
     * @MongoDB\Field(type="raw")
     */
    protected $tokens;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $tokensLength;

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
     * @param string[] $tokens
     */
    public function setTokens(array $tokens): self
    {
        $this->tokens = $tokens;

        return $this;
    }

    public function setTokensLength(int $tokensLength): self
    {
        $this->tokensLength = $tokensLength;

        return $this;
    }
}
