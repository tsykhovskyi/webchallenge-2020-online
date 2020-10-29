<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller\Article;

use App\Tests\Functional\Controller\AppTestCase;

/**
 * Class GetUniqueControllerTest
 */
class GetUniqueControllerTest extends AppTestCase
{
    public function testGetUniqueArticles(): void
    {
        $this->createArticle('Lorem ipsum');
        $this->createArticle('Lorem ipsum');
        $this->createArticle('Hi there');
        $this->createArticle('Another');
        $this->createArticle('Another');
        $this->createArticle('Another');

        $this->client->request('GET', '/articles');

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        $expectedResponse = [
            'articles' => [
                [
                    'id' => 1,
                    'content' => 'Lorem ipsum',
                    'duplicate_article_ids' => [2]
                ],
                [
                    'id' => 3,
                    'content' => 'Hi there',
                    'duplicate_article_ids' => []
                ],
                [
                    'id' => 4,
                    'content' => 'Another',
                    'duplicate_article_ids' => [5, 6]
                ]
            ]
        ];
        self::assertEquals($expectedResponse, json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR));
    }
}
