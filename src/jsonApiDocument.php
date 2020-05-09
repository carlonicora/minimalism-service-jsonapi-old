<?php
namespace carlonicora\minimalism\services\jsonapi;

use carlonicora\minimalism\services\jsonapi\interfaces\responseInterface;
use carlonicora\minimalism\services\jsonapi\interfaces\transformationInterface;
use carlonicora\minimalism\services\jsonapi\resources\errorObject;
use carlonicora\minimalism\services\jsonapi\resources\resourceObject;
use carlonicora\minimalism\services\jsonapi\traits\includedTrait;
use carlonicora\minimalism\services\jsonapi\traits\linksTrait;
use carlonicora\minimalism\services\jsonapi\traits\metaTrait;
use JsonException;

class jsonApiDocument implements responseInterface, transformationInterface {
    use metaTrait;
    use linksTrait;
    use includedTrait;

    /** @var string  */
    public string $status = responseInterface::HTTP_STATUS_200;

    /** @var resourceObject|null */
    public ?resourceObject $data=null;

    /** @var array|null  */
    public ?array $errors=null;

    /** @var array|null */
    public ?array $dataList=null;

    /** @var bool  */
    public bool $forceList=false;

    /**
     * responseObject constructor.
     * @param array $data
     */
    public function __construct(array $data=null) {
        if ($data !== null) {
            $included = null;

            if (array_key_exists('included', $data)) {
                $included = $data['included'];
            }

            if (array_key_exists('type', $data['data'])) {
                $this->data = new resourceObject($data['data'], $included);
            } else {
                $this->dataList = [];

                foreach ($data['data'] as $objectData) {
                    $this->dataList[] = new resourceObject($objectData, $included);
                }
            }
        }
    }

    /**
     * @param errorObject $error
     */
    public function addError(errorObject $error) : void {
        if ($this->errors === null){
            $this->errors = [];

            $this->status = $error->status;
        }

        $this->errors[] = $error;
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
     * @param array $dataList
     */
    public function addDataList(array $dataList) : void {
        if ($this->dataList === null){
            $this->dataList = [];
        }

        if ($this->data !== null){
            $this->dataList = array_merge($this->dataList, [$this->data]);
            $this->data = null;
        }

        $this->dataList = array_merge($this->dataList, $dataList);
    }

    /**
     *
     */
    public function forceList(): void {
        $this->forceList = true;
    }

    /**
     * @return array
     */
    public function toArray(): array {
        $response = [];

        $included = [];

        if ($this->errors !== null) {
            $response = [
                'errors' => []
            ];

            /** @var errorObject $error */
            foreach ($this->errors ?? [] as $error) {
                $response['errors'][] = $error->toArray();
            }

            if (!empty($this->meta)) {
                $response['meta'] = $this->meta;
            }
        } else {
            if ($this->data !== null) {
                if ($this->forceList) {
                    $response['data'] = [];
                    $response['data'][] = $this->data->toArray();
                } else {
                    $response['data'] = $this->data->toArray();
                }
                $this->buildIncluded($this->data, $included);
            } elseif ($this->dataList !== null) {
                $response['data'] = [];

                /** @var resourceObject $data */
                foreach ($this->dataList ?? [] as $data) {
                    $response['data'][] = $data->toArray();
                    $this->buildIncluded($data, $included);
                }
            } else {
                $response['data'] = [];
            }

            if (!empty($this->links)) {
                $response['links'] = $this->links;
            }

            if (!empty($this->meta)) {
                $response['meta'] = $this->meta;
            }

            if ($included !== []) {
                $response['included'] = $included;
            }
        }

        return $response;
    }

    /**
     * @return string
     * @throws JsonException
     */
    public function toJson() : string {
        if (in_array($this->status, [self::HTTP_STATUS_204, self::HTTP_STATUS_205], true)) {
            return '';
        }

        $response = $this->toArray();
        return json_encode($response, JSON_THROW_ON_ERROR, 512);
    }

    /**
     * @return string
     */
    public function getStatus(): string {
        return $this->status;
    }

    /**
     * @return string
     */
    public static function generateProtocol() : string {
        return ($_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1');
    }

    /**
     * @return string
     */
    public function generateText() : string {
        switch ($this->status) {
            case responseInterface::HTTP_STATUS_201:
                return 'Created';
                break;
            case responseInterface::HTTP_STATUS_204:
                return 'No Content';
                break;
            case responseInterface::HTTP_STATUS_304:
                return 'Not Modified';
                break;
            case responseInterface::HTTP_STATUS_400:
                return 'Bad Request';
                break;
            case responseInterface::HTTP_STATUS_401:
                return 'Unauthorized';
                break;
            case responseInterface::HTTP_STATUS_403:
                return 'Forbidden';
                break;
            case responseInterface::HTTP_STATUS_404:
                return 'Not Found';
                break;
            case responseInterface::HTTP_STATUS_405:
                return 'Method Not Allowed';
                break;
            case responseInterface::HTTP_STATUS_406:
                return 'Not Acceptable';
                break;
            case responseInterface::HTTP_STATUS_409:
                return 'Conflict';
                break;
            case responseInterface::HTTP_STATUS_410:
                return 'Gone';
                break;
            case responseInterface::HTTP_STATUS_411:
                return 'Length Required';
                break;
            case responseInterface::HTTP_STATUS_412:
                return 'Precondition Failed';
                break;
            case responseInterface::HTTP_STATUS_415:
                return 'Unsupported Media Type';
                break;
            case responseInterface::HTTP_STATUS_422:
                return 'Unprocessable Entity';
                break;
            case responseInterface::HTTP_STATUS_428:
                return 'Precondition Required';
                break;
            case responseInterface::HTTP_STATUS_429:
                return 'Too Many Requests';
                break;
            case responseInterface::HTTP_STATUS_500:
                return 'Internal Server Error';
                break;
            case responseInterface::HTTP_STATUS_501:
                return 'Not Implemented';
                break;
            case responseInterface::HTTP_STATUS_502:
                return 'Bad Gateway';
                break;
            case responseInterface::HTTP_STATUS_503:
                return 'Service Unavailable';
                break;
            case responseInterface::HTTP_STATUS_504:
                return 'Gateway Timeout';
                break;
            case responseInterface::HTTP_STATUS_200:
            default:
                return 'OK';
                break;
        }
    }
}