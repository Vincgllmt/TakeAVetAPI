<?php

namespace App\Tests\Api\Vaccine;

use App\Factory\AnimalFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PostVaccineCest
{
    public function testVaccinePost(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $animal = AnimalFactory::createOne();
        $I->sendPost('/api/vaccines', [
            'name' => 'test',
            'next' => '2023-03-09T16:24:03.030Z',
            'last' => '2023-03-09T16:24:03.030Z',
            'animal' => "/api/animals/{$animal->getId()}",
        ]);
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
    }
}
