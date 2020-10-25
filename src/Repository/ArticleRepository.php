<?php
declare(strict_types=1);

namespace App\Repository;

use App\Document\Article;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ObjectRepository;

class ArticleRepository
{
    private DocumentManager $dm;
    private ObjectRepository $repository;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
        $this->repository = $dm->getRepository(Article::class);
    }

    public function getById(int $id): Article
    {
        return $this->repository->find($id);
    }
}
