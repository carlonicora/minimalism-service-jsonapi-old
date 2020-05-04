<?php
namespace carlonicora\minimalism\services\jsonapi\traits;

use carlonicora\minimalism\services\jsonapi\resources\resourceObject;

trait modelTrait {
    /**
     * @param $parameter
     * @return resourceObject
     */
    protected function validateJsonapiParameter($parameter) : resourceObject {
        return new resourceObject($parameter);
    }
}