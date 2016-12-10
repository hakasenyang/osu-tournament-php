<?php
    require_once 'include.php';
    $Check = new OsuTournament\Check();

    function GetDataTest()
    {
        global $Check;
        echo $Check->RealID . ' (' . $Check->OsuID . ')';
        $occu = $Check->CheckOccupation();

        if ($Check->CheckOccupation() === true)
            echo '<br>You are applied!';
        elseif (empty($occu))
            echo '<br>Please change occupation to ' . $Check->GetOccupation();
        else
            echo '<br>Please change occupation to ' . $Check->GetOccupation() . '<br>Your Occupation : ' . $occu;
        echo '<br>';

        if (empty($Check->CheckPerformance()))
            echo 'Error<br>';
        echo 'Play Count : ' . $Check->CheckPlayCount();
        echo '<br>';
        echo 'Performance : ' . $Check->CheckPerformance();
        echo '<br>';
        echo 'Ranking : ' . $Check->CheckRank();
        echo '<br><br>';
    }
    $Check->CheckUser(2558286, 0);
    GetDataTest();

    $Check->CheckUser('angelsIm', 0);
    GetDataTest();

    $Check->CheckUser('peppy', 0);
    GetDataTest();

    $Check->CheckUser('banchobot', 0);
    GetDataTest();