<?php

declare(strict_types=1);

namespace Tests\Service;

use App\Service\HttpClient;
use App\Service\UrlChecker;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class UrlCheckerTest extends TestCase
{
    private UrlChecker $urlChecker;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->urlChecker = new UrlChecker($this->createMock(HttpClient::class));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCheck(
        int $statusCode,
        string $fixtureFile,
        array $expected
    ): void {
        $pathToHtml = $this->getFixtureFullPath($fixtureFile);

        $urlData = [
            'statusCode' => $statusCode,
            'content' => file_get_contents($pathToHtml),
        ];

        $urlCheckData = $this->urlChecker->getUrlCheckData($urlData);

        $this->assertEquals($expected, $urlCheckData);
    }

    public static function dataProvider(): array
    {
        return [
            'status - 200, with content' => [
                'statusCode' => 200,
                'fixtureFile' => 'hexlet_black_friday.html',
                'expected' => [
                    'statusCode' => 200,
                    'h1' => 'Правила проведения Акции «Чёрная пятница 2024»',
                    'title' => 'Правила проведения Акции «Чёрная пятница 2024»',
                    'description' => 'Правила проведения Акции «Чёрная пятница 2024» с 28.10.2024 по 04.12.2024 г.',
                ],
            ],
            'status - 200, empty content' => [
                'statusCode' => 200,
                'fixtureFile' => 'empty.html',
                'expected' => [
                    'statusCode' => 200,
                    'h1' => null,
                    'title' => null,
                    'description' => null,
                ],
            ],
        ];
    }

    public function getFixtureFullPath(string $fixtureName): string
    {
        $parts = [dirname(__DIR__), 'fixtures', $fixtureName];

        return implode('/', $parts);
    }
}
