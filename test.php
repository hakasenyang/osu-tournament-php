<?php
    require_once 'include.php';
    $Check = new OsuTournament\Check();
    $Check->SelectMode(0);

    echo $Check->CheckUser('peppy');
    if ($Check->CheckOccupation() === true)
        echo '<br>You are applied!';
    else
        echo '<br>Please change occupation to ' . $Check->CheckOccupation();
    echo '<br>';
    echo 'Performance : ' . $Check->CheckPerformance();
    echo '<br>';
    echo 'Ranking : ' . $Check->CheckRank();

    $Check->ResetObject();

    echo $Check->CheckUser('ExampleToNotUser');
    echo '<br>';
    echo 'Performance : ' . $Check->CheckPerformance();