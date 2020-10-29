<?php
declare(strict_types=1);

namespace App\Service;

use App\Document\Article;

/**
 * Filters out duplications from the articles list.
 */
class DuplicationFilter
{
    /**
     * @param Article[] $articles
     *
     * @return Article[]
     */
    public function filter(array $articles): array
    {
        $uniqueArticles = [];
        $duplicationIds = [];
        foreach ($articles as $article) {
            if (!in_array($article->getId(), $duplicationIds, true)) {
                array_push($duplicationIds, ...$article->getDuplicateIds());
                $uniqueArticles[] = $article;
            }
        }

        return $uniqueArticles;
    }
}
