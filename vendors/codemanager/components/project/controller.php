<?php

    /*
    *  Copyright (c) Codiad & Kent Safranski (codiad.com), distributed
    *  as-is and without warranty under the MIT License. See
    *  [root]/license.txt for more. This information must remain intact.
    */

    require_once '../../common.php';
    require_once 'class.project.php';

    //////////////////////////////////////////////////////////////////
    // Verify Session or Key
    //////////////////////////////////////////////////////////////////

    checkSession();

    $Project = new Project();

    //////////////////////////////////////////////////////////////////
    // Get Current Project
    //////////////////////////////////////////////////////////////////

    $no_return = false;
    if (isset($_GET['no_return'])) {
        $no_return = true;
    }

    if ('get_current' == $_GET['action']) {
        if (!isset($_SESSION['project'])) {
            // Load default/first project
            if ($no_return) {
                $Project->no_return = true;
            }
            $Project->GetFirst();
        } else {
            // Load current
            $Project->path = $_SESSION['project'];
            $project_name = $Project->GetName();
            if (!$no_return) {
                echo formatJSEND('success', ['name' => $project_name, 'path' => $_SESSION['project']]);
            }
        }
    }

    //////////////////////////////////////////////////////////////////
    // Open Project
    //////////////////////////////////////////////////////////////////

    if ('open' == $_GET['action']) {
        if (!checkPath($_GET['path'])) {
            die(formatJSEND('error', 'No Access'));
        }
        $Project->path = $_GET['path'];
        $Project->Open();
    }

    //////////////////////////////////////////////////////////////////
    // Create Project
    //////////////////////////////////////////////////////////////////

    if ('create' == $_GET['action']) {
        if (checkAccess()) {
            $Project->name = $_GET['project_name'];
            if ('' != $_GET['project_path']) {
                $Project->path = $_GET['project_path'];
            } else {
                $Project->path = $_GET['project_name'];
            }
            // Git Clone?
            if (!empty($_GET['git_repo'])) {
                $Project->gitrepo = $_GET['git_repo'];
                $Project->gitbranch = $_GET['git_branch'];
            }
            $Project->Create();
        }
    }

    //////////////////////////////////////////////////////////////////
    // Rename Project
    //////////////////////////////////////////////////////////////////

    if ('rename' == $_GET['action']) {
        if (!checkPath($_GET['project_path'])) {
            die(formatJSEND('error', 'No Access'));
        }
        $Project->path = $_GET['project_path'];
        $Project->Rename();
    }

    //////////////////////////////////////////////////////////////////
    // Delete Project
    //////////////////////////////////////////////////////////////////

    if ('delete' == $_GET['action']) {
        if (checkAccess()) {
            $Project->path = $_GET['project_path'];
            $Project->Delete();
        }
    }

    //////////////////////////////////////////////////////////////////
    // Return Current
    //////////////////////////////////////////////////////////////////

    if ('current' == $_GET['action']) {
        if (isset($_SESSION['project'])) {
            echo formatJSEND('success', $_SESSION['project']);
        } else {
            echo formatJSEND('error', 'No Project Returned');
        }
    }
