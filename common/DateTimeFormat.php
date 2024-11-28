<?php
namespace Common;

class DateTimeFormat {
    public static function dateTimeToStr($date_time) {
        return $date_time->format('Y-m-d H:i:s');
    }
    
    public static function strToDateTime($string) {
        return new \DateTime($string);
    }
}

?>