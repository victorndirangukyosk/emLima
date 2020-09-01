<?php

namespace Stripe;

/**
 * Class BankAccount.
 */
class BankAccount extends ExternalAccount
{
    /**
     * @param array|null        $params
     * @param array|string|null $options
     *
     * @return BankAccount the verified bank account
     */
    public function verify($params = null, $options = null)
    {
        $url = $this->instanceUrl().'/verify';
        list($response, $opts) = $this->_request('post', $url, $params, $options);
        $this->refreshFrom($response, $opts);

        return $this;
    }
}
