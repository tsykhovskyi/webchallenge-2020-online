<?php
declare(strict_types=1);

namespace App\Repository;

use App\Document\Article;
use App\Models\TokenizeResult;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Query\Builder;
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
     * @return Article[]
     */
    public function all(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @return array<int, int[]> duplications list per each article id.
     */
    public function duplicationsMap(): array
    {
        $result = $this->createQueryBuilder()
            ->select('_id', 'duplicateIds')
            ->hydrate(false)
            ->getQuery()
            ->execute();

        $map = [];
        foreach ($result as $item) {
            $map[$item['_id']] = $item['duplicateIds'];
        }

        return $map;
    }

    /**
     * @param int   $sourceId
     * @param array $sourceTokensCount
     * @param int   $sourceTokensLength
     * @param int   $deviation
     *
     * @return Article[]
     */
    public function findArticlesWithSimilarTokens(
        int $sourceId,
        array $sourceTokensCount,
        int $sourceTokensLength,
        int $deviation
    ): array
    {
        $qb = $this->createQueryBuilder();
        $result = $qb
            ->field('_id')->notEqual($sourceId)
            ->field('tokensLength')->gte($sourceTokensLength - $deviation)
            ->field('tokensLength')->lte($sourceTokensLength + $deviation)
            ->where($this->createDictionaryDiffExpr($sourceTokensCount, $sourceTokensLength, $deviation))
            ->getQuery()
            ->execute();

        return $result->toArray();
    }

    /**
     * @param int   $articleId
     * @param int[] $duplicateIds
     */
    public function addDuplicatedGroup(int $articleId, array $duplicateIds): void
    {
        $this->createQueryBuilder()
            ->updateMany()
            ->field('duplicateIds')->addToSet($articleId)
            ->field('_id')->in($duplicateIds)
            ->getQuery()
            ->execute();

        $this->createQueryBuilder()
            ->updateOne()
            ->field('duplicateIds')->set($duplicateIds)
            ->field('_id')->equals($articleId)
            ->getQuery()
            ->execute();
    }

    protected function createDictionaryDiffExpr(array $tokensCount, int $tokensLength, int $deviation): string
    {
        $tokensCountObj = json_encode($tokensCount, JSON_THROW_ON_ERROR);

        return sprintf("dictionary_diff(this.tokensCount, this.tokensLength, %s, %d, %d)", $tokensCountObj, $tokensLength, $deviation);
    }

    private function createQueryBuilder(): Builder
    {
        return $this->dm->createQueryBuilder(Article::class);
    }
}
