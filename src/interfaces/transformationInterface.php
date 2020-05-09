<?php
namespace carlonicora\minimalism\services\jsonapi\interfaces;

use JsonException;

interface transformationInterface {
    /**
     * @return string
     * @throws JsonException
     */
    public function toJson() : string;

    /**
     * @return array
     */
    public function toArray() : array;
}