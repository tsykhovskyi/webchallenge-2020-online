<?php
declare(strict_types=1);

namespace App\View;

use App\Document\Article;

/**
 * Class ArticlesLIstView
 */
class ArticlesListView
{
    /**
     * @var Article[]
     */
    private array $articles;

    public function __construct(array $articles)
    {
        $this->articles = $articles;
    }

    public function render(): array
    {
        return [
            'articles' => array_map(
                static fn(Article $article) => (new ArticleView($article))->render(),
                $this->articles
            )
        ];
    }
}
