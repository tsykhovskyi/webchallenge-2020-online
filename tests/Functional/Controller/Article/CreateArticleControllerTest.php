<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller\Article;

use App\Tests\Functional\Controller\AppTestCase;

/**
 * Class CreateArticleControllerTest
 */
class CreateArticleControllerTest extends AppTestCase
{
    public function testCreateSuccess(): void
    {
        $content = 'Lorem ipsum dolores';

        $this->client->request('POST', '/articles',  [], [], [], json_encode(['content' => $content], JSON_THROW_ON_ERROR));

        self::assertEquals(201, $this->client->getResponse()->getStatusCode());
        $expectedResponse = [
            'id' => 1,
            'content' => 'Lorem ipsum dolores',
            'duplicate_article_ids' => []
        ];
        self::assertEquals($expectedResponse, json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR));
    }

    public function testCreateWithDuplicated()
    {
        $content1 = 'Lorem ipsum dolores';
        $content2 = 'At vero eos et accusamus et iusto odio';
        $this->createArticle($content1);
        $this->createArticle($content1);
        $this->createArticle($content2);

        $this->client->request('POST', '/articles',  [], [], [], json_encode(['content' => $content1], JSON_THROW_ON_ERROR));
        $expectedResponse = [
            'id' => 4,
            'content' => $content1,
            'duplicate_article_ids' => [1, 2]
        ];
        self::assertEquals($expectedResponse, json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR));

        $this->client->request('POST', '/articles',  [], [], [], json_encode(['content' => $content2], JSON_THROW_ON_ERROR));
        $expectedResponse = [
            'id' => 5,
            'content' => $content2,
            'duplicate_article_ids' => [3]
        ];
        self::assertEquals($expectedResponse, json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR));
    }
}
