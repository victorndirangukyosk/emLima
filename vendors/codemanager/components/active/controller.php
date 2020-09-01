<?php

    /*
    *  Copyright (c) Codiad & Kent Safranski (codiad.com), distributed
    *  as-is and without warranty under the MIT License. See
    *  [root]/license.txt for more. This information must remain intact.
    */

    require_once '../../common.php';
    require_once 'class.active.php';

    $Active = new Active();

    //////////////////////////////////////////////////////////////////
    // Verify Session or Key
    //////////////////////////////////////////////////////////////////

    checkSession();

    //////////////////////////////////////////////////////////////////
    // Get user's active files
    //////////////////////////////////////////////////////////////////

    if ('list' == $_GET['action']) {
        $Active->username = $_SESSION['user'];
        $Active->ListActive();
    }

    //////////////////////////////////////////////////////////////////
    // Add active record
    //////////////////////////////////////////////////////////////////

    if ('add' == $_GET['action']) {
        $Active->username = $_SESSION['user'];
        $Active->path = $_GET['path'];
        $Active->Add();
    }

    //////////////////////////////////////////////////////////////////
    // Rename
    //////////////////////////////////////////////////////////////////

    if ('rename' == $_GET['action']) {
        $Active->username = $_SESSION['user'];
        $Active->path = $_GET['old_path'];
        $Active->new_path = $_GET['new_path'];
        $Active->Rename();
    }

    //////////////////////////////////////////////////////////////////
    // Check if file is active
    //////////////////////////////////////////////////////////////////

    if ('check' == $_GET['action']) {
        $Active->username = $_SESSION['user'];
        $Active->path = $_GET['path'];
        $Active->Check();
    }

    //////////////////////////////////////////////////////////////////
    // Remove active record
    //////////////////////////////////////////////////////////////////

    if ('remove' == $_GET['action']) {
        $Active->username = $_SESSION['user'];
        $Active->path = $_GET['path'];
        $Active->Remove();
    }

    //////////////////////////////////////////////////////////////////
    // Remove all active record
    //////////////////////////////////////////////////////////////////

    if ('removeall' == $_GET['action']) {
        $Active->username = $_SESSION['user'];
        $Active->RemoveAll();
    }

    //////////////////////////////////////////////////////////////////
    // Mark file as focused
    //////////////////////////////////////////////////////////////////

    if ('focused' == $_GET['action']) {
        $Active->username = $_SESSION['user'];
        $Active->path = $_GET['path'];
        $Active->MarkFileAsFocused();
    }
