<?php


namespace App\Services\Auth;


/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2021/12/13 17:17
 */
class JwtModel
{
    /**
     * @var int $sub id
     */
    public $sub;
    /**
     * @var int $exp expire timestamp
     */
    public $exp;
    /**
     * @var int $iat build timestamp
     */
    public $iat;
    /**
     * @var string $iss site url
     */
    public $iss;
}
