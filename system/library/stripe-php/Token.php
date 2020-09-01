<?php

namespace Stripe;

/**
 * Class Token.
 *
 * @property string $id
 * @property string $object
 * @property mixed  $bank_account
 * @property mixed  $card
 * @property mixed  $client_ip
 * @property int    $created
 * @property bool   $livemode
 * @property string $type
 * @property bool   $used
 */
class Token extends ApiResource
{
    /**
     * @param string            $id   the ID of the token to retrieve
     * @param array|string|null $opts
     *
     * @return Token
     */
    public static function retrieve($id, $opts = null)
    {
        return self::_retrieve($id, $opts);
    }

    /**
     * @param array|null        $params
     * @param array|string|null $opts
     *
     * @return Token the created token
     */
    public static function create($params = null, $opts = null)
    {
        return self::_create($params, $opts);
    }
}
