<?php

namespace App\Controller\Articles;

use App\Repository\ArticleRepository;
use App\View\ArticleView;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        try {
            $article = $this->articleRepository->getById($id);
        }catch (NotFoundHttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse((new ArticleView($article))->render());
    }
}
