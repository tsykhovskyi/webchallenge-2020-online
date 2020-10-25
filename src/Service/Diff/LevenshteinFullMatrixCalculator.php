<?php
declare(strict_types=1);

namespace App\Service\Diff;

/**
 * Iterative computation of Levenstien number with full matrix usage.
 *
 * @see https://en.wikipedia.org/wiki/Levenshtein_distance#Iterative_with_full_matrix
 */
class LevenshteinFullMatrixCalculator implements DistanceCalculator
{
    public function getDistance(array $sequence1, array $sequence2): int
    {
        $seq1Len = count($sequence1);
        $seq2Len = count($sequence2);

        $d = [];
        for ($i = 0; $i <= $seq1Len; $i++) {
            $d[$i][0] = $i;
        }
        for ($j = 0; $j <= $seq2Len; $j++) {
            $d[0][$j] = $j;
        }

        for ($j = 1; $j <= $seq2Len; $j++) {
            for ($i = 1; $i <= $seq1Len; $i++) {
                $substitutionCost = $sequence1[$i - 1] === $sequence2[$j - 1] ? 0 : 1;
                $d[$i][$j] = min(
                    $d[$i - 1][$j] + 1, // deletion
                    $d[$i][$j - 1] + 1, // insertion
                    $d[$i - 1][$j - 1] + $substitutionCost // substitution
                );
            }
        }

        return $d[$seq1Len][$seq2Len];
    }
}
