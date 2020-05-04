<?php
namespace carlonicora\minimalism\services\jsonapi\resources;

use carlonicora\minimalism\services\jsonapi\traits\metaTrait;

class resourceIdentifierObject {
    use metaTrait;

    /** @var string  */
    public string $type;

    /** @var string */
    public string $id;

    /**
     * resourceIdentifierObject constructor.
     * @param string $type
     * @param string|null $id
     */
    public function __construct(string $type, ?string $id=null) {
        $this->type = $type;
        if ($id !== null) {
            $this->id = $id;
        }
    }

    /**
     * @param bool $limitToIdentifierObject
     * @return array
     */
    public function toArray(bool $limitToIdentifierObject=false) : array{
        $response = [
            'type' => $this->type,
            'id' => $this->id
        ];

        if (!$limitToIdentifierObject) {
            if (!empty($this->meta)) {
                $response['meta'] = $this->meta;
            }
        }

        return $response;
    }
}