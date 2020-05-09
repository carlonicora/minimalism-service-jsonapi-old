<?php
namespace carlonicora\minimalism\services\jsonapi\abstracts;

use carlonicora\minimalism\services\jsonapi\interfaces\transformationInterface;
use JsonException;

abstract class abstractDocumentTransform implements transformationInterface {
    /**
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * @return string
     * @throws JsonException
     */
    public function toJson(): string {
        $response = $this->toArray();

        return json_encode($response, JSON_THROW_ON_ERROR, 512);
    }
}