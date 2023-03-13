<?php

namespace App\Tests\Api\Animal;

use App\Entity\Animal;
use App\Factory\AnimalFactory;
use App\Factory\ClientFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PatchAnimalCest
{
    public function cantPatchAnimalUnauthenticated(ApiTester $I): void
    {
        $animal = AnimalFactory::createOne();
        $I->sendPatch("/api/animals/{$animal->getId()}", [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }
    /*
    public function patchAnimal(ApiTester $I): void
    {
        $client = ClientFactory::createOne();
        $animal = AnimalFactory::createOne(['owner' => $client]);
        $I->amLoggedInAs($client->object());
        $I->sendPatch("/api/animals/{$animal->getId()}", [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Animal::class, "/api/animals/{$animal->getId()}");
    }
    */
}
