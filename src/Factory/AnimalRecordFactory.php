<?php

namespace App\Factory;

use App\Entity\AnimalRecord;
use App\Repository\AnimalRecordRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<AnimalRecord>
 *
 * @method static AnimalRecord|Proxy                     createOne(array $attributes = [])
 * @method static AnimalRecord[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static AnimalRecord[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static AnimalRecord|Proxy                     find(object|array|mixed $criteria)
 * @method static AnimalRecord|Proxy                     findOrCreate(array $attributes)
 * @method static AnimalRecord|Proxy                     first(string $sortedField = 'id')
 * @method static AnimalRecord|Proxy                     last(string $sortedField = 'id')
 * @method static AnimalRecord|Proxy                     random(array $attributes = [])
 * @method static AnimalRecord|Proxy                     randomOrCreate(array $attributes = [])
 * @method static AnimalRecord[]|Proxy[]                 all()
 * @method static AnimalRecord[]|Proxy[]                 findBy(array $attributes)
 * @method static AnimalRecord[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static AnimalRecord[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static AnimalRecordRepository|RepositoryProxy repository()
 * @method        AnimalRecord|Proxy                     create(array|callable $attributes = [])
 */
final class AnimalRecordFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'weight' => self::faker()->randomFloat(2, 30, 140),
            'height' => self::faker()->randomFloat(2, 30, 140),
            'otherInfos' => self::faker()->paragraph(1),
            'healthInfos' => self::faker()->paragraph(1),
            'updatedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-2 years', 'now')),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(AnimalRecord $animalRecord): void {})
        ;
    }

    protected static function getClass(): string
    {
        return AnimalRecord::class;
    }
}
