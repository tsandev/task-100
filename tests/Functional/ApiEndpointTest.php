<?php

declare(strict_types=1);

namespace App\Tests\Functional;


use App\Tests\Factory\LogRecordFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use function Zenstruck\Foundry\faker;

class ApiEndpointTest extends WebTestCase
{
    use ResetDatabase, Factories;

    /**
     * @dataProvider filterProvider
     */
    public function testApiFilters(string $query, int $expectedCount): void
    {
        $client = static::createClient();
        LogRecordFactory::createMany(
            5,
            ['serviceName' => 'USER-SERVICE', 'statusCode' => '201', 'eventTime' => faker()->dateTimeBetween('-2 months', '-1 month')],
        );
        LogRecordFactory::createMany(
            5,
            ['serviceName' => 'INVOICE-SERVICE', 'statusCode' => '400', 'eventTime' => faker()->dateTimeBetween('-2 weeks', '-1 weeks')],
        );


        $client->request('GET', "/api/count?{$query}");

        self::assertResponseIsSuccessful();

        $content = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame($expectedCount, $content['count']);
    }

    public function filterProvider(): array
    {
        return [
            'No filters' => ['', 10],
            'Filter by statusCode' => ['statusCode=201', 5],
            'Filter by service name' => ['serviceNames[]=USER-SERVICE', 5],
            'Filter by service names' => ['serviceNames[]=USER-SERVICE&serviceNames[]=INVOICE-SERVICE', 10],
            'Filter by startDate' => [
                'startDate=' . (new \DateTimeImmutable('-2 days'))->format('Y-m-d H:i:s'),
                0,
            ],
            'Filter by endDate' => [
                'endDate=' . (new \DateTimeImmutable('-1 week'))->format('Y-m-d H:i:s'),
                10,
            ],
        ];
    }
}
