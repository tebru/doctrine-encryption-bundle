<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\DoctrineEncryptionBundle\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Tebru\AesEncryption\AesEncrypter;
use UnexpectedValueException;

/**
 * Class Encrypted
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Encrypted extends Type
{
    /**
     * Name of type
     */
    const NAME = 'encrypted';

    /**
     * @var AesEncrypter
     */
     private $encrypter;

    /**
     * Take the encrypted string and decrypt
     *
     * @param string $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $this->getEncrypter()->decrypt($value);
    }

    /**
     * Encrypt data
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $this->getEncrypter()->encrypt($value);
    }

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getBlobTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * Get the encrypter
     *
     * @throws UnexpectedValueException if encrypter library is not set
     * @return AesEncrypter
     */
    private function getEncrypter()
    {
        if (null === $this->encrypter) {
            throw new UnexpectedValueException('Requires an encrypter to be provided before being used. See Encrypter::setEncrypter');
        }

        return $this->encrypter;
    }

    /**
     * Sets the encrypter
     *
     * @param AesEncrypter $encrypter
     *
     * @return self
     */
    public function setEncrypter(AesEncrypter $encrypter)
    {
        $this->encrypter = $encrypter;

        return $this;
    }
}
