<?php
declare(strict_types=1);

namespace GIS\Response;

use JsonSerializable;

/**
 * Class ExceptionResponse
 * @package GIS
 */
class ExceptionResponse implements JsonSerializable
{

    /** @var string */
    protected $message;

    /**
     * ExceptionResponse constructor.
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return json_encode([
            "error" => [
                "message" => $this->message
            ]
        ], JSON_PRETTY_PRINT);
    }

}