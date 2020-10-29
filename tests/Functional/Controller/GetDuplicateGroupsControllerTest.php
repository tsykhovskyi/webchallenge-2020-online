<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller;

/**
 * Class GetDuplicateGroupsControllerTest
 */
class GetDuplicateGroupsControllerTest extends AppTestCase
{
    public function testGetDuplicateGroups(): void
    {
        $this->createArticle('Lorem ipsum');
        $this->createArticle('Lorem ipsum');
        $this->createArticle('Hi there');
        $this->createArticle('Another');
        $this->createArticle('Another');
        $this->createArticle('Another');

        $this->client->request('GET', '/duplicate_groups');

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        $expectedResponse = [
            [1, 2],
            [4, 5, 6],
        ];
        self::assertEquals($expectedResponse, json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR));
    }
}
