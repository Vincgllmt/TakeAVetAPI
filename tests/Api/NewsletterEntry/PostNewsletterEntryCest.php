<?php

declare(strict_types=1);

namespace App\Tests\Api\NewsletterEntry;

use App\Entity\NewsletterEntry;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PostNewsletterEntryCest
{
    public function createNewsletterEntry(ApiTester $I): void
    {
        $I->sendPost('/api/newsletter', [
            'email' => 'monemail@gmail.com',
        ]);

        $I->canSeeResponseCodeIs(HttpCode::CREATED);
        $I->canSeeResponseIsJson();
        $I->seeResponseIsAnEntity(NewsletterEntry::class, '/api/newsletter_entries/1');
    }

    public function cantCreateNewsletterOnWrongEmail(ApiTester $I): void
    {
        $I->sendPost('/api/newsletter', [
            'email' => 'abcd',
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->canSeeResponseIsJson();
    }
}
