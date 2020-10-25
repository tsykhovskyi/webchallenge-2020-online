<?php

namespace App\Controller\Articles;

use App\Document\Article;
use App\Service\Parser\ContentParser;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateArticleController extends AbstractController
{
    private DocumentManager $dm;
    private ContentParser $parser;

    public function __construct(DocumentManager $dm, ContentParser $parser)
    {
        $this->dm = $dm;
        $this->parser = $parser;
    }

    /**
     * @Route("/articles", methods={"POST"})
     */
    public function index(Request $request ): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $content = $data['content'];

        $tokenizeResult = $this->parser->parse($content);

        $article = new Article();
        $article->setContent($data['content']);
        $article->setTokens($tokenizeResult->getTokens());
        $article->setTokensCount($tokenizeResult->getTokensCount());
        $article->setTokensLength($tokenizeResult->getLength());

        $this->dm->persist($article);
        $this->dm->flush();

        return new Response('Created product id '.$article->getId());
    }
}
