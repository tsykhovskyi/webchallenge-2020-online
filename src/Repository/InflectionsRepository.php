<?php
declare(strict_types=1);

namespace App\Repository;

use App\Document\Article;
use App\Document\Inflection;
use App\Service\Parser\Contract\StemmingSource;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Class StemmingRepository
 */
class InflectionsRepository implements StemmingSource
{
    private DocumentManager $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     * @inheritDoc
     */
    public function getBaseFormForWords(array $words): array
    {
        $qb = $this->dm->createQueryBuilder(Inflection::class);

        /** @var Inflection[] $result */
        $result = $qb->field('inflected')->in($words)
            ->getQuery()
            ->execute();

        $baseFormDict = [];
        foreach ($result as $item) {
            $baseFormDict[$item->getInflected()] = $item->getBase();
        }

        return $baseFormDict;
    }
}
