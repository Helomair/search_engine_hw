<?php

class GaisdbClient {
    private $_base_url;
    private $_segment_url;
    private $_default_segments;
    private $_db      ;
    private $_out_type;
    private $_header;

    public function __construct($base_url, $db) {
        $this->_base_url = $base_url;
        $this->_db = $db;
        $this->_out_type = "json";
        $this->_header[] =  "Content-Type: application/json";
        $this->_segment_url = "http://gaisdb.ccu.edu.tw:5721/api/segment";
    }

    public function get_recs($query = null, $page = 1, $ps = 10) {
        $url = $this->_base_url . "?db=" . $this->_db;
        $url .= ($query == null) ? "" : ( "&q=" . $query );
        $url .= "&p=" . $page ;
        $url .= "&ps=" . $ps  ;
        $url .= "&out=" . $this->_out_type;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $result = json_decode($response);
        curl_close($ch);

        return $result;
    }

    public function get_segments($query = null, $replace_default_segs = 0) {
        if ($query == null) {
            return false;
        }

        $url = $this->_segment_url . "?content=" . $query;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $result = json_decode($response);
        curl_close($ch);

        if ($replace_default_segs == 1) {
            $this->_default_segments = $result->recs;
        }

        return $result->recs;
    }

    public function highlight_segments($content, $segments = null) {
        if ($segments == null) {
            $segments = $this->_default_segments;
        }

        foreach ($segments as $segment) {
            $highlight = "<font color='red'>" . $segment . "</font>";
            $content = str_replace($segment, $highlight, $content);
        }

        return $content;
    }
}
?>