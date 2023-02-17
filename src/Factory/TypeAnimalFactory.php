<?php

namespace App\Factory;

use App\Entity\TypeAnimal;
use App\Repository\TypeAnimalRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<TypeAnimal>
 *
 * @method static TypeAnimal|Proxy                     createOne(array $attributes = [])
 * @method static TypeAnimal[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static TypeAnimal[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static TypeAnimal|Proxy                     find(object|array|mixed $criteria)
 * @method static TypeAnimal|Proxy                     findOrCreate(array $attributes)
 * @method static TypeAnimal|Proxy                     first(string $sortedField = 'id')
 * @method static TypeAnimal|Proxy                     last(string $sortedField = 'id')
 * @method static TypeAnimal|Proxy                     random(array $attributes = [])
 * @method static TypeAnimal|Proxy                     randomOrCreate(array $attributes = [])
 * @method static TypeAnimal[]|Proxy[]                 all()
 * @method static TypeAnimal[]|Proxy[]                 findBy(array $attributes)
 * @method static TypeAnimal[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static TypeAnimal[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static TypeAnimalRepository|RepositoryProxy repository()
 * @method        TypeAnimal|Proxy                     create(array|callable $attributes = [])
 */
final class TypeAnimalFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        $name = mb_convert_case(self::faker()->word(), MB_CASE_TITLE);

        return [
            'name' => $name,
            'icon' => 'fa-paw',
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(type $categoryAnimal): void {})
        ;
    }

    protected static function getClass(): string
    {
        return TypeAnimal::class;
    }
}
