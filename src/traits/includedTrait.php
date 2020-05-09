<?php
namespace carlonicora\minimalism\services\jsonapi\traits;

use carlonicora\minimalism\services\jsonapi\resources\resourceObject;

trait includedTrait {
    /**
     * @param string $type
     * @param string $id
     * @param array $included
     * @return resourceObject|null
     */
    protected function getIncludedResourceObject(string $type, string $id, array $included) : ?resourceObject{
        $response = null;

        foreach ($included as $resourceObject){
            if ($resourceObject['type'] === $type && $resourceObject['id'] === $id){
                $response = new resourceObject($resourceObject, $included);
            }
        }

        return $response;
    }

    /**
     * @param resourceObject $data
     * @param array $included
     */
    private function buildIncluded(resourceObject $data, array &$included): void {
        if ($data->relationships !== null){
            foreach ($data->relationships as $relationshipType=>$relationships){

                /** @var resourceObject $resourceObject */
                foreach ($relationships->data->getResources() ?? [] as $resourceObject) {
                    if (null === $this->getIncludedResourceObject($resourceObject->type, $resourceObject->id, $included)){
                        $this->buildIncluded($resourceObject, $included);

                        $included[] = $resourceObject->toArray();
                    }
                }
            }
        }
    }
}