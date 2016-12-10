<?php
    require_once 'include.php';
    $Check = new OsuTournament\Check('ctb');

    echo $Check->CheckUser('Angelsim');
    echo '<br>';
    echo $Check->CheckPlayCount();
