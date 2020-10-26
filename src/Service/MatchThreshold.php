<?php
declare(strict_types=1);

namespace App\Service;

class MatchThreshold
{
    private float $modifier;

    /**
     * @param int $threshold for texts match in percents
     */
    public function __construct(int $threshold)
    {
        if ($threshold > 100 || $threshold < 0) {
            throw new \InvalidArgumentException("Threshold should be between 0 and 100. $threshold given");
        }

        $this->modifier = (100 - $threshold) / 100;
    }

    /**
     * @param int $length
     *
     * @return array{int, int} min/max pair for length acceptance
     */
    public function getAcceptableBounds(int $length): array
    {
        return [
            (int) round($length * (1 + $this->modifier)),
            (int) round($length * (1 - $this->modifier)),
        ];
    }
}
