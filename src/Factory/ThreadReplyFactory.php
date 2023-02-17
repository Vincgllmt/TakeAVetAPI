<?php

namespace App\Factory;

use App\Entity\ThreadReply;
use App\Repository\ThreadReplyRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ThreadReply>
 *
 * @method static ThreadReply|Proxy                     createOne(array $attributes = [])
 * @method static ThreadReply[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ThreadReply[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static ThreadReply|Proxy                     find(object|array|mixed $criteria)
 * @method static ThreadReply|Proxy                     findOrCreate(array $attributes)
 * @method static ThreadReply|Proxy                     first(string $sortedField = 'id')
 * @method static ThreadReply|Proxy                     last(string $sortedField = 'id')
 * @method static ThreadReply|Proxy                     random(array $attributes = [])
 * @method static ThreadReply|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ThreadReply[]|Proxy[]                 all()
 * @method static ThreadReply[]|Proxy[]                 findBy(array $attributes)
 * @method static ThreadReply[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static ThreadReply[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ThreadReplyRepository|RepositoryProxy repository()
 * @method        ThreadReply|Proxy                     create(array|callable $attributes = [])
 */
final class ThreadReplyFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'description' => self::faker()->realTextBetween(10, 1024),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    protected function initialize(): self
    {
        return $this;
    }

    protected static function getClass(): string
    {
        return ThreadReply::class;
    }
}
