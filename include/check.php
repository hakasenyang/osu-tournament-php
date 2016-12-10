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
         * [$mode osu! mode
         *        0 = osu! standard (standard)
         *        1 = Taiko (taiko)
         *        2 = Catch The Beat (ctb)
         *        3 = osu!mania (mania)
         * ]
         * @var [int]
         */
        private $mode;
        /**
         * [$data_profile description]
         * [$data_userdata description]
         * @var [string]
         */
        private $data_profile, $data_userdata;
        /**
         * Other data
         * In production.
         */

        public function __construct()
        {
            $this->Parser = new Parser();
        }
        /**
         * [SelectMode osu! mode]
         * [$mode osu! mode
         *        0 = osu! standard (standard)
         *        1 = Taiko (taiko)
         *        2 = Catch The Beat (ctb)
         *        3 = osu!mania (mania)
         * ]
         * @var [int]
         */
        public function SelectMode($mode)
        {
            switch(strtolower($mode))
            {
                case '0':
                case 'standard':
                    $this->mode = 0;
                    break;
                case '1':
                case 'taiko':
                    $this->mode = 1;
                    break;
                case '2':
                case 'ctb':
                    $this->mode = 2;
                    break;
                case '3':
                case 'mania':
                    $this->mode = 3;
                    break;
                default:
                    throw new \Exception($mode . ' is not supported.');
                    break;
            }
            return $this->mode;
        }
        /**
         * [CheckUser Get osu! UserID]
         * @param [string] $osuid [osu! ID]
         */
        public function CheckUser($osuid)
        {
            // Transfer osu! ID to Num
            $this->data_profile = $this->Parser->WEBParsing('https://osu.ppy.sh/u/' . $osuid);
            $this->OsuID = $this->Parser->splits($this->data_profile, 'var userId = ', ';');
            if(empty($this->OsuID))
                return false;

            $this->Occupation = $this->Parser->splits($this->data_profile, '<div title=\'Occupation\'><i class=\'icon-pencil\'></i><div>', '</div></div>');
            return $this->OsuID;
        }
        public function CheckOccupation()
        {
            /**
             * md5 = $OsuID|date('Ymd')|$mode
             * 20 string (substr)
             */
            $occu = substr(md5($this->OsuID.'|' . date('Ymd') . '|' . $this->mode), 0, 20);
            if ($occu === $this->Occupation)
                return true;
            else
                return $occu;
        }
        private function GetUserData()
        {
            if(empty($this->OsuID))
                throw new \Exception('Please, CheckUser(OsuID) first!');
            if(empty($this->data_userdata))
                $this->data_userdata = $this->Parser->WEBParsing('https://osu.ppy.sh/pages/include/profile-general.php?u=' . $this->OsuID . '&m=' . $this->mode);
        }
        /**
         * [CheckPlayCount Get PlayCount]
         */
        public function CheckPlayCount()
        {
            $this->GetUserData();
            $this->PlayCount = $this->Parser->splits($this->data_userdata, '<b>Play Count</b>: ', '</div>');
            return $this->PlayCount;
        }
        public function CheckPerformance()
        {
            $this->GetUserData();
            if (strpos($this->data_userdata, 'This user has not played enough, or has not played recently.'))
                return false;
            $this->Performance = str_replace(',', NULL, $this->Parser->splits($this->data_userdata, 'Performance</a>: ', 'pp'));
            return $this->Performance;
        }
        public function CheckRank()
        {
            $this->GetUserData();
            if (strpos($this->data_userdata, 'This user has not played enough, or has not played recently.'))
                return false;
            $this->Rank = str_replace(',', NULL, $this->Parser->splits($this->data_userdata, 'pp (#', ')'));
            return $this->Rank;
        }
        public function ResetObject()
        {
            foreach ($this as $key => $value)
                unset($this->$key);

            $this->__construct();
        }
    }
