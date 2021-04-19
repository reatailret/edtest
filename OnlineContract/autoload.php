<?php
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $sources = array(__DIR__ . "/../$class.php");
    foreach ($sources as $source) {

        if (file_exists($source)) {
            require_once $source;
        }
    }
});
