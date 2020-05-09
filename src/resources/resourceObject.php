<?php
namespace carlonicora\minimalism\services\jsonapi\resources;

use carlonicora\minimalism\services\jsonapi\traits\includedTrait;
use carlonicora\minimalism\services\jsonapi\traits\linksTrait;

class resourceObject extends resourceIdentifierObject {
    use linksTrait;
    use includedTrait;

    /** @var array|null */
    public ?array $attributes=null;

    /** @var array|resourceRelationship[]|null  */
    public ?array $relationships=null;

    /**
     * resourceObject constructor.
     * @param array $data
     * @param array|null $included
     */
    public function __construct(array $data, array $included = null) {
        parent::__construct($data['type'], $data['id'] ?? null);

        if (array_key_exists('attributes', $data)) {
            $this->attributes = $data['attributes'];
        }

        if (array_key_exists('meta', $data)) {
            $this->meta = $data['meta'];
        }

        if (array_key_exists('links', $data)) {
            $this->links = $data['links'];
        }

        if (array_key_exists('relationships', $data) && $included !== null) {
            /**
             * @var string $relationshipTypeName
             * @var resourceRelationship $relationship
             */
            foreach ($data['relationships'] as $relationshipTypeName=>$relationship){
                $resourceRelationship = new resourceRelationship();
                if (array_key_exists('links', $relationship)) {
                    $resourceRelationship->addLinks($relationship['links']);
                }
                if (array_key_exists('meta', $relationship)) {
                    $resourceRelationship->addMetas($relationship['meta']);
                }

                $this->relationships[$relationshipTypeName] = $resourceRelationship;

                if (array_key_exists('data', $relationship)) {
                    if (array_key_exists('type', $relationship['data'])){
                        if (($object = $this->getIncludedResourceObject($relationship['data']['type'], $relationship['data']['id'] ?? null, $included)) !== null){
                            $this->addResourceLink($object, $relationshipTypeName);
                        }
                    } else {
                        foreach ($relationship['data'] as $singleRelationship){
                            if (($object = $this->getIncludedResourceObject($singleRelationship['type'], $singleRelationship['id'] ?? null, $included)) !== null){
                                $this->addResourceLink($object, $relationshipTypeName);
                            }
                        }
                    }
                }
            }

        }
    }

    /**
     * @param string $relationshipName
     * @return resourceRelationship|null
     */
    public function getRelationship(string $relationshipName): ?resourceRelationship {
        if ($this->relationships !== null && array_key_exists($relationshipName, $this->relationships)){
            return $this->relationships[$relationshipName];
        }

        return null;
    }

    /**
     * @param resourceObject $object
     * @param string|null $relationshipName
     */
    public function addResourceLink(resourceObject $object, string $relationshipName=null) : void{
        if ($this->relationships === null){
            $this->relationships = [];
        }

        if (!array_key_exists($relationshipName ?? $object->type, $this->relationships)){
            $this->relationships[$relationshipName ?? $object->type] = new resourceRelationship();
        }

        $this->relationships[$relationshipName ?? $object->type]->addResourceObject($object);
    }

    /**
     * @param bool $limitToIdentifierObject
     * @return array
     */
    public function toArray(bool $limitToIdentifierObject=false) : array {
        $response = parent::toArray();

        if (!$limitToIdentifierObject) {
            if ($this->attributes !== null) {
                $response['attributes'] = $this->attributes;
            }

            if ($this->hasLinks()) {
                $response['links'] = $this->links;
            }

            if ($this->relationships !== null) {
                $response['relationships'] = [];

                foreach ($this->relationships as $type => $relationships) {
                    $response['relationships'][$type] = $relationships->toArray();
                }
            }
        }

        return $response;
    }
}