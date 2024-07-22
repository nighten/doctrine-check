<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Entity\Embeddable;

use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping as ORM;

#[Embeddable]
class Embeddable3ClassWithTypeError
{
    #[ORM\Column(type: 'string', nullable: false)]
    private int $integer;
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?bool $boolean;
}
