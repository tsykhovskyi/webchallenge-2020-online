<?php
declare(strict_types=1);

namespace App\Tests\Unit\Diff;

use App\Service\Diff\LevenshteinFullMatrixCalculator;
use PHPUnit\Framework\TestCase;

/**
 * Class LevenshteinFullMatrixCalculatorTest
 */
class LevenshteinFullMatrixCalculatorTest extends TestCase
{
    private LevenshteinFullMatrixCalculator $service;

    public function setUp()
    {
        $this->service = new LevenshteinFullMatrixCalculator();
    }

    public function sequencesData(): array
    {
        return [
            'same tokens' => [
                ['A', 'A', 'A', 'A', 'A', 'A', 'A'],
                ['A', 'A', 'A', 'A', 'A', 'A', 'A'],
                0
            ],
            'deleted tokens' => [
                ['A', 'A', 'A', 'A', 'A', 'A', 'A'],
                ['A', 'A', 'A', 'A'],
                3
            ],
            'inserted in the middle' => [
                ['A', 'B', 'C', 'D'],
                ['A', 'B', 'A', 'D', 'C', 'D'],
                2
            ],
            'replaced tokens' => [
                ['The', 'quick', 'brown', 'fox', 'jumps', 'over', 'the', 'lazy', 'dog'],
                ['The', 'quick', 'red', 'fox', 'jumps', 'at', 'the', 'lazy', 'cat'],
                3
            ],
            'create/remove/replace tokens' => [
                ['The', 'quick', 'brown', 'fox', 'jumps', 'over', 'the', 'lazy', 'dog'],
                ['fast', 'brown', 'fox', 'Alice', 'decides', 'to', 'jumps', 'over', 'lazy', 'dog'],
                6
            ],
        ];
    }

    /**
     * @dataProvider sequencesData
     */
    public function testDistanceCalculation(array $tokensSequence1, array $tokensSequence2, int $expectedDistance): void
    {
        $distance = $this->service->getDistance($tokensSequence1, $tokensSequence2);
        self::assertEquals($expectedDistance, $distance);

        // Distance should be the same for swapped sequences
        $distance = $this->service->getDistance($tokensSequence2, $tokensSequence1);
        self::assertEquals($expectedDistance, $distance);
    }
}
