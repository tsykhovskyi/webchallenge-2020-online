<?php
declare(strict_types=1);

namespace App\Service;

use App\Document\Article;
use App\Repository\ArticleRepository;

/**
 * Class DuplicateSearcher
 */
class DuplicateSearcher
{
    private ArticleRepository $repository;

    /**
     * @var MatchThreshold
     */
    private MatchThreshold $matchThreshold;

    public function __construct(ArticleRepository $repository, MatchThreshold $matchThreshold)
    {
        $this->repository = $repository;
        $this->matchThreshold = $matchThreshold;
    }

    /**
     * @param int $id
     *
     * @return int[]
     */
    public function findForArticleId(int $id): array
    {
        $article = $this->repository->getById($id);

        [$minBound, $maxBound] = $this->matchThreshold->getAcceptableMinMax($article->getTokensLength());

        $duplicates = $this->repository->findDuplicates(
            $id,
            $article->getTokensCount(),
            $article->getTokensLength(),
            $minBound,
            $maxBound
        );

        return array_map(static fn(Article $article) => $article->getId(), $duplicates);
    }
}
