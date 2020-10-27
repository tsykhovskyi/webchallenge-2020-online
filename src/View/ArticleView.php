<?php
declare(strict_types=1);

namespace App\View;

use App\Document\Article;

/**
 * Class ArticleView
 */
class ArticleView
{
    /**
     * @var Article
     */
    private Article $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function render(): array
    {
        return [
            'id' => $this->article->getId(),
            'content' => $this->article->getContent(),
            'duplicate_article_ids' => $this->article->getDuplicateIds(),
        ];
    }
}
