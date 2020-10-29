<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Document\Article;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AppTestCase
 */
class AppTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected DocumentManager $dm;

    public function setUp(): void
    {
        $this->client = static::createClient();
        /** @var DocumentManager $dm */
        $this->dm = $this->client->getContainer()->get('doctrine_mongodb.odm.default_document_manager');

        $this->cleanDB();
    }

    protected function createArticle(string $content): void
    {
        $this->client->request('POST', '/articles',  [], [], [], json_encode(['content' => $content], JSON_THROW_ON_ERROR));
    }

    public function cleanDB()
    {
        $this->dm->getSchemaManager()->dropCollections();
        $db = $this->dm->getDocumentDatabase(Article::class);
        $doctrineIdsCollection = $db->selectCollection('doctrine_increment_ids');
        $doctrineIdsCollection->drop();
    }
}
