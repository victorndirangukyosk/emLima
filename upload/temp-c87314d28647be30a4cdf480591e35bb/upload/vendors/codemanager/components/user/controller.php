<?php

    /*
    *  Copyright (c) Codiad & Kent Safranski (codiad.com), distributed
    *  as-is and without warranty under the MIT License. See
    *  [root]/license.txt for more. This information must remain intact.
    */

    require_once '../../common.php';
    require_once 'class.user.php';

    if (!isset($_GET['action'])) {
        die(formatJSEND('error', 'Missing parameter'));
    }

    //////////////////////////////////////////////////////////////////
    // Verify Session or Key
    //////////////////////////////////////////////////////////////////

   //  if($_GET['action']!='authenticate'){ checkSession(); }

    $User = new User();

    //////////////////////////////////////////////////////////////////
    // Authenticate
    //////////////////////////////////////////////////////////////////

    if ('authenticate' == $_GET['action']) {
        if (!isset($_POST['username']) || !isset($_POST['password'])) {
            die(formatJSEND('error', 'Missing username or password'));
        }

        $User->username = $_POST['username'];
        $User->password = $_POST['password'];

        // check if the asked languages exist and is registered in languages/code.php
        require_once '../../languages/code.php';
        if (isset($languages[$_POST['language']])) {
            $User->lang = $_POST['language'];
        } else {
            $User->lang = 'en';
        }

        // theme
        $User->theme = $_POST['theme'];

        $User->Authenticate();
    }

    /* OpenCart LOGIN */
        if ('opencart' == $_GET['action']) {
            if (isset($_SESSION['codemanager']) && (true == $_SESSION['codemanager'])) {
                $User->username = 'admin';
                $User->password = 'p@$$w04dp@$$w04d';

                // check if the asked languages exist and is registered in languages/code.php
                require_once '../../languages/code.php';
                if (isset($languages[$_POST['language']])) {
                    $User->lang = $_POST['language'];
                } else {
                    $User->lang = 'en';
                }

                // theme
                $User->theme = $_POST['theme'];

                $User->Authenticate();
            }
        }
    /* OpenCART LOGIN */

    //////////////////////////////////////////////////////////////////
    // Logout
    //////////////////////////////////////////////////////////////////

    if ('logout' == $_GET['action']) {
        session_unset();
        session_destroy();
        session_start();
    }

    //////////////////////////////////////////////////////////////////
    // Create User
    //////////////////////////////////////////////////////////////////

    if ('create' == $_GET['action']) {
        if (checkAccess()) {
            if (!isset($_POST['username']) || !isset($_POST['password'])) {
                die(formatJSEND('error', 'Missing username or password'));
            }

            $User->username = User::CleanUsername($_POST['username']);
            $User->password = $_POST['password'];
            $User->Create();
        }
    }

    //////////////////////////////////////////////////////////////////
    // Delete User
    //////////////////////////////////////////////////////////////////

    if ('delete' == $_GET['action']) {
        if (checkAccess()) {
            if (!isset($_GET['username'])) {
                die(formatJSEND('error', 'Missing username'));
            }

            $User->username = $_GET['username'];
            $User->Delete();
        }
    }

    //////////////////////////////////////////////////////////////////
    // Set Project Access
    //////////////////////////////////////////////////////////////////

    if ('project_access' == $_GET['action']) {
        if (checkAccess()) {
            if (!isset($_GET['username'])) {
                die(formatJSEND('error', 'Missing username'));
            }
            $User->username = $_GET['username'];

            //No project selected
            if (isset($_POST['projects'])) {
                $User->projects = $_POST['projects'];
            } else {
                $User->projects = [];
            }
            $User->Project_Access();
        }
    }

    //////////////////////////////////////////////////////////////////
    // Change Password
    //////////////////////////////////////////////////////////////////

    if ('password' == $_GET['action']) {
        if (!isset($_POST['username']) || !isset($_POST['password'])) {
            die(formatJSEND('error', 'Missing username or password'));
        }

        if (checkAccess() || $_POST['username'] == $_SESSION['user']) {
            $User->username = $_POST['username'];
            $User->password = $_POST['password'];
            $User->Password();
        }
    }

    //////////////////////////////////////////////////////////////////
    // Change Project
    //////////////////////////////////////////////////////////////////

    if ('project' == $_GET['action']) {
        if (!isset($_GET['project'])) {
            die(formatJSEND('error', 'Missing project'));
        }

        $User->username = $_SESSION['user'];
        $User->project = $_GET['project'];
        $User->Project();
    }

    //////////////////////////////////////////////////////////////////
    // Verify User Account
    //////////////////////////////////////////////////////////////////

    if ('verify' == $_GET['action']) {
        $User->username = $_SESSION['user'];
        $User->Verify();
    }
