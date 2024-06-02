<?php

namespace App\Tests\Factory;

use App\Entity\LogRecord;
use App\Repository\LogRecordRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<LogRecord>
 *
 * @method        LogRecord|Proxy                     create(array|callable $attributes = [])
 * @method static LogRecord|Proxy                     createOne(array $attributes = [])
 * @method static LogRecord|Proxy                     find(object|array|mixed $criteria)
 * @method static LogRecord|Proxy                     findOrCreate(array $attributes)
 * @method static LogRecord|Proxy                     first(string $sortedField = 'id')
 * @method static LogRecord|Proxy                     last(string $sortedField = 'id')
 * @method static LogRecord|Proxy                     random(array $attributes = [])
 * @method static LogRecord|Proxy                     randomOrCreate(array $attributes = [])
 * @method static LogRecordRepository|RepositoryProxy repository()
 * @method static LogRecord[]|Proxy[]                 all()
 * @method static LogRecord[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static LogRecord[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static LogRecord[]|Proxy[]                 findBy(array $attributes)
 * @method static LogRecord[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static LogRecord[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class LogRecordFactory extends ModelFactory
{

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function getDefaults(): array
    {
        return [
            'eventTime' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'serviceName' => self::faker()->text(32),
            'statusCode' => self::faker()->numberBetween(1, 32767),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this;
    }

    protected static function getClass(): string
    {
        return LogRecord::class;
    }
}
