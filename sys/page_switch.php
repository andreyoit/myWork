<?php
include 'https://mywork-andreaem-dev.c9.io/sys/config.php';

if (! isset($_GET['page']))
    {
       include_once $app_url . 'pages/dash.php';

    } else {    
        $page = $_GET['page'];  
        switch($page)
        {
            case 'login':
                include( $app_url . 'auth/login.php');
                break;      
            case 'dashboard':
                include($app_url . 'pages/dash.php');
                break;  
            case 'todo':
                include($app_url . '/pages/todo.php');
                break;  
            case 'profile':
                include($app_url . 'pages/profile.php');
                break;  
            case 'events':
                include('./events.php');
                break;  
            case 'contact':
                include('./contact.php');
                break;
            default:
                include('pages/dash.php');
                break;
        }
    }
?>