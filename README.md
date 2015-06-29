# Doctrine Encryption Bundle
Utilizes [tebru/aes-encryption](https://github.com/tebru/aes-encryption) and adds a Doctrine type that automatically
encrypts and decrypts values in the database.

## Installation

    composer require tebru/doctrine-encryption-bundle
    
Add to AppKernel

    new Tebru\DoctrineEncryptionBundle\DoctrineEncryptionBundle(),
    
## Usage
Set the column annotation type to `encrypted` on your entity property

    @ORM\Column(type="encrypted")
