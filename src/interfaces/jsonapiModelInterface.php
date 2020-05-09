<?php
namespace carlonicora\minimalism\services\jsonapi\interfaces;

use carlonicora\minimalism\services\jsonapi\jsonApiDocument;

interface jsonapiModelInterface {
    public const PARAMETER_TYPE_JSONAPI = 'validateJsonapiParameter';

    /**
     * @param $parameter
     * @return jsonApiDocument
     */
    public function validateJsonapiParameter($parameter) : jsonApiDocument;
}