<?php

namespace Stripe;

/**
 * Class Order.
 */
class Order extends ApiResource
{
    /**
     * @param string            $id   the ID of the Order to retrieve
     * @param array|string|null $opts
     *
     * @return Order
     */
    public static function retrieve($id, $opts = null)
    {
        return self::_retrieve($id, $opts);
    }

    /**
     * @param array|null        $params
     * @param array|string|null $opts
     *
     * @return Order the created Order
     */
    public static function create($params = null, $opts = null)
    {
        return self::_create($params, $opts);
    }

    /**
     * @param string            $id      the ID of the order to update
     * @param array|null        $params
     * @param array|string|null $options
     *
     * @return Order the updated order
     */
    public static function update($id, $params = null, $options = null)
    {
        return self::_update($id, $params, $options);
    }

    /**
     * @param array|string|null $opts
     *
     * @return Order the saved Order
     */
    public function save($opts = null)
    {
        return $this->_save($opts);
    }

    /**
     * @param array|null        $params
     * @param array|string|null $opts
     *
     * @return Collection of Orders
     */
    public static function all($params = null, $opts = null)
    {
        return self::_all($params, $opts);
    }

    /**
     * @return Order the paid order
     */
    public function pay($params = null, $opts = null)
    {
        $url = $this->instanceUrl().'/pay';
        list($response, $opts) = $this->_request('post', $url, $params, $opts);
        $this->refreshFrom($response, $opts);

        return $this;
    }

    /**
     * @return OrderReturn the newly created return
     */
    public function returnOrder($params = null, $opts = null)
    {
        $url = $this->instanceUrl().'/returns';
        list($response, $opts) = $this->_request('post', $url, $params, $opts);

        return Util\Util::convertToStripeObject($response, $opts);
    }
}
