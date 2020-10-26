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
     * @param array<string, int> $tokens
     */
    public function findDuplicates(array $tokens)
    {
        $qb = $this->dm->createQueryBuilder(Article::class);
        $result = $qb
//            ->field("tokensLength")->gt(1)
            ->where($this->createDictionaryDiffExpr($tokens))
            ->getQuery()
            ->execute();

        return $result;
    }

    private function createDictionaryDiffExpr(array $tokens)
    {
        $compareTokens = json_encode(["hello" => 2, "this" => 1, "is" => 1, "me" => 1], JSON_THROW_ON_ERROR);

        return sprintf("dictionary_diff(this.tokensCount, this.tokensLength, %s, %d, %d)", $compareTokens, 5, 1);
    }
}
