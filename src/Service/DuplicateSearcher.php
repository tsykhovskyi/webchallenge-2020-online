<?php
declare(strict_types=1);

namespace App\Service;

use App\Document\Article;
use App\Repository\ArticleRepository;
use App\Service\Diff\DistanceCalculator;

/**
 * Class DuplicateSearcher
 */
class DuplicateSearcher
{
    private ArticleRepository $repository;
    private MatchThreshold $matchThreshold;
    private DistanceCalculator $distanceCalculator;

    public function __construct(ArticleRepository $repository, MatchThreshold $matchThreshold, DistanceCalculator $distanceCalculator)
    {
        $this->repository = $repository;
        $this->matchThreshold = $matchThreshold;
        $this->distanceCalculator = $distanceCalculator;
    }

    /**
     * @param Article $article
     *
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     * @throws \JsonException
     */
    public function findAndUpdateDuplicatesForArticle(Article $article)
    {
        $diffLimit = $this->matchThreshold->getDiffLimitForSize($article->getTokensLength());

        $potentialDuplicates = $this->repository->findArticlesWithSimilarTokens(
            $article->getId(),
            $article->getTokensCount(),
            $article->getTokensLength(),
            $diffLimit
        );

        if (count($potentialDuplicates) === 0) {
            return;
        }

        $duplicateIds = [];
        foreach ($potentialDuplicates as $potentialDuplicate) {
            $articlesDistance = $this->distanceCalculator->getDistance($article->getTokens(), $potentialDuplicate->getTokens());
            if ($articlesDistance <= $diffLimit) {
                $duplicateIds[] = $potentialDuplicate->getId();
            }
        }

        if (count($duplicateIds) === 0) {
            return;
        }

        $this->repository->addDuplicatedGroup($article->getId(), $duplicateIds);
        $article->setDuplicateIds($duplicateIds);
    }
}
