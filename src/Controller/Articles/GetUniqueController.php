<?php

namespace App\Controller\Articles;

use App\Repository\ArticleRepository;
use App\Service\DuplicationFilter;
use App\View\ArticlesListView;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetUniqueController extends AbstractController
{
    private ArticleRepository $repository;
    private DuplicationFilter $duplicationFilter;

    public function __construct(ArticleRepository $repository, DuplicationFilter $duplicationFilter)
    {
        $this->repository = $repository;
        $this->duplicationFilter = $duplicationFilter;
    }

    /**
     * @Route("/articles", methods={"GET"})
     */
    public function index(): Response
    {
        $articles = $this->repository->all();

        $articles = $this->duplicationFilter->filter($articles);

        return new JsonResponse((new ArticlesListView($articles))->render());
    }
}
