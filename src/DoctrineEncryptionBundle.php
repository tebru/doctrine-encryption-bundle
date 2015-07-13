<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\DoctrineEncryptionBundle;

use Doctrine\DBAL\Types\Type;
use Tebru\DoctrineEncryptionBundle\Type\Encrypted;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class DoctrineEncryptionBundle
 *
 * @author Nate Brunette <n@tebru.net>
 */
class DoctrineEncryptionBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // This seems odd, because it is. Symfony boots the bundle twice on cache priming
        // which would cause an exception to be thrown due to already having defined
        // the custom type.
        if (false === Type::hasType(Encrypted::NAME)) {
            Type::addType(Encrypted::NAME, 'Tebru\DoctrineEncryptionBundle\Type\Encrypted');
        }

        /** @var Encrypted $encryptedType */
        $encryptedType = Type::getType(Encrypted::NAME);
        $encryptedType->setEncrypter($this->container->get('aes_encrypter'));

        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $platform = $entityManager->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping(Encrypted::NAME, Encrypted::NAME);
        $platform->markDoctrineTypeCommented(Encrypted::NAME);
    }
}
