<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embedded;

#[
    ORM\Entity,
]
class EntityWithEmbeddedWithTypeErrors
{
    #[ORM\Id, ORM\Column(type: 'integer', nullable: false)]
    private int $integer;

    #[Embedded(class: Embeddable3ClassWithTypeError::class, columnPrefix: 'prefix.')]
    private Embeddable3ClassWithTypeError $embedded3;

    #[Embedded(class: Embeddable2Class::class)]
    private Embeddable1Class $embedded2;
}
