<?php

namespace App\Controller\Articles;

use App\Repository\ArticleRepository;
use App\Service\Parser\ContentParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateArticleController extends AbstractController
{
    private ContentParser $parser;

    private ArticleRepository $repository;

    public function __construct(ContentParser $parser, ArticleRepository $repository)
    {
        $this->parser = $parser;
        $this->repository = $repository;
    }

    /**
     * @Route("/articles", methods={"POST"})
     */
    public function index(Request $request ): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $content = $data['content'];

        $tokenizeResult = $this->parser->parse($content);

        $article = $this->repository->create($data['content'], $tokenizeResult);

        return new Response('Created product id '.$article->getId());
    }
}
