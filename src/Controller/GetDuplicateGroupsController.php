<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetDuplicateGroupsController extends AbstractController
{
    /**
     * @var ArticleRepository
     */
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route("/duplicate_groups", methods={"GET"})
     */
    public function index(): Response
    {
        $groups = $this->articleRepository->duplicationsMap();

        return new JsonResponse($groups);
    }
}
