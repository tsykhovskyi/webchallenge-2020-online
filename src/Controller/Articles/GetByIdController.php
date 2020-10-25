<?php

namespace App\Controller\Articles;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetByIdController extends AbstractController
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
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

        return $this->json([
            'id' => $article->getId(),
            'content' => $article->getContent(),
            'duplicate_article_ids' => $article->getTokensCount(),
        ]);
    }
}
