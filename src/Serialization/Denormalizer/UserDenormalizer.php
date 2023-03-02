<?php

declare(strict_types=1);

namespace App\Serialization\Denormalizer;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

class UserDenormalizer implements ContextAwareDenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public const ALREADY_CALLED = 'USER_DENORMALIZER_ALREADY_CALLED';
    private UserPasswordHasherInterface $passwordHasher;
    private Security $security;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, Security $security)
    {
        $this->passwordHasher = $userPasswordHasher;
        $this->security = $security;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return !isset($context[self::ALREADY_CALLED])
            && is_a($type, User::class, true)
            && isset($data['password']);
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $user = $this->security->getUser();
        if (null !== $user) {
            $data['password'] = $this->passwordHasher->hashPassword($this->security->getUser(), $data['password']);

            return $this->denormalizer->denormalize($data, $type, $format, $context + [self::ALREADY_CALLED => true]);
        }

        return $this->denormalizer->denormalize($data, $type, $format, $context + [self::ALREADY_CALLED => true]);
    }
}
