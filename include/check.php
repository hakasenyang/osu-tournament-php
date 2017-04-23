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
         * [$server osu! server
         *          0 or osu = osu! Server
         *          1 or ripple = Ripple Server
         *          other = return false
         *
         *          using data : only use osu or ripple (string)]
         * @var [int or string]
         */
        public $server;
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
        public function CheckUser($osuid, $mode, $server='osu')
        {
            $this->ResetObject();
            $this->SelectMode($mode);
            // Transfer osu! ID to Num
            switch($server)
            {
                case 'osu':
                case 1:
                    $this->server = 'osu';
                    $this->data_profile = $this->Parser->WEBParsing('https://osu.ppy.sh/u/' . $osuid);
                    $this->OsuID = $this->Parser->splits($this->data_profile, 'var userId = ', ';');
                    $this->RealID = $this->Parser->splits($this->data_profile, '<title>', '\'s profile</title>');
                    break;
                case 'ripple':
                case 2:
                    $this->server = 'ripple';
                    $this->data_profile = $this->Parser->WEBParsing('https://ripple.moe/u/' . $osuid);
                    $this->OsuID = $this->Parser->splits($this->data_profile, 'window.userID = \'', '\';');
                    $this->RealID = $this->Parser->splits($this->data_profile, '<title>', '&#39;s profile - Ripple');
                    break;
                default:
                    throw new \Exception($server . ' is not supported.');
            }
            if(empty($this->OsuID) || empty($this->RealID))
            {
                $this->ResetObject();
                return false;
            }
            switch($this->server)
            {
                case 'osu':
                    $this->Occupation = $this->Parser->splits($this->data_profile, '<div title=\'Occupation\'><i class=\'icon-pencil\'></i><div>', '</div></div>');
                    break;
                case 'ripple':
                    $this->Occupation = $this->Parser->splits($this->data_profile, '(aka <b>', '</b>)');
                    break;
                default:
                    throw new \Exception($this->server . ' is not supported.');
            }

            $this->GetOccupation();
            $this->GetUserData();

            return $this->OsuID;
        }
        /**
         * [GetOccupation Get user occupation data]
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
        /**
         * [GetUserData Get user data!]
         */
        private function GetUserData()
        {
            if(empty($this->OsuID))
                throw new \Exception('Please, CheckUser(OsuID) first!');
            switch($this->server)
            {
                case 'osu':
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
                    break;
                case 'ripple':
                    $this->Playcount = str_replace(',', NULL, $this->Parser->splits($this->data_profile, '<td class="right aligned">', '</td>', 6 + ($this->mode * 9)));
                    $this->Performance = str_replace(',', NULL, $this->Parser->splits($this->data_profile, '<td class="right aligned">', '</td>', 3 + ($this->mode * 9)));
                    $this->Rank = str_replace('#', NULL, $this->Parser->splits($this->data_profile, '<td class="right aligned">', '</td>', 1 + ($this->mode * 9)));
                    break;
                default:
                    throw new \Exception($this->server . ' is not supported.');
            }
        }
        /**
         * [ResetObject All reset using data]
         */
        private function ResetObject()
        {
            foreach ($this as $key => $value)
                unset($this->$key);

            $this->__construct();
        }
    }
