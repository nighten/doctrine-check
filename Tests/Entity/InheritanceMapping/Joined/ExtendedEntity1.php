<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Entity\InheritanceMapping\Joined;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ExtendedEntity1 extends BaseEntity
{
    #[ORM\Column(type: 'integer')]
    protected int $exField1;
    #[ORM\Column(type: 'string')]
    protected string $exField2;
}
