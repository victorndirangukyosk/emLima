<?php

class EventCatalogDemo extends Event
{
    public function postAdminManufacturerEdit($manufacturer_id)
    {
        $a = 'Dummy text '.$manufacturer_id;
    }
}
