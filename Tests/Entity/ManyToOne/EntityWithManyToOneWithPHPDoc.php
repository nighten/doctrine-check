<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Entity\ManyToOne;

use Doctrine\ORM\Mapping as ORM;
use Nighten\DoctrineCheck\Tests\Entity\Related\EntityRelated;

#[
    ORM\Entity,
]
class EntityWithManyToOneWithPHPDoc
{
    #[ORM\Id, ORM\Column(type: 'integer', nullable: false)]
    private int $integer;

    /**
     * @var EntityRelated|null
     */
    #[
        ORM\ManyToOne(targetEntity: EntityRelated::class),
        ORM\JoinColumn(nullable: true),
    ]
    private $related;

    /**
     * @var \Nighten\DoctrineCheck\Tests\Entity\Related\EntityRelated|null
     */
    #[
        ORM\ManyToOne(targetEntity: EntityRelated::class),
        ORM\JoinColumn(nullable: true),
    ]
    private $related2;
}
