<?php
declare(strict_types=1);

namespace App\Service\Diff;

/**
 * Class DistanceCalculator
 */
interface DistanceCalculator
{
    /**
     * Compute distance between 2 sequences of strings.
     *
     * @param string[] $sequence1
     * @param string[] $sequence2
     *
     * @return int
     */
    public function getDistance(array $sequence1, array $sequence2): int;
}
