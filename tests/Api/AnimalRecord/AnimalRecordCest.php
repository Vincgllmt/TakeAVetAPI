<?php

namespace App\Tests\Api\AnimalRecord;

use App\Tests\Support\ApiTester;

class AnimalRecordCest
{
    protected static function expectedPropertiesResultPost(): array
    {
        return [
            'id' => 'integer',
            'weight' => 'float',
            'height' => 'float',
            'updatedAt' => 'DateTimeInterface',
            'Animal' => 'Animal',
            'otherInfos' => 'string',
            'healthInfos' => 'string',
        ];
    }

    public function testCreateAnimalRecord(ApiTester $I): void
    {
    }
}
