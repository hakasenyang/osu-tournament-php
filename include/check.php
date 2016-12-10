<?php
    namespace OsuTournament;
    class Check
    {
        /**
         * [$Parser Load parser class]
         * @var [class]
         */
        private $Parser;
        /**
         * [$OsuID transfer osu! ID to UserID]
         * [$PlayCount User Playcount]
         * [$Performance User Performance]
         * [$Rank User Ranking]
         * [$Occupation Check user occupation]
         * @var [string]
         */
        private $OsuID, $PlayCount, $Performance, $Rank, $Occupation;
        /**
         * Other data
         * In production.
         */

        public function __construct()
        {
            $this->Parser = new Parser();
        }
        public function CheckUser($osuid)
        {
            // Transfer osu! ID to Num
            $data = $this->Parser->WEBParsing('https://osu.ppy.sh/u/'.$osuid);
            $this->OsuID = $this->Parser->splits($data, 'var userId = ', ';');
            if(!isset($this->OsuID)) return 0;
            return $this->OsuID;
        }
        public function CheckPlayCount()
        {
            $data = $this->Parser->WEBParsing('https://osu.ppy.sh/pages/include/profile-general.php?u='. $this->OsuID .'&m=0');
            $this->PlayCount = $this->Parser->splits($data, '<b>Play Count</b>: ', '</div>');
            return $this->PlayCount;
        }
    }
