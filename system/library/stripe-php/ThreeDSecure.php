<?php

namespace Stripe;

class ThreeDSecure extends ApiResource
{
    /**
     * @return string the endpoint URL for the given class
     */
    public static function classUrl()
    {
        return '/v1/3d_secure';
    }

    /**
     * @param array|null        $params
     * @param array|string|null $opts
     *
     * @return ThreeDSecure the created 3D Secure object
     */
    public static function create($params = null, $opts = null)
    {
        return self::_create($params, $opts);
    }
}
