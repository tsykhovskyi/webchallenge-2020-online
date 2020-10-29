<?php
declare(strict_types=1);

namespace App\Tests\Unit\Parser;

use App\Service\Parser\Contract\StemmingSource;
use App\Service\Parser\Normalizer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class NormalizerTest
 */
class NormalizerTest extends TestCase
{
    /**
     * @var StemmingSource&MockObject
     */
    private $stemmingSource;

    private Normalizer $service;

    protected function setUp()
    {
        $this->stemmingSource = $this->createMock(StemmingSource::class);
        $this->service = new Normalizer($this->stemmingSource);
    }

    public function testNormalizer()
    {
        $this->stemmingSource->expects($this->once())->method('getBaseFormForWords')
            ->with(['il', 'buono', 'brutto', 'cattivo'])
            ->willReturn(['il' => 'the', 'buono' => 'good', 'brutto' => 'bad'])
        ;

        $tokens = ['il', 'buono', 'il', 'brutto', 'il', 'cattivo'];

        $normalizedTokens = $this->service->normalize($tokens);

        self::assertEquals(['the', 'good', 'the', 'bad', 'the', 'cattivo'], $normalizedTokens);
    }
}
