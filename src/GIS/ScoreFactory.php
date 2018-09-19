<?php
declare(strict_types=1);

namespace GIS;

/**
 * Class ScoreFactory
 * @package GIS
 */
class ScoreFactory
{

    /** @var string */
    protected $className;

    /**
     * ScoreFactory constructor.
     * @param string $className
     */
    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * @return ScoreInterface
     */
    public function productionLine(): ScoreInterface
    {
        return new $this->className;
    }

}