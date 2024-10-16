<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Entity\InheritanceMapping\SuperClass;

use Doctrine\ORM\Mapping as ORM;
use Nighten\DoctrineCheck\Tests\Entity\Related\EntityRelated;

#[
    ORM\MappedSuperclass,
]
class Superclass
{
    #[ORM\Column(type: 'integer')]
    protected int $mapped1;
    #[ORM\Column(type: 'string')]
    protected string $mapped2;


    #[ORM\ManyToOne(targetEntity: EntityRelated::class)]
    #[ORM\JoinColumn(name: 'related_id', referencedColumnName: 'id', nullable: true)]
    protected ?EntityRelated $related = null;
}
