<?php

class Paginator {
    private $_limit;
    private $_page;
    private $_total;

    public function __construct($limit, $total, $page) {
        $this->_limit = $limit;
        $this->_total = $total;
        $this->_page  = $page;
    }

    public function createLinks( $links, $list_class, $now_data ) {
        if ( $this->_limit == 'all' ) {
            return '';
        }
     
        $last       = ceil( $this->_total / $this->_limit );
     
        $start      = ( ( $this->_page - $links ) > 0 ) ? $this->_page - $links : 1;
        $end        = ( ( $this->_page + $links ) < $last ) ? $this->_page + $links : $last;
     
        $html       = '<ul class="' . $list_class . '">';
     
        $class      = ( $this->_page == 1 ) ? "active" : "";
        $html       .= '<li class="page-item ' . $class .'"><a class="page-link" href="?SearchText=' . $now_data . '&page=' . ( $this->_page - 1 ) . '">&laquo;</a></li>';
     
        if ( $start > 1 ) {
            $html   .= '<li><a class="page-link" href="?SearchText=' . $now_data . '&page=1">1</a></li>';
            $html   .= '<li class="page-item ' . $class .'"><span>...</span></li>';
        }
     
        for ( $i = $start ; $i <= $end; $i++ ) {
            $class      = ( $this->_page == $i ) ? "active" : "";
            $html   .= '<li class="page-item ' . $class .'"><a class="page-link" href="?SearchText=' . $now_data . '&page=' . $i . '">' . $i . '</a></li>';
        }
     
        if ( $end < $last ) {
            $html   .= '<li class="page-item ' . $class .'"><span>...</span></li>';
            $html   .= '<li><a class="page-link" href="?SearchText=' . $now_data . '&page=' . $last . '">' . $last . '</a></li>';
        }
     
        $class      = ( $this->_page == $last ) ? "active" : "";
        $html       .= '<li class="page-item ' . $class .'"><a class="page-link" href="?SearchText=' . $now_data . '&page=' . ( $this->_page + 1 ) . '">&raquo;</a></li>';
     
        $html       .= '</ul>';
     
        return $html;
    }
}