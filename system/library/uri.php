<?php

use Joomla\Uri\Uri as JUri;

class Uri extends JUri
{
    public function __construct($uri = 'SERVER')
    {
        // Are we obtaining the Uri from the server?
        if ('SERVER' == $uri) {
            // Determine if the request was over SSL (HTTPS).
            if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && ('off' != strtolower($_SERVER['HTTPS']))) {
                $https = 's://';
            } else {
                $https = '://';
            }

            // Since we are assigning the Uri from the server variables, we first need
            // to determine if we are running on apache or IIS.  If PHP_SELF and REQUEST_URI
            // are present, we will assume we are running on apache.

            if (!empty($_SERVER['PHP_SELF']) && !empty($_SERVER['REQUEST_URI'])) {
                // To build the entire URI we need to prepend the protocol, and the http host
                // to the URI string.
                $theUri = 'http'.$https.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            } else {
                // Since we do not have REQUEST_URI to work with, we will assume we are
                // running on IIS and will therefore need to work some magic with the SCRIPT_NAME and
                // QUERY_STRING environment variables.

                // IIS uses the SCRIPT_NAME variable instead of a REQUEST_URI variable... thanks, MS
                $theUri = 'http'.$https.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];

                // If the query string exists append it to the URI string
                if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
                    $theUri .= '?'.$_SERVER['QUERY_STRING'];
                }
            }

            // Check for quotes in the URL to prevent injections through the Host header
            if ($theUri !== str_replace(["'", '"', '<', '>'], '', $theUri)) {
                throw new InvalidArgumentException('Invalid URI detected.');
            }
        } else {
            // We were given a Uri
            $theUri = $uri;
        }

        $this->parse($theUri);
    }
}
