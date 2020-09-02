<?php

namespace Stripe\Util;

use Stripe\StripeObject;

abstract class Util
{
    private static $isMbstringAvailable = null;

    /**
     * Whether the provided array (or other) is a list rather than a dictionary.
     *
     * @param array|mixed $array
     *
     * @return bool true if the given object is a list
     */
    public static function isList($array)
    {
        if (!is_array($array)) {
            return false;
        }

        // TODO: generally incorrect, but it's correct given Stripe's response
        foreach (array_keys($array) as $k) {
            if (!is_numeric($k)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Recursively converts the PHP Stripe object to an array.
     *
     * @param array $values the PHP Stripe object to convert
     *
     * @return array
     */
    public static function convertStripeObjectToArray($values)
    {
        $results = [];
        foreach ($values as $k => $v) {
            // FIXME: this is an encapsulation violation
            if ('_' == $k[0]) {
                continue;
            }
            if ($v instanceof StripeObject) {
                $results[$k] = $v->__toArray(true);
            } elseif (is_array($v)) {
                $results[$k] = self::convertStripeObjectToArray($v);
            } else {
                $results[$k] = $v;
            }
        }

        return $results;
    }

    /**
     * Converts a response from the Stripe API to the corresponding PHP object.
     *
     * @param array $resp the response from the Stripe API
     * @param array $opts
     *
     * @return StripeObject|array
     */
    public static function convertToStripeObject($resp, $opts)
    {
        $types = [
            'account' => 'Stripe\\Account',
            'alipay_account' => 'Stripe\\AlipayAccount',
            'bank_account' => 'Stripe\\BankAccount',
            'balance_transaction' => 'Stripe\\BalanceTransaction',
            'card' => 'Stripe\\Card',
            'charge' => 'Stripe\\Charge',
            'country_spec' => 'Stripe\\CountrySpec',
            'coupon' => 'Stripe\\Coupon',
            'customer' => 'Stripe\\Customer',
            'dispute' => 'Stripe\\Dispute',
            'list' => 'Stripe\\Collection',
            'invoice' => 'Stripe\\Invoice',
            'invoiceitem' => 'Stripe\\InvoiceItem',
            'event' => 'Stripe\\Event',
            'file' => 'Stripe\\FileUpload',
            'token' => 'Stripe\\Token',
            'transfer' => 'Stripe\\Transfer',
            'order' => 'Stripe\\Order',
            'order_return' => 'Stripe\\OrderReturn',
            'plan' => 'Stripe\\Plan',
            'product' => 'Stripe\\Product',
            'recipient' => 'Stripe\\Recipient',
            'refund' => 'Stripe\\Refund',
            'sku' => 'Stripe\\SKU',
            'source' => 'Stripe\\Source',
            'subscription' => 'Stripe\\Subscription',
            'three_d_secure' => 'Stripe\\ThreeDSecure',
            'fee_refund' => 'Stripe\\ApplicationFeeRefund',
            'bitcoin_receiver' => 'Stripe\\BitcoinReceiver',
            'bitcoin_transaction' => 'Stripe\\BitcoinTransaction',
        ];
        if (self::isList($resp)) {
            $mapped = [];
            foreach ($resp as $i) {
                array_push($mapped, self::convertToStripeObject($i, $opts));
            }

            return $mapped;
        } elseif (is_array($resp)) {
            if (isset($resp['object']) && is_string($resp['object']) && isset($types[$resp['object']])) {
                $class = $types[$resp['object']];
            } else {
                $class = 'Stripe\\StripeObject';
            }

            return $class::constructFrom($resp, $opts);
        } else {
            return $resp;
        }
    }

    /**
     * @param string|mixed $value a string to UTF8-encode
     *
     * @return string|mixed the UTF8-encoded string, or the object passed in if
     *                      it wasn't a string
     */
    public static function utf8($value)
    {
        if (null === self::$isMbstringAvailable) {
            self::$isMbstringAvailable = function_exists('mb_detect_encoding');

            if (!self::$isMbstringAvailable) {
                trigger_error('It looks like the mbstring extension is not enabled. '.
                    'UTF-8 strings will not properly be encoded. Ask your system '.
                    'administrator to enable the mbstring extension, or write to '.
                    'support@stripe.com if you have any questions.', E_USER_WARNING);
            }
        }

        if (is_string($value) && self::$isMbstringAvailable && 'UTF-8' != mb_detect_encoding($value, 'UTF-8', true)) {
            return utf8_encode($value);
        } else {
            return $value;
        }
    }
}
