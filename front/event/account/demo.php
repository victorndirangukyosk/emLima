<?php

class EventAccountDemo extends Event
{
    public function preCustomerAddAddress($data)
    {
        $xyz = $data;
    }
}
