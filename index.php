<?php

    session_start();

    require_once 'application/config.php';
    require_once 'application/class/log.class.php';

    if( debug ) {
        ini_set( 'display_errors', 1 );
    }

    require_once 'application/class/database.class.php';
    require_once 'application/class/core.class.php';
    require_once 'application/class/security.class.php';
    require_once 'application/class/user.class.php';
    require_once 'application/class/folder.class.php';
    require_once 'application/class/gmail.class.php';

    require_once 'application/core/view.php';
    require_once 'application/core/controller.php';
    require_once 'application/core/route.php';

    use core\route;
    route::start();
