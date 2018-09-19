<?php
declare(strict_types=1);

namespace GIS\Response;

use JsonSerializable;

/**
 * Class JsonResponse
 * @package GIS
 */
class Response implements JsonSerializable
{

    /** @var array */
    protected $response;

    /**
     * JsonResponse constructor.
     * @param array $response
     */
    public function __construct(array $response = [])
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return json_encode($this->response, JSON_PRETTY_PRINT);
    }

}