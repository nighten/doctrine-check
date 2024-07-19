<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Entity\Simple;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity,
]
class EntitySimpleTypeWithPHPDoc
{
    /**
     * @var int
     */
    #[ORM\Id, ORM\Column(type: 'integer', nullable: false)]
    private $integer;

    /**
     * @var int
     */
    #[ORM\Id, ORM\Column(type: 'smallint', nullable: false)]
    private $smallint;

    /**
     * @var string
     */
    #[ORM\Column(type: 'bigint', nullable: false)]
    private $bigint;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private $string;

    /**
     * @var string
     */
    #[ORM\Column(type: 'text', nullable: false)]
    private $text;

    /**
     * @var string
     */
    #[ORM\Column(type: 'decimal', nullable: false)]
    private $decimal;

    /**
     * @var float
     */
    #[ORM\Column(type: 'float', nullable: false)]
    private $float;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean', nullable: false)]
    private $boolean;

    /**
     * @var DateTime
     */
    #[ORM\Column(type: 'datetime', nullable: false)]
    private $datetime;

    /**
     * @var DateTimeImmutable
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private $datetime_immutable;

    /**
     * @var DateTime
     */
    #[ORM\Column(type: 'date', nullable: false)]
    private $date;

    /**
     * @var DateTimeImmutable
     */
    #[ORM\Column(type: 'date_immutable', nullable: false)]
    private $date_immutable;

    /**
     * @var string
     */
    #[ORM\Column(type: 'guid', nullable: false)]
    private $guid;

    /**
     * @var array
     */
    #[ORM\Column(type: 'simple_array', nullable: false)]
    private $simple_array;

    /**
     * @var array
     */
    #[ORM\Column(type: 'json', nullable: false)]
    private $json;

    /**
     * @var array
     */
    #[ORM\Column(type: 'json_object', nullable: false)]
    private $json_object;

    /**
     * @var array
     */
    #[ORM\Column(type: 'serialized', nullable: false)]
    private $serialized;
}
