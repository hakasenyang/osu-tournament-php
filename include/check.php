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
         * [$RealID transfer osu! UserID to ID]
         * [$OsuID transfer osu! ID to UserID]
         * @var [string]
         */
        public $RealID, $OsuID;
        /**
         * [$Playcount User playcount]
         * [$Performance User performance]
         * [$Rank User ranking]
         * [$Occupation Check user occupation]
         * [$Occupation_Set Setting user occupation data]
         * @var [string]
         */
        public $Playcount, $Performance, $Rank, $Occupation, $Occupation_Set;
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
         * Temp data
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
        private function SelectMode($mode)
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
        public function CheckUser($osuid, $mode)
        {
            $this->ResetObject();
            $this->SelectMode($mode);
            // Transfer osu! ID to Num
            $this->data_profile = $this->Parser->WEBParsing('https://osu.ppy.sh/u/' . $osuid);
            $this->OsuID = $this->Parser->splits($this->data_profile, 'var userId = ', ';');
            $this->RealID = $this->Parser->splits($this->data_profile, '<title>', '\'s profile</title>');
            if(empty($this->OsuID) || empty($this->RealID))
            {
                $this->ResetObject();
                return false;
            }

            $this->Occupation = $this->Parser->splits($this->data_profile, '<div title=\'Occupation\'><i class=\'icon-pencil\'></i><div>', '</div></div>');

            $this->GetOccupation();
            $this->GetUserData();

            return $this->OsuID;
        }
        /**
         * Not using this function.
         */
        /*
        private function CheckOccupation()
        {
            if(empty($this->OsuID))
                throw new \Exception('Please, CheckUser(OsuID) first!');
            if(empty($this->Occupation_Set))
                $this->GetOccupation();

            if ($this->Occupation_Set === $this->Occupation)
                return true;
            else
                return $this->Occupation;
        }
        */
        private function GetOccupation()
        {
            /**
             * md5 = $RealID / $OsuID / date('Ymd') / $mode
             * 20 string (substr)
             */
            if(empty($this->OsuID))
                throw new \Exception('Please, CheckUser(OsuID) first!');
            if(empty($this->Occupation_Set))
                $this->Occupation_Set = substr(md5($this->RealID . $this->OsuID . date('Ymd') . $this->mode), 0, 20);
            return $this->Occupation_Set;
        }
        private function GetUserData()
        {
            if(empty($this->OsuID))
                throw new \Exception('Please, CheckUser(OsuID) first!');

            if(empty($this->data_userdata))
                $this->data_userdata = $this->Parser->WEBParsing('https://osu.ppy.sh/pages/include/profile-general.php?u=' . $this->OsuID . '&m=' . $this->mode);
            else
                return true;

            if (strpos($this->data_userdata, 'This user has not yet played any ranked maps in osu! mode.') === true)
                return false;

            if (strpos($this->data_userdata, 'This user has not played enough, or has not played recently.') === false)
            {
                $this->Playcount = $this->Parser->splits($this->data_userdata, '<b>Play Count</b>: ', '</div>');
                $this->Performance = str_replace(',', NULL, $this->Parser->splits($this->data_userdata, 'Performance</a>: ', 'pp'));
                $this->Rank = str_replace(',', NULL, $this->Parser->splits($this->data_userdata, 'pp (#', ')'));
            }
            else
                return false;
        }
        private function ResetObject()
        {
            foreach ($this as $key => $value)
                unset($this->$key);

            $this->__construct();
        }
    }
