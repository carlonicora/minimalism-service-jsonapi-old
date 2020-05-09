<?php
namespace carlonicora\minimalism\services\jsonapi\resources;

use carlonicora\minimalism\services\jsonapi\abstracts\abstractDocumentTransform;
use carlonicora\minimalism\services\jsonapi\traits\metaTrait;

class errorObject extends abstractDocumentTransform {
    use metaTrait;

    /** @var string  */
    public string $status;

    /** @var int|null  */
    public ?int $id=null;

    /** @var string  */
    public ?string $code=null;

    /** @var string  */
    public ?string $title=null;

    /** @var string|null  */
    public ?string $detail=null;

    /**
     * errorObject constructor.
     * @param string $status
     * @param string $code
     * @param string|null $detail
     * @param int|null $id
     */
    public function __construct(string $status, string $code, ?string $detail=null, ?int $id=null) {
        $this->status = $status;
        $this->code = $code;
        $this->detail = $detail;
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function toArray(): array {
        $response = [
            'status' => $this->status
        ];

        if ($this->id !== null){
            $response['id'] = $this->id;
        }

        if ($this->code !== null){
            $response['code'] = $this->code;
        }

        if ($this->detail !== null){
            $response['detail'] = $this->detail;
        }

        if (!empty($this->meta)){
            $response['meta'] = $this->meta;
        }

        return $response;
    }
}