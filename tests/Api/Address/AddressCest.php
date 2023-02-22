<?php

namespace App\Tests\Api\Address;

use App\Tests\Support\ApiTester;

class AddressCest
{
    protected static function expectedPropertiesResultPost(): array
    {
        return [
            'id' => 'integer',
            'name' => 'string',
            'ad' => 'string',
            'pc' => 'string',
            'city' => 'string',
            'client' => 'Client',
        ];
    }

    public function testCreateAdress(ApiTester $I): void
    {
    }
}
