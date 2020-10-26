<?php

namespace App\Controller\Articles;

use App\Repository\ArticleRepository;
use App\Service\DuplicateSearcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetByIdController extends AbstractController
{
    private ArticleRepository $articleRepository;

    /**
     * @var DuplicateSearcher
     */
    private DuplicateSearcher $duplicateSearcher;

    public function __construct(ArticleRepository $articleRepository, DuplicateSearcher $duplicateSearcher)
    {
        $this->articleRepository = $articleRepository;
        $this->duplicateSearcher = $duplicateSearcher;
    }

    /**
     * @Route("/articles/{id}", methods={"GET"})
     * @param int $id
     *
     * @return Response
     */
    public function index(int $id): Response
    {
        $article = $this->articleRepository->getById($id);

        $duplicates = $this->duplicateSearcher->findForArticleId($id);

        return $this->json([
            'id' => $article->getId(),
            'content' => $article->getContent(),
            'duplicate_article_ids' => $duplicates,
        ]);
    }
}
