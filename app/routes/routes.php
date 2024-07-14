<?php

use system\core\Router;
use system\Loader;

Router::setRoute('default', '', 'defaultController');
Router::setRoute('default/([a-z]+)', '', 'defaultController/${1}');
Router::setRoute('404_error', '', 'errorController');

Router::setRoute('api/uac/([a-z]+)', 'api/uac/', '${1}');
Router::setRoute('api/([a-z]+)', 'api/', '${1}');
Router::setRoute("ap", "", "app");
Router::setRoute(
    "ivan",
    '',
    function () {
        echo "test";
        Loader::view("home");
    }
);
