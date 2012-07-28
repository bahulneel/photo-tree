<?php
namespace PhotoTree\Bundle\AlbumBundle\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PhotoTree\Bundle\AlbumBundle\Domain\Gender\AbstractGender;
use PhotoTree\Bundle\AlbumBundle\Domain\Gender\Male;
use PhotoTree\Bundle\AlbumBundle\Domain\Gender\Female;

class GenderType extends Type
{
    const GENDER = 'gender'; // modify to match your type name

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'char(1)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        switch ($value) {
            case 'M':
                return new Male;
            case 'F':
                return new Female;
            default:
                return null;
        }
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!$value instanceof AbstractGender) {
            return null;
        }
        return $value->getValue();
    }

    public function getName()
    {
        return self::GENDER;
    }
}
