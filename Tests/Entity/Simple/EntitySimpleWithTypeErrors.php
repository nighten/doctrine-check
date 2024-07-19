<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Entity\Simple;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity,
]
class EntitySimpleWithTypeErrors
{
    #[ORM\Id, ORM\Column(type: 'integer', nullable: false)]
    private string $integer;

    #[ORM\Id, ORM\Column(type: 'smallint', nullable: false)]
    private string $smallint;

    #[ORM\Column(type: 'bigint', nullable: false)]
    private int $bigint;

    #[ORM\Column(type: 'string', nullable: false)]
    private int $string;

    #[ORM\Column(type: 'text', nullable: false)]
    private int $text;

    #[ORM\Column(type: 'decimal', nullable: false)]
    private int $decimal;

    #[ORM\Column(type: 'float', nullable: false)]
    private string $float;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private int $boolean;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private DateTimeImmutable $datetime;

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private DateTime $datetime_immutable;

    #[ORM\Column(type: 'date', nullable: false)]
    private DateTimeImmutable $date;

    #[ORM\Column(type: 'date_immutable', nullable: false)]
    private DateTime $date_immutable;

    #[ORM\Column(type: 'guid', nullable: false)]
    private int $guid;

    #[ORM\Column(type: 'simple_array', nullable: false)]
    private int $simple_array;

    #[ORM\Column(type: 'json', nullable: false)]
    private int $json;

    #[ORM\Column(type: 'json_object', nullable: false)]
    private int $json_object;

    #[ORM\Column(type: 'serialized', nullable: false)]
    private int $serialized;
}
