<?php
declare(strict_types=1);

namespace App\Service;

/**
 * This service creates group from all related duplicates
 */
class DuplicateGrouper
{
    /**
     * @param array<int, int[]> $map
     *
     * @return int[][]
     */
    public function group(array $map): array
    {
        $duplicationGroup = [];
        $visitedIds = [];

        foreach ($map as $id => $duplicates) {
            if (count($duplicates) === 0 || in_array($id, $visitedIds, true)) {
                continue;
            }
            $group = $this->getRelatedDuplications($id, $map);
            $duplicationGroup[] = $group;
            array_push($visitedIds, ...$group);
        }

        return $duplicationGroup;
    }

    /**
     * @param int               $id
     * @param array<int, int[]> $map
     * @param array<int[]>      $visitedIds
     *
     * @return int[]
     */
    public function getRelatedDuplications(int $id, array $map, array &$visitedIds = []): array
    {
        $result = [];

        if (!isset($map[$id])) {
            return $result;
        }

        $visitedIds[] = $id;
        $result[] = $id;

        foreach ($map[$id] as $duplicateId) {
            if (in_array($duplicateId, $visitedIds, true)) {
                continue;
            }

            $relatedIds = $this->getRelatedDuplications($duplicateId, $map, $visitedIds);

            array_push($result, ...$relatedIds);
        }

        return $result;
    }
}
