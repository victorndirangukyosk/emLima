<?php
/*
 * Copyright (c) Codiad & Andr3as, distributed
 * as-is and without warranty under the MIT License.
 * See http://opensource.org/licenses/MIT for more information.
 * This information must remain intact.
 */
    error_reporting(0);

    require_once '../../common.php';
    checkSession();

    switch ($_GET['action']) {
        case 'save':
            if (isset($_POST['settings'])) {
                saveJSON('beautify.settings.php', json_decode($_POST['settings']), 'config');
                echo '{"status":"success","message":"Settings saved"}';
            } else {
                echo '{"status":"error","message":"Missing parameter"}';
            }
            break;

        case 'load':
            if (file_exists(DATA.'/config/beautify.settings.php')) {
                echo json_encode(getJSON('beautify.settings.php', 'config'));
            } else {
                echo file_get_contents('default.settings.json');
            }
            break;

        case 'saveContent':
            if (isset($_GET['path']) && isset($_POST['content'])) {
                if (false === file_put_contents(getWorkspacePath($_GET['path']), $_POST['content'])) {
                    echo '{"status":"error","message":"Failed to save content"}';
                } else {
                    echo '{"status":"success","message":"Content saved"}';
                }
            } else {
                echo '{"status":"error","message":"Missing parameter"}';
            }
            break;

        case 'getContent':
            if (isset($_GET['path'])) {
                echo file_get_contents(getWorkspacePath($_GET['path']));
            } else {
                echo '{"status":"error","message":"Missing parameter"}';
            }
            break;

        default:
            echo '{"status":"error","message":"No Type"}';
            break;
    }

    function getWorkspacePath($path)
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

        return WORKSPACE.'/'.$path;
    }
