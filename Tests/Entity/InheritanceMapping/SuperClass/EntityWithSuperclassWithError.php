<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Entity\InheritanceMapping\SuperClass;

use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity,
]
class EntityWithSuperclassWithError extends Superclass
{
    #[ORM\Id, ORM\Column(type: 'integer')]
    private ?int $id = null;
    #[ORM\Column(type: 'integer')]
    private string $name;
}
