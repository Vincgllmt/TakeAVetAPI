<?php

namespace App\Tests\Api\Appointment;

use App\Tests\Support\ApiTester;

class AppointmentCest
{
    protected static function expectedPropertiesResultPost(): array
    {
        return [
            'id' => 'integer',
            'note' => 'string',
            'isValidated' => 'bool',
            'isUrgent' => 'bool',
            'isCompleted' => 'bool',
            'receipt' => 'Receipt',
            'type' => 'TypeAppointment',
            'client' => 'Client',
            'veto' => 'Veto',
            'animal' => 'Animal',
            'address' => 'Address',
            'startHour' => 'DateTimeInterface',
            'endHour' => 'DateTimeInterface'
        ];
    }

    public function testCreateAppointment(ApiTester $I): void
    {
    }
}
