<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Entity\InheritanceMapping\SingleTable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ExtendedEntity1WithError extends BaseEntityWithError
{
    #[ORM\Column(type: 'integer')]
    private int $exField1;
    #[ORM\Column(type: 'integer')]
    protected string $exField2;
}
