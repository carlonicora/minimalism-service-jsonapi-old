<?php
namespace carlonicora\minimalism\services\jsonapi\resources;

use carlonicora\minimalism\services\jsonapi\abstracts\abstractDocumentTransform;

class resourceLinkage extends abstractDocumentTransform {
    /** @var resourceObject|null  */
    public ?resourceObject $resourceObject = null;

    /** @var resourceObject[]|null  */
    public ?array $resourceObjects = null;

    /**
     * @param resourceObject $object
     */
    public function addResourceObject(resourceObject $object) : void {
        if ($this->resourceObjects !== null){
            $this->resourceObjects[] = $object;
        } elseif ($this->resourceObject === null){
            $this->resourceObject = $object;
        } else {
            $this->resourceObjects = [];
            $this->resourceObjects[] = $this->resourceObject;
            $this->resourceObject = null;
            $this->resourceObjects[] = $object;
        }
    }

    /**
     * @return resourceObject[]
     */
    public function getResources() : array {
        $response = [];

        if ($this->resourceObject !== null){
            $response[] = $this->resourceObject;
        } elseif ($this->resourceObjects !== null){
            $response = $this->resourceObjects;
        }

        return $response;
    }

    /**
     * @return array
     */
    public function toArray(): array {
        $response = [];
        if ($this->resourceObject !== null){
            $response = $this->resourceObject->toArray(true);
        } elseif ($this->resourceObjects !== null) {
            $response = [];
            foreach ($this->resourceObjects as $resourceObject) {
                $response[] = $resourceObject->toArray(true);
            }
        }

        return $response;
    }
}