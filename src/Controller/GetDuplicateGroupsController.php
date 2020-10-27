<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Service\DuplicateGrouper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetDuplicateGroupsController extends AbstractController
{
    private ArticleRepository $articleRepository;
    private DuplicateGrouper $duplicateGrouper;

    public function __construct(ArticleRepository $articleRepository, DuplicateGrouper $duplicateGrouper)
    {
        $this->articleRepository = $articleRepository;
        $this->duplicateGrouper = $duplicateGrouper;
    }

    /**
     * @Route("/duplicate_groups", methods={"GET"})
     */
    public function index(): Response
    {
        $map = $this->articleRepository->duplicationsMap();

        $duplicationGroups = $this->duplicateGrouper->group($map);

        return new JsonResponse($duplicationGroups);
    }
}
