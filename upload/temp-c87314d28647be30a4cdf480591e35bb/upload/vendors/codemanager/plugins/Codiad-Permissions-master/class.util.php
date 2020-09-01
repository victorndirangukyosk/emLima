<?php
/*
 * Copyright (c) Codiad & Andr3as, distributed
 * as-is and without warranty under the MIT License.
 * See [root]/license.md for more information. This information must remain intact.
 */
    class util
    {
        public static function getWorkspacePath($path)
        {
            //Security check
            if (!Common::checkPath($path)) {
                die('{"status":"error","message":"Invalid path"}');
            }
            if (0 === strpos($path, '/')) {
                //Unix absolute path
                return $path;
            }
            if (false !== strpos($path, ':/')) {
                //Windows absolute path
                return $path;
            }
            if (false !== strpos($path, ':\\')) {
                //Windows absolute path
                return $path;
            }

            return '../../workspace/'.$path;
        }
    }
