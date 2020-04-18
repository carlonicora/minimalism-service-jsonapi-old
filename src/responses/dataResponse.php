<?php
namespace carlonicora\minimalism\services\jsonapi\responses;

use carlonicora\minimalism\services\jsonapi\interfaces\responseInterface;
use carlonicora\minimalism\services\jsonapi\abstracts\abstractResponseObject;
use carlonicora\minimalism\services\jsonapi\resources\errorObject;
use carlonicora\minimalism\services\jsonapi\resources\resourceObject;
use carlonicora\minimalism\services\jsonapi\resources\resourceRelationship;
use carlonicora\minimalism\services\jsonapi\traits\linksTrait;
use carlonicora\minimalism\services\jsonapi\traits\metaTrait;

class dataResponse extends abstractResponseObject implements responseInterface {
    use metaTrait;
    use linksTrait;

    /** @var resourceObject|null */
    public ?resourceObject $data=null;

    /** @var array|null  */
    public ?array $errors=null;

    /** @var array|null */
    public ?array $dataList=null;

    /** @var array|null  */
    public ?array $included=null;

    /**
     * responseObject constructor.
     * @param array $data
     */
    public function __construct(array $data=null) {
        if (isset($data)){
            $this->data = new resourceObject($data);
        }
    }

    /**
     * @param errorObject $error
     */
    public function addError(errorObject $error) : void {
        if ($this->errors === null){
            $this->errors = [];
        }

        $this->errors[] = $error;
        $this->status = $error->getStatus();
    }

    /**
     * @param resourceObject $data
     */
    public function addData(resourceObject $data) : void {
        if ($this->dataList === null) {
            if ($this->data !== null) {
                $this->dataList = [];
                $this->dataList[] = $this->data;
                $this->data = null;
            } else {
                $this->data = $data;
            }
        }

        if ($this->dataList !== null){
            $this->dataList[] = $data;
        }
    }

    /**
     * @return array
     */
    public function toArray(): array {
        $response = [];

        if ($this->data !== null) {
            $response['data'] = $this->data->toArray();
            $this->buildIncluded($this->data);
        } else if ($this->dataList !== null) {
            $response['data'] = [];

            /** @var resourceObject $data */
            foreach ($this->dataList ?? [] as $data){
                $response['data'][] = $data->toArray();
                $this->buildIncluded($data);
            }
        } elseif ($this->errors !== null) {
            $response['errors'] = [];
            /** @var errorObject $error */
            foreach ($this->errors ?? [] as $error) {
                $response['errors'][] = $error->toArray();

                if ($this->status === self::HTTP_STATUS_200){
                    $this->status = $error->status;
                }
            }
        } else {
            $response['data'] = [];
        }

        if (!empty($this->links)){
            $response['links'] = $this->links;
        }

        if (!empty($this->meta)){
            $response['meta'] = $this->meta;
        }

        if ($this->included !== null){
            $response['included'] = [];

            /** @var resourceRelationship $resource */
            foreach ($this->included as $resource){
                $response['included'][] = $resource->toArray();
            }
        }

        return $response;
    }

    /**
     * @param resourceObject $data
     */
    public function addIncluded(resourceObject $data): void {
        if ($this->included === null){
            $this->included = [];
        }

        $this->included[] = $data;
    }

    /**
     * @param resourceObject $data
     */
    private function buildIncluded(resourceObject $data): void {
        if ($data->relationships !== null){
            foreach ($data->relationships as $relationshipType=>$relationships){
                /** @var resourceRelationship $relationship */
                foreach ($relationships as $relationship){
                    if ($this->included === null){
                        $this->included = [];
                    }

                    $addIncluded = true;
                    foreach ($this->included as $included) {
                        if ($included->id === $relationship->data->id && $included->type === $relationship->data->type){
                            $addIncluded = false;
                            break;
                        }
                    }

                    if ($addIncluded){
                        $this->included[] = $relationship->data;
                    }

                    $this->buildIncluded($relationship->data);
                }
            }
        }
    }

    /**
     * @return string
     */
    public function toJson() : string {
        if (!in_array($this->status, [self::HTTP_STATUS_204, self::HTTP_STATUS_205], true)) {
            $response = $this->toArray();

            return json_encode($response, JSON_THROW_ON_ERROR, 512);
        }

        return '';
    }

}