<?php
declare(strict_types=1);

namespace App\Repository;

use App\Document\Article;
use App\Models\TokenizeResult;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleRepository
{
    private DocumentManager $dm;

    private ObjectRepository $repository;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
        $this->repository = $dm->getRepository(Article::class);
    }

    public function create(string $text, TokenizeResult $result): Article
    {
        $article = new Article();
        $article->setContent($text);
        $article->setTokens($result->getTokens());
        $article->setTokensCount($result->getTokensCount());
        $article->setTokensLength($result->getLength());

        $this->dm->persist($article);
        $this->dm->flush();

        return $article;
    }

    public function getById(int $id): Article
    {
        $article = $this->repository->find($id);
        if ($article === null) {
            throw new NotFoundHttpException("Article with id=$id was not found");
        }

        return $article;
    }

    /**
     * @param int   $sourceId
     * @param array $sourceTokensCount
     * @param int   $sourceTokensLength
     * @param int   $minLengthBound
     * @param int   $maxLengthBound
     *
     * @return array
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     * @throws \JsonException
     */
    public function findDuplicates(
        int $sourceId,
        array $sourceTokensCount,
        int $sourceTokensLength,
        int $minLengthBound,
        int $maxLengthBound
    ): array
    {
        $qb = $this->dm->createQueryBuilder(Article::class);
        $result = $qb
            ->field('_id')->notEqual($sourceId)
            ->field('tokensLength')->gte($minLengthBound)
            ->field('tokensLength')->lte($maxLengthBound)
            ->where($this->createDictionaryDiffExpr($sourceTokensCount, $sourceTokensLength, (int) ($maxLengthBound - $minLengthBound) / 2))
            ->getQuery()
            ->execute();
        $articles = iterator_to_array($result);

        return $articles;
    }

    private function createDictionaryDiffExpr(array $tokensCount, int $tokensLength, int $diffLimit): string
    {
        $tokensCountObj = json_encode($tokensCount, JSON_THROW_ON_ERROR);

        return sprintf("dictionary_diff(this.tokensCount, this.tokensLength, %s, %d, %d)", $tokensCountObj, $tokensLength, $diffLimit);
    }
}
