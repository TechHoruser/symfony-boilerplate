<?php

namespace App\Infrastructure\Security;

use App\Infrastructure\Persistence\Doctrine\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider implements PayloadAwareUserProviderInterface
{
    private array $cache = [];

    public function __construct(
        private UserRepository $userRepository,
        private RequestStack $requestStack,
    ) {
    }

    public function loadUserByUsernameAndPayload(string $username, array $payload)
    {
        if (isset($this->cache[$username])) {
            return $this->cache[$username];
        }

        if (!isset($payload['clientId'])) {
            // Fetch from where ever you want, for example through the requestStack
            $payload['clientId'] = $this->requestStack->getCurrentRequest()->attributes->get('clientId');
        }

        $user = $this->userRepository->getByEmail($username);

        if (null === $user) {
            throw new \Exception();
        }

        return $this->cache[$username] = User::createFromUser($user);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        if (isset($this->cache[$identifier])) {
            return $this->cache[$identifier];
        }

        $user = $this->userRepository->getByEmail($identifier);

        if (null === $user) {
            throw new \Exception();
        }

        return $this->cache[$identifier] = User::createFromUser($user);
    }
}
