<?php

namespace App\Tests\Integration\UI\Http\Rest;

use App\Domain\Client\Entity\Client;
use App\Domain\User\Entity\User;
use App\Tests\Integration\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;

class AuthenticationTest extends AbstractControllerIntegrationTest
{
    public function testLoginSuccessfully()
    {
        // GIVEN
        $method = self::POST;
        $path = '/login_check';
        $user = $this->saveAndReturnUser();

        // WHEN
        $this->sendRequestWithBody(
            $method,
            $path,
            [
                'username' => $user->getEmail(),
                'password' => $this->fakerFactory->getUserPassword(),
            ]
        );
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('token', $responseData);
    }

    private function saveAndReturnUser(): User
    {
        $user = $this->fakerFactory->newUser();
        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }
}
