<?php
declare(strict_types=1);

namespace App\Service;

/**
 * Service that stores allowed threshold to define 2 texts are similar.
 */
class MatchThreshold
{
    private int $threshold;

    /**
     * @param int $threshold for texts match in percents
     */
    public function __construct(int $threshold)
    {
        if ($threshold > 100 || $threshold < 0) {
            throw new \InvalidArgumentException("Threshold should be between 0 and 100. $threshold given");
        }

        $this->threshold = $threshold;
    }

    /**
     * Return acceptance diff value for the $size. $size - $diff < X < $size + $diff
     *
     * @param int $size
     *
     * @return int
     */
    public function getDiffLimitForSize(int $size): int
    {
        return (int) floor($size * ((100 - $this->threshold) / 100));
    }
}
