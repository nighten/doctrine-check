<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Entity\Related;

use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity,
]
class EntityRelated2
{
    #[ORM\Id, ORM\Column(type: 'integer', nullable: false)]
    private int $integer;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $string;
}
