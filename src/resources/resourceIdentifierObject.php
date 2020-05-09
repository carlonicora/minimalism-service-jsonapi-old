<?php
namespace carlonicora\minimalism\services\jsonapi\resources;

use carlonicora\minimalism\services\jsonapi\abstracts\abstractDocumentTransform;
use carlonicora\minimalism\services\jsonapi\traits\metaTrait;

class resourceIdentifierObject extends abstractDocumentTransform {
    use metaTrait;

    /** @var string  */
    public string $type;

    /** @var string|null */
    public ?string $id=null;

    /**
     * resourceIdentifierObject constructor.
     * @param string $type
     * @param string|null $id
     * @param array|null $meta
     */
    public function __construct(string $type, ?string $id=null, array $meta=null) {
        $this->type = $type;

        if ($id !== null) {
            $this->id = $id;
        }

        if ($meta !== null){
            $this->meta = $meta;
        }
    }

    /**
     * @return array
     */
    public function toArray() : array{
        $response = [
            'type' => $this->type,
            'id' => $this->id
        ];

        if (!empty($this->meta)) {
            $response['meta'] = $this->meta;
        }

        return $response;
    }
}