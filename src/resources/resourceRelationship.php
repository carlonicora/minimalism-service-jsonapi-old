<?php
namespace carlonicora\minimalism\services\jsonapi\resources;

use carlonicora\minimalism\services\jsonapi\abstracts\abstractDocumentTransform;
use carlonicora\minimalism\services\jsonapi\traits\linksTrait;
use carlonicora\minimalism\services\jsonapi\traits\metaTrait;

class resourceRelationship extends abstractDocumentTransform {
    use linksTrait;
    use metaTrait;

    /** @var resourceLinkage  */
    public resourceLinkage $data;

    /**
     * resourceRelationship constructor.
     */
    public function __construct() {
        $this->data = new resourceLinkage();
    }

    /**
     * @param resourceObject $object
     */
    public function addResourceObject(resourceObject $object): void {
        $this->data->addResourceObject($object);
    }

    /**
     * @return array
     */
    public function toArray() : array {
        $response = [
            'data' => $this->data->toArray()
        ];

        if (!empty($this->links)){
            $response['links'] = $this->links;
        }

        if (!empty($this->meta)) {
            $response['meta'] = $this->meta;
        }

        return $response;
    }
}