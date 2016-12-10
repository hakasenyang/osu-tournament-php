<?php
    require_once 'include/db.php';
    require_once 'include/webparser.php';

    $Parser = new OsuTournament\Parser();
    $DB = new OsuTournament\DB('127.0.0.1', 'example', 'example', 'example');