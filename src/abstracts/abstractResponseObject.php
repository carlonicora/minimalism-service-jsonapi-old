<?php
namespace carlonicora\minimalism\services\jsonapi\abstracts;

abstract class abstractResponseObject {
    /** @var string  */
    public string $status = self::HTTP_STATUS_200;

    /** @var string  */
    public const HTTP_STATUS_200='200';

    /** @var string  */
    public const HTTP_STATUS_201='201';

    /** @var string  */
    public const HTTP_STATUS_204='204';

    /** @var string  */
    public const HTTP_STATUS_205='205';

    /** @var string  */
    public const HTTP_STATUS_304='304';

    /** @var string  */
    public const HTTP_STATUS_400='400';

    /** @var string  */
    public const HTTP_STATUS_401='401';

    /** @var string  */
    public const HTTP_STATUS_403='403';

    /** @var string  */
    public const HTTP_STATUS_404='404';

    /** @var string  */
    public const HTTP_STATUS_405='405';

    /** @var string  */
    public const HTTP_STATUS_406='406';

    /** @var string  */
    public const HTTP_STATUS_409='409';

    /** @var string  */
    public const HTTP_STATUS_410='410';

    /** @var string  */
    public const HTTP_STATUS_411='411';

    /** @var string  */
    public const HTTP_STATUS_412='412';

    /** @var string  */
    public const HTTP_STATUS_415='415';

    /** @var string  */
    public const HTTP_STATUS_422='422';

    /** @var string  */
    public const HTTP_STATUS_428='428';

    /** @var string  */
    public const HTTP_STATUS_429='429';

    /** @var string  */
    public const HTTP_STATUS_500='500';

    /** @var string  */
    public const HTTP_STATUS_501='510';

    /** @var string  */
    public const HTTP_STATUS_502='520';

    /** @var string  */
    public const HTTP_STATUS_503='503';

    /** @var string  */
    public const HTTP_STATUS_504='504';

    /**
     * @return string
     */
    public function getStatus(): string {
        return $this->status;
    }

    /**
     * @return string
     */
    public function generateText() : string {
        switch ($this->status) {
            case self::HTTP_STATUS_201:
                return 'Created';
                break;
            case self::HTTP_STATUS_204:
                return 'No Content';
                break;
            case self::HTTP_STATUS_304:
                return 'Not Modified';
                break;
            case self::HTTP_STATUS_400:
                return 'Bad Request';
                break;
            case self::HTTP_STATUS_401:
                return 'Unauthorized';
                break;
            case self::HTTP_STATUS_403:
                return 'Forbidden';
                break;
            case self::HTTP_STATUS_404:
                return 'Not Found';
                break;
            case self::HTTP_STATUS_405:
                return 'Method Not Allowed';
                break;
            case self::HTTP_STATUS_406:
                return 'Not Acceptable';
                break;
            case self::HTTP_STATUS_409:
                return 'Conflict';
                break;
            case self::HTTP_STATUS_410:
                return 'Gone';
                break;
            case self::HTTP_STATUS_411:
                return 'Length Required';
                break;
            case self::HTTP_STATUS_412:
                return 'Precondition Failed';
                break;
            case self::HTTP_STATUS_415:
                return 'Unsupported Media Type';
                break;
            case self::HTTP_STATUS_422:
                return 'Unprocessable Entity';
                break;
            case self::HTTP_STATUS_428:
                return 'Precondition Required';
                break;
            case self::HTTP_STATUS_429:
                return 'Too Many Requests';
                break;
            case self::HTTP_STATUS_500:
                return 'Internal Server Error';
                break;
            case self::HTTP_STATUS_501:
                return 'Not Implemented';
                break;
            case self::HTTP_STATUS_502:
                return 'Bad Gateway';
                break;
            case self::HTTP_STATUS_503:
                return 'Service Unavailable';
                break;
            case self::HTTP_STATUS_504:
                return 'Gateway Timeout';
                break;
            case self::HTTP_STATUS_200:
            default:
                return 'OK';
                break;
        }
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
    abstract public function toJson() : string;

    /**
     * @return array
     */
    abstract public function toArray(): array;
}