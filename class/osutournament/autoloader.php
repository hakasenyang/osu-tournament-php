<?php
/**
 * @file class/osutournament/autoloader.php
 * @author Hakase (contact@hakase.kr)
 */
    namespace OsuTournament;
    class Autoloader
    {
        /**
         * register Add autoloader class
         */
        public function register()
        {
            spl_autoload_register(array($this, 'load'));
        }
        /**
         * load Load class php file
         * @param  string $class Class name
         * @return bool          True / False
         */
        public function load($class)
        {
            $basedir = dirname(dirname(__DIR__));
            $file = $basedir.'/class/'.strtolower(str_replace("\\", "/", $class)).'.php';
            if ($this->requireFile($basedir.'/class/'.strtolower(str_replace("\\", "/", $class)).'.php'))
                return true;
            else
                return false;
        }
        /**
         * requireFile Require php file
         * @param  string $file File name
         * @return bool         True / False
         */
        protected function requireFile($file)
        {
            if (file_exists($file))
            {
                require $file;
                return true;
            }
            return false;
        }
    }
