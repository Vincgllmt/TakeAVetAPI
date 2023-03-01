<?php

namespace App\Tests\Api\Animal;

use App\Entity\Animal;
use App\Factory\AnimalFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class GetAnimalCest
{
    public static function exceptPropertiesResultGet(): array
    {
        return [
            'id' => 'integer',
            'name' => 'string',
            'note' => 'string',
            'specificRace' => 'string|null',
            'gender' => 'string',
            'birthday' => 'string',
            'imagePath' => 'string',
            'infarm' => 'boolean',
            'isGroups' => 'boolean',
            'records' => 'array',
            'type' => 'array',
            'owner' => 'string',
            'vaccines' => 'array'
        ];
    }
    public function getAllAnimals(ApiTester $I): void
    {
        AnimalFactory::createMany(20);

        $I->sendGet('/api/animals');

        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function getAllAnimalsOfUserById(ApiTester $I): void
    {

    }

    public function getOneAnimal(ApiTester $I): void
    {
        AnimalFactory::createOne();
    }
}
