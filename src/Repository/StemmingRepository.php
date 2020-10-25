<?php
declare(strict_types=1);

namespace App\Repository;

use App\Service\Parser\Contract\StemmingSource;

/**
 * Class StemmingRepository
 */
class StemmingRepository implements StemmingSource
{
    /**
     * @inheritDoc
     */
    public function getBaseFormForWords(array $words): array
    {
        return [];
    }
}
