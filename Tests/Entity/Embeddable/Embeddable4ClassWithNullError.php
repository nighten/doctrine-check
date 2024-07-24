<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Entity\Embeddable;

use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping as ORM;

#[Embeddable]
class Embeddable4ClassWithNullError
{
    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $integer;
    #[ORM\Column(type: 'boolean', nullable: true)]
    private bool $boolean;
}
