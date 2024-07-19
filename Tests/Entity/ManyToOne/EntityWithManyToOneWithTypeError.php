<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Entity\ManyToOne;

use Doctrine\ORM\Mapping as ORM;
use Nighten\DoctrineCheck\Tests\Entity\Related\EntityRelated;
use Nighten\DoctrineCheck\Tests\Entity\Related\EntityRelated2;

#[
    ORM\Entity,
]
class EntityWithManyToOneWithTypeError
{
    #[ORM\Id, ORM\Column(type: 'integer', nullable: false)]
    private int $integer;

    #[
        ORM\ManyToOne(targetEntity: EntityRelated::class),
        ORM\JoinColumn(nullable: true),
    ]
    private ?EntityRelated2 $related;
}
