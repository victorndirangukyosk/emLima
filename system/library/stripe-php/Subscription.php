<?php

namespace Stripe;

/**
 * Class Subscription.
 */
class Subscription extends ApiResource
{
    /**
     * These constants are possible representations of the status field.
     *
     * @see https://stripe.com/docs/api#subscription_object-status
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_CANCELED = 'canceled';
    const STATUS_PAST_DUE = 'past_due';
    const STATUS_TRIALING = 'trialing';
    const STATUS_UNPAID = 'unpaid';

    /**
     * @param string            $id   the ID of the subscription to retrieve
     * @param array|string|null $opts
     *
     * @return Subscription
     */
    public static function retrieve($id, $opts = null)
    {
        return self::_retrieve($id, $opts);
    }

    /**
     * @param array|null        $params
     * @param array|string|null $opts
     *
     * @return Collection of Subscriptions
     */
    public static function all($params = null, $opts = null)
    {
        return self::_all($params, $opts);
    }

    /**
     * @param array|null        $params
     * @param array|string|null $opts
     *
     * @return Subscription the created subscription
     */
    public static function create($params = null, $opts = null)
    {
        return self::_create($params, $opts);
    }

    /**
     * @param string            $id      the ID of the subscription to retrieve
     * @param array|null        $params
     * @param array|string|null $options
     *
     * @return Subscription the updated subscription
     */
    public static function update($id, $params = null, $options = null)
    {
        return self::_update($id, $params, $options);
    }

    /**
     * @param array|null $params
     *
     * @return Subscription the deleted subscription
     */
    public function cancel($params = null, $opts = null)
    {
        return $this->_delete($params, $opts);
    }

    /**
     * @param array|string|null $opts
     *
     * @return Subscription the saved subscription
     */
    public function save($opts = null)
    {
        return $this->_save($opts);
    }

    /**
     * @return Subscription the updated subscription
     */
    public function deleteDiscount()
    {
        $url = $this->instanceUrl().'/discount';
        list($response, $opts) = $this->_request('delete', $url);
        $this->refreshFrom(['discount' => null], $opts, true);
    }
}
