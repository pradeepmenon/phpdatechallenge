<?php

/**
 * Clean-room implementation of Date-like functionality.
 * 
 * The phrase "for our purposes" appears a lot, because we're
 * implementing only the functionality that meets the tests - 
 * see README.md for more info.
 * 
 * @author John Field <John.Field@gMail.com>
 */
class MyDate
{

    /**
     * The date's year - for our purposes, an integer.
     *
     * @var int
     */
    protected $year;

    /**
     * The date's month - for our purposes, a positive integer.
     *
     * @var int
     */
    protected $month;

    /**
     * The date's day - for our purposes, a positive integer.
     *
     * @var int
     */
    protected $day;

    /**
     * Constructor.
     *
     * @param string $date            
     */
    public function __construct($date)
    {
        $this->set($date);
    }

    /**
     * Setter.
     *
     * For our purposes, we only accept “YYYY/MM/DD”, performing basic validation.
     *
     * @param string $date            
     * @throws \Exception
     */
    public function set($date)
    {
        $dateSplit = explode('/', $date);
        if (count($dateSplit) != 3) {
            throw new \Exception("Date '$date' was invalid.");
        }
        // Validate ranges.
        // A valid value may well be a string (e.g. "03"), so convert, then validate, then pad out.
        
        $year = (int) $dateSplit[0];
        $year = filter_var($year, FILTER_VALIDATE_INT);
        if (! $year) {
            throw new \Exception("Year '{$dateSplit[0]}' was invalid.");
        }
        $year = str_pad($year, 4, '0', STR_PAD_LEFT);
        
        $month = (int) $dateSplit[1];
        $month = filter_var($month, FILTER_VALIDATE_INT, array(
            "options" => array(
                "min_range" => 1,
                "max_range" => 12
            )
        ));
        
        if (! $month) {
            throw new \Exception("Month '{$dateSplit[1]}' was invalid.");
        }
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        $day = (int) $dateSplit[2];
        $day = filter_var($day, FILTER_VALIDATE_INT, array(
            "options" => array(
                "min_range" => 1,
                "max_range" => 31
            )
        ));
        
        if (! $day) {
            throw new \Exception("Day '{$dateSplit[2]}' was invalid.");
        }
        $day = str_pad($day, 2, '0', STR_PAD_LEFT);
        
        // Finally, validate the days in the month.
        // https://en.wikipedia.org/wiki/Thirty_Days_Hath_September
        // Going by the spec, we don't need to know month names.
        switch ((int) $month) {
            case 9:
            case 4:
            case 6:
            case 11:
                $maxDaysinMonth = 30;
                break;
            case 2:
                $maxDaysinMonth = 28;
                if (($year % 4) == 0) {
                    // Leap year.
                    $maxDaysinMonth = 29;
                }
                break;
            default:
                $maxDaysinMonth = 31;
        }
        if ((int) $day > $maxDaysinMonth) {
            throw new \Exception("Day '$day' was invalid for month '$month'.");
        }
        
        // Hooray!
        $this->year = $year;
        $this->month = $month;
        $this->date = $day;
    }

    /**
     * Return difference between two dates.
     *
     * For our purposes, the returned object is similar to
     * http://php.net/manual/en/class.dateinterval.php
     *
     * @param string $start            
     * @param string $end            
     * @return StdClass
     */
    public static function diff($start, $end)
    {
        // Init our diff object.
        $return = (object) array(
            'years' => null,
            'months' => null,
            'days' => null,
            'total_days' => null,
            'invert' => null
        );
        
        return $return;
    }
}
