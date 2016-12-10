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
         * Other data
         * In production.
         */

        public function __construct($mode)
        {
            switch($mode)
            {
                case 0:
                case 'standard':
                    $this->mode = 0;
                    break;
                case 1:
                case 'taiko':
                    $this->mode = 1;
                    break;
                case 2:
                case 'ctb':
                    $this->mode = 2;
                    break;
                case 3:
                case 'mania':
                    $this->mode = 3;
                    break;
                default:
                    throw new Exception('Error Mode');
                    break;
            }
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
            $data = $this->Parser->WEBParsing('https://osu.ppy.sh/pages/include/profile-general.php?u=' . $this->OsuID .'&m=' . $this->mode);
            $this->PlayCount = $this->Parser->splits($data, '<b>Play Count</b>: ', '</div>');
            return $this->PlayCount;
        }
    }
