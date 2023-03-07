<?php

namespace App\Tests\Api\Thread;

use App\Entity\Thread;
use App\Factory\ClientFactory;
use App\Factory\ThreadFactory;
use App\Tests\Support\ApiTester;

class GetThreadCest
{
    private static function exceptedThreadProperties(bool $isFull): array
    {
        $properties = [
            'id' => 'integer',
            'subject' => 'string',
            'description' => 'string',
            'createdAt' => 'string',
            'author' => 'array|null',
            'resolved' => 'boolean',
        ];

        if ($isFull) {
            $properties['replies'] = 'array';
        }

        return $properties;
    }

    public function anyoneCanGetThreads(ApiTester $I): void
    {
        ThreadFactory::createMany(2, [
            'author' => ClientFactory::createOne(),
        ]);

        $I->sendGet('/api/threads');

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseIsACollection(Thread::class, '/api/threads');
        $I->seeResponseMatchesJsonType([
            'hydra:totalItems' => 'integer',
            'hydra:member' => [
                self::exceptedThreadProperties(false),
                self::exceptedThreadProperties(false),
            ],
        ]);
    }

    public function anyoneCanGetOneThreadWithHisId(ApiTester $I): void
    {
        $thread = ThreadFactory::createOne([
            'author' => ClientFactory::createOne(),
        ]);

        $I->sendGet("/api/threads/{$thread->getId()}");

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseIsAnItem(self::exceptedThreadProperties(true));
    }
}
