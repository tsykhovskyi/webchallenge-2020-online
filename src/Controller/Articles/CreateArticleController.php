<?php

namespace App\Controller\Articles;

use App\Repository\ArticleRepository;
use App\Service\DuplicateSearcher;
use App\Service\Parser\ContentParser;
use App\View\ArticleView;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CreateArticleController extends AbstractController
{
    private ContentParser $parser;

    private ArticleRepository $repository;

    private DuplicateSearcher $duplicateSearcher;

    public function __construct(ContentParser $parser, ArticleRepository $repository, DuplicateSearcher $duplicateSearcher)
    {
        $this->parser = $parser;
        $this->repository = $repository;
        $this->duplicateSearcher = $duplicateSearcher;
    }

    /**
     * @Route("/articles", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     * @throws \JsonException
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return new JsonResponse(['error' => 'Invalid JSON'], 400);
        }
        $content = $data['content'] ?? null;
        if (null === $content || '' === $content) {
            return new JsonResponse(['error' => 'Article content should be not empty'], 400);
        }

        $tokenizeResult = $this->parser->parse($content);
        $article = $this->repository->create($content, $tokenizeResult);
        $this->duplicateSearcher->findAndUpdateDuplicatesForArticle($article);

        return new JsonResponse((new ArticleView($article))->render(), Response::HTTP_CREATED);
    }
}
