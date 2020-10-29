<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller\Article;

use App\Document\Article;
use App\Tests\Functional\Controller\AppTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class GetByIdControllerTest
 */
class GetByIdControllerTest extends AppTestCase
{
    public function setUp():void
    {
        parent::setUp();

        $article = new Article();
        $article->setContent('Hi there');
        $article->setTokens([]);
        $article->setTokensCount([]);
        $article->setTokensLength(2);
        $article->setDuplicateIds([2, 3]);
        $this->dm->persist($article);
        $this->dm->flush();
    }

    public function testArticleNotFound(): void
    {
        $this->client->request('GET', '/articles/5');
        self::assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testArticleGetSuccess(): void
    {
        $this->client->request('GET', '/articles/1');

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        $expectedResponse = [
            'id' => 1,
            'content' => 'Hi there',
            'duplicate_article_ids' => [2, 3]
        ];
        self::assertEquals($expectedResponse, json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR));
    }
}
