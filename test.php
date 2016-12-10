<?php
    require_once 'include.php';
    $Check = new OsuTournament\Check();

    $Check->CheckUser(2558286, 0);
    echo $Check->RealID . ' (' . $Check->OsuID . ')';
    $occu = $Check->CheckOccupation();

    if ($Check->CheckOccupation() === true)
        echo '<br>You are applied!';
    else
        echo '<br>Please change occupation to ' . $Check->GetOccupation() . '<br>Your Occupation : ' . $occu;
    echo '<br>';

    if (empty($Check->CheckPerformance()))
        echo 'Error<br>';
    echo 'Performance : ' . $Check->CheckPerformance();
    echo '<br>';
    echo 'Ranking : ' . $Check->CheckRank();
    echo '<br><br>';

    $Check->CheckUser('Angelsim', 0);
    echo $Check->RealID . ' (' . $Check->OsuID . ')';
    $occu = $Check->CheckOccupation();

    if ($Check->CheckOccupation() === true)
        echo '<br>You are applied!';
    else
        echo '<br>Please change occupation to ' . $Check->GetOccupation() . '<br>Your Occupation : ' . $occu;
    echo '<br>';
    echo 'Performance : ' . $Check->CheckPerformance();