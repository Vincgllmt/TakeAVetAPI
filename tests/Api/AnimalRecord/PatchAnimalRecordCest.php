<?php

namespace Api\AnimalRecord;

use App\Entity\AnimalRecord;
use App\Factory\AnimalFactory;
use App\Factory\AnimalRecordFactory;
use App\Factory\ClientFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PatchAnimalRecordCest
{
    public function cantPatchRecordUnauthenticated(ApiTester $I): void
    {
        $animal = AnimalFactory::createOne();
        $animalRecord = AnimalRecordFactory::createOne(['Animal' => $animal]);
        $I->sendPatch("/api/animal_records/{$animalRecord->getId()}", [
            'weight' => 0,
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }

    public function cantPatchIfNotVet(ApiTester $I): void
    {
        $client = ClientFactory::createOne();
        $animal = AnimalFactory::createOne();
        $animalRecord = AnimalRecordFactory::createOne(['Animal' => $animal]);
        $I->amLoggedInAs($client->object());
        $I->sendPatch("/api/animal_records/{$animalRecord->getId()}", [
            'weight' => 0,
        ]);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
    }

    public function patchRecord(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $animal = AnimalFactory::createOne();
        $animalRecord = AnimalRecordFactory::createOne(['Animal' => $animal]);
        $I->amLoggedInAs($veto->object());
        $I->sendPatch("/api/animal_records/{$animalRecord->getId()}", [
            'weight' => 0,
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(AnimalRecord::class, "/api/animal_records/{$animalRecord->getId()}");
        $I->assertEquals(0, $animalRecord->getWeight());
    }
}
