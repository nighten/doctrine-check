<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embedded;

#[
    ORM\Entity,
]
class EntityWithEmbeddedWithNullErrors
{
    #[ORM\Id, ORM\Column(type: 'integer', nullable: false)]
    private int $integer;

    #[Embedded(class: Embeddable1Class::class, columnPrefix: 'prefix.')]
    private ?Embeddable1Class $embedded1;

    #[Embedded(class: Embeddable4ClassWithNullError::class)]
    private Embeddable4ClassWithNullError $embedded2;
}
