<?php
/**
 * @file include/db.php
 * @author Hakase (contact@hakase.kr)
 */
    namespace OsuTournament;
    /**
     * Osu Tournament for PHP
     * In production.
     */

    class DB {
        public $mysql;
        public function __construct($ip, $dbid, $dbpw, $dbnm)
        {
            $this->mysql = new mysqli($ip, $dbid, $dbpw, $dbnm);
        }
        public function Query($query)
        {
            // ...
        }
        public function Insert($query)
        {
            // ...
        }
    }
