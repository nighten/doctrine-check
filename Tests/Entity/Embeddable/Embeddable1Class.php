<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Entity\Embeddable;

use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping as ORM;

#[Embeddable]
class Embeddable1Class
{
    #[ORM\Column(type: 'string', nullable: false)]
    private string $string1;
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $string2;
}
