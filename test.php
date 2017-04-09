<?php
    require_once 'include.php';
    $Check = new OsuTournament\Check();

    function GetDataTest()
    {
        global $Check;
        if(empty($Check->RealID) || empty($Check->OsuID))
        {
            echo 'User not found<br><br>';
            return false;
        }
        echo $Check->RealID . ' (' . $Check->OsuID . ')';

        if ($Check->Occupation_Set === $Check->Occupation)
            echo '<br>You are applied!';
        elseif (empty($Check->Occupation))
            echo '<br>Please change occupation to ' . $Check->Occupation_Set . '<br>Your Occupation is blank.';
        else
            echo '<br>Please change occupation to ' . $Check->Occupation_Set . '<br>Your Occupation : ' . $Check->Occupation;
        echo '<br>';

        echo 'Play Count : ' . $Check->Playcount;
        echo '<br>';
        echo 'Performance : ' . $Check->Performance;
        echo '<br>';
        echo 'Ranking : ' . $Check->Rank;
        echo '<br><br>';
    }
    $Check->CheckUser(2558286, 0);
    GetDataTest();

    $Check->CheckUser('angelsIm', 0);
    GetDataTest();

    $Check->CheckUser('didisksks', 0);
    GetDataTest();

    $Check->CheckUser('peppy', 0);
    GetDataTest();

    $Check->CheckUser('banchobot', 0);
    GetDataTest();
