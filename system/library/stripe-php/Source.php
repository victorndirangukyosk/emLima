<?php

namespace Stripe;

/**
 * Class Source.
 */
class Source extends ApiResource
{
    /**
     * @param string            $id   the ID of the Source to retrieve
     * @param array|string|null $opts
     *
     * @return Source
     */
    public static function retrieve($id, $opts = null)
    {
        return self::_retrieve($id, $opts);
    }

    /**
     * @param array|null        $params
     * @param array|string|null $opts
     *
     * @return Collection of Sources
     */
    public static function all($params = null, $opts = null)
    {
        return self::_all($params, $opts);
    }

    /**
     * @param array|null        $params
     * @param array|string|null $opts
     *
     * @return Source the created Source
     */
    public static function create($params = null, $opts = null)
    {
        return self::_create($params, $opts);
    }
}
