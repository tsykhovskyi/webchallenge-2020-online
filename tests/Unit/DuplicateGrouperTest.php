<?php
declare(strict_types=1);

namespace App\Tests\Unit;

use App\Service\DuplicateGrouper;
use PHPUnit\Framework\TestCase;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

class DuplicateGrouperTest extends TestCase
{
    private DuplicateGrouper $service;

    protected function setUp()
    {
        $this->service = new DuplicateGrouper();
    }

    public function testDuplicatedGroupsCreation()
    {
        $duplicationsMap = [
            1 => [2, 3],
            2 => [4, 1],
            3 => [1],
            4 => [2],
            5 => [],
            6 => [7],
            7 => [6, 8, 9],
            8 => [7],
            9 => [7]
        ];

        $groups = $this->service->group($duplicationsMap);

        self::assertEquals([
            [1, 2, 3, 4],
            [6, 7, 8, 9]
        ], $groups);
    }
}
