<?php
/**
 * @file include.php
 * @author Hakase (contact@hakase.kr)
 */
    spl_autoload_register(
        function($classname)
        {
            $file = __DIR__.'/class/'.strtolower(str_replace("\\", "/", $classname)).'.php';
            if (file_exists($file))
                require_once(__DIR__.'/class/'.strtolower(str_replace("\\", "/", $classname)).'.php');
        }
    );
