<?php

  class MyDate {

    public static function diff($start, $end) {

        $start = self::_toSecond($start);
        $end   = self::_toSecond($end);

        $diff = abs($start - $end);

        $years   = (int)($diff / (365*86400));
        $mon     = (int)(($diff - $years * 365*86400) / (30*86400));
        $days    = (int)(($diff - $years * 365*86400 - $mon*30*86400) / 86400);

      // Sample object:
      return (object)array(
        'years' => $years,
        'months' => $mon,
        'days' => $days,
        'total_days' => self::_getCountDays($diff),
        'invert' => ($start<=$end)?false:true
      );

    }

    public static function _getCountDays($second)
    {
        return (int)$second/86400;
    }

    public static function _toSecond($str_date)
    {
        preg_match('/^(?<year>\d+)[\/](?<mon>\d+)[\/](?<day>\d+)$/', $str_date, $dateArray);

        $second = ($dateArray['year']*365+$dateArray["mon"]*30+$dateArray["day"])*86400;

        return $second+self::getLeapCountSeconds($dateArray['year']);

    }

    private static function getLeapCountSeconds($year)
    {
        return ((int)($year/4)-(int)($year/100)+(int)($year/400))*86400;
    }

  }
