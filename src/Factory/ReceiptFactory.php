<?php

namespace App\Factory;

use App\Entity\Receipt;
use App\Repository\ReceiptRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Receipt>
 *
 * @method        Receipt|Proxy                     create(array|callable $attributes = [])
 * @method static Receipt|Proxy                     createOne(array $attributes = [])
 * @method static Receipt|Proxy                     find(object|array|mixed $criteria)
 * @method static Receipt|Proxy                     findOrCreate(array $attributes)
 * @method static Receipt|Proxy                     first(string $sortedField = 'id')
 * @method static Receipt|Proxy                     last(string $sortedField = 'id')
 * @method static Receipt|Proxy                     random(array $attributes = [])
 * @method static Receipt|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ReceiptRepository|RepositoryProxy repository()
 * @method static Receipt[]|Proxy[]                 all()
 * @method static Receipt[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Receipt[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Receipt[]|Proxy[]                 findBy(array $attributes)
 * @method static Receipt[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Receipt[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ReceiptFactory extends ModelFactory
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
        $priceWithoutTva = self::faker()->randomFloat(2, 35, 60);
        $priceWithTva = $priceWithoutTva * 1.2;
        $tva = $priceWithTva - $priceWithoutTva;

        return [
            'receiptAt' => self::faker()->dateTimeBetween('- 1 year'),
            'total' => $priceWithTva,
            'vat' => $tva,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Receipt $receipt): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Receipt::class;
    }
}
