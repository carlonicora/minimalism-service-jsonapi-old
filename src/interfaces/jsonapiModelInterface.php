<?php
namespace carlonicora\minimalism\services\jsonapi\interfaces;

use carlonicora\minimalism\services\jsonapi\resources\resourceObject;

interface jsonapiModelInterface {
    public const PARAMETER_TYPE_JSONAPI = 'validateJsonapiParameter';

    /**
     * @param $parameter
     * @return resourceObject
     */
    public function validateJsonapiParameter($parameter) : resourceObject;
}