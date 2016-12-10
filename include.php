<?php
    require_once 'include/db.php';
    require_once 'include/webparser.php';
    require_once 'include/check.php';

    $Check = new OsuTournament\Check();
    /**
     * In production DB.
     */
    // $DB = new OsuTournament\DB('127.0.0.1', 'example', 'example', 'example');
