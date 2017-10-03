<?php
/**
 * @file test.php
 * @author Hakase (contact@hakase.kr)
 */
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
        echo '<br>';
        echo 'Server : ';
        switch($Check->server)
        {
            case 'osu':
                echo 'osu!';
                break;
            case 'ripple':
                echo 'Ripple';
                break;
            default:
                echo 'No';
        }
        echo '<br><br>';
    }
    $Check->CheckUser('Hakase', 0, 2);
    GetDataTest();

    $Check->CheckUser('Hakase', 1, 2);
    GetDataTest();

    $Check->CheckUser(5173, 0, 2);
    GetDataTest();

    $Check->CheckUser('aergn8i39jf', 1, 2);
    GetDataTest();

    $Check->CheckUser('peppy', 0);
    GetDataTest();

    $Check->CheckUser('aergn8i39jf', 1, 1);
    GetDataTest();

    $Check->CheckUser('3318', 0, 2, 'https://test.hoto.us/ripple');
    GetDataTest();
	
    $Check->CheckUser('3318', 0, 2);
    GetDataTest();