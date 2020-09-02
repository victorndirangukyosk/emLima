<?php

namespace GingerPayments\Payment\Common;

use Assert\Assertion as Guard;

trait ChoiceBasedValueObject
{
    use StringBasedValueObject;

    /**
     * @return array
     */
    abstract public function possibleValues();

    /**
     * @param string $value
     */
    private function __construct($value)
    {
        Guard::choice($value, $this->possibleValues());

        $this->value = $value;
    }
}
