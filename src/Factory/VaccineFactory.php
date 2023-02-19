<?php

namespace App\Factory;

use App\Entity\Vaccine;
use App\Repository\VaccineRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Vaccine>
 *
 * @method        Vaccine|Proxy                     create(array|callable $attributes = [])
 * @method static Vaccine|Proxy                     createOne(array $attributes = [])
 * @method static Vaccine|Proxy                     find(object|array|mixed $criteria)
 * @method static Vaccine|Proxy                     findOrCreate(array $attributes)
 * @method static Vaccine|Proxy                     first(string $sortedField = 'id')
 * @method static Vaccine|Proxy                     last(string $sortedField = 'id')
 * @method static Vaccine|Proxy                     random(array $attributes = [])
 * @method static Vaccine|Proxy                     randomOrCreate(array $attributes = [])
 * @method static VaccineRepository|RepositoryProxy repository()
 * @method static Vaccine[]|Proxy[]                 all()
 * @method static Vaccine[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Vaccine[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Vaccine[]|Proxy[]                 findBy(array $attributes)
 * @method static Vaccine[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Vaccine[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class VaccineFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function getDefaults(): array
    {
        $lastDate = self::faker()->dateTimeBetween('-1 year');
        $nextDate = self::faker()->dateTimeBetween($lastDate, '+5 year');

        return [
            'name' => self::faker()->word(),
            'last' => $lastDate,
            'next' => $nextDate,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Vaccine $vaccine): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Vaccine::class;
    }
}
