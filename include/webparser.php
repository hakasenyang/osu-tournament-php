<?php
    namespace OsuTournament;
    class Parser
    {
        private $httph = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36';
        public function splits($data, $first, $end, $num = 1)
        {
            $temp = @explode($first, $data);
            $temp = @explode($end, $temp[$num]);
            $temp = $temp[0];
            return $temp;
        }
        public function WEBParsing($url, $cookie = NULL, $headershow = TRUE, $postparam = NULL, $otherheader = NULL)
        {
            $ch = curl_init();
            $opts = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $url,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_HEADER => $headershow,
                CURLOPT_USERAGENT => $this->httph
            );
            curl_setopt_array($ch, $opts);
            if ($otherheader) curl_setopt($ch, CURLOPT_HTTPHEADER, $otherheader);
            if ($cookie) curl_setopt($ch, CURLOPT_COOKIE, $cookie);
            if ($postparam)
            {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postparam);
            }

            $data = curl_exec($ch);
            curl_close($ch);
            if ($curl_errno > 0)
                $this->ErrorEcho(14, 'Connect Error!!!');
            return ($data) ? $data : false;
        }
    }