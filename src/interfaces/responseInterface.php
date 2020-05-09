<?php
namespace carlonicora\minimalism\services\jsonapi\interfaces;

interface responseInterface {
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
    public function getStatus(): string;

    /**
     * @return string
     */
    public function generateText() : string;

    /**
     * @return string
     */
    public static function generateProtocol() : string;
}