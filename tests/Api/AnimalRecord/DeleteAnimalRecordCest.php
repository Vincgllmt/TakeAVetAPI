<?php

namespace Api\AnimalRecord;

use App\Factory\AnimalFactory;
use App\Factory\AnimalRecordFactory;
use App\Factory\ClientFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class DeleteAnimalRecordCest
{
    public function anonymousVetoForbiddenToDelete(ApiTester $I): void
    {
        $animal = AnimalFactory::createOne();
        $animalRecord = AnimalRecordFactory::createOne(['Animal' => $animal]);
        $I->sendDelete("/api/animal_records/{$animalRecord->getId()}");
        $I->seeResponseCodeIs(401);
    }

    public function VetoCanDeleteRecord(ApiTester $I): void
    {
        $user = VetoFactory::createOne();
        $animal = AnimalFactory::createOne();
        $animalRecord = AnimalRecordFactory::createOne(['Animal' => $animal]);
        $I->amLoggedInAs($user->object());
        $I->sendDelete("/api/animal_records/{$animalRecord->getId()}");
        $I->seeResponseCodeIs(204);
    }

    public function ClientForbiddenToDeleteRecord(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $animal = AnimalFactory::createOne();
        $animalRecord = AnimalRecordFactory::createOne(['Animal' => $animal]);
        $I->amLoggedInAs($user->object());
        $I->sendDelete("/api/animal_records/{$animalRecord->getId()}");
        $I->seeResponseCodeIs(httpCode::FORBIDDEN);
    }
}
