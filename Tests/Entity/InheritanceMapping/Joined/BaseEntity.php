<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Entity\InheritanceMapping\Joined;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'base' => BaseEntity::class,
    'type1' => ExtendedEntity1::class,
    'type2' => ExtendedEntity2::class,
])]
class BaseEntity
{
    #[ORM\Id, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private int $field1;
    #[ORM\Column(type: 'string')]
    protected string $field2;
}
