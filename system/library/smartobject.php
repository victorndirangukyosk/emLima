<?php

class SmartObject
{
    public function get($property, $default = null)
    {
        if (isset($this->$property)) {
            return $this->$property;
        }

        return $default;
    }

    public function getProperties($public = true)
    {
        $vars = get_object_vars($this);

        if ($public) {
            foreach ($vars as $key => $value) {
                if ('_' == substr($key, 0, 1)) {
                    unset($vars[$key]);
                }
            }
        }

        return $vars;
    }

    public function has($property)
    {
        return isset($this->$property);
    }

    public function set($property, $value = null)
    {
        $previous = isset($this->$property) ? $this->$property : null;

        $this->$property = $value;

        return $previous;
    }

    public function setProperties($properties)
    {
        if (is_array($properties) || is_object($properties)) {
            foreach ((array) $properties as $k => $v) {
                // Use the set function which might be overridden.
                $this->set($k, $v);
            }

            return true;
        }

        return false;
    }
}
