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
     * The date's year.
     *
     * @var string
     */
    protected $year = '0001';

    /**
     * The date's month
     *
     * @var string
     */
    protected $month = '01';

    /**
     * The date's day
     *
     * @var string
     */
    protected $day = '01';

    /**
     * Days elapsed since 0001/01/01
     *
     * @see getElapsedDays()
     *
     * @var string
     */
    protected $elapsedDays = null;

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
        $this->day = $day;
        $this->initElapsedDays();
    }

    /**
     * __toString()
     *
     * @return string
     */
    public function __toString()
    {
        return "$this->year/$this->month/$this->day";
    }

    /**
     * Getter.
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Getter.
     *
     * @return string
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Getter.
     *
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Determine how many days have elapsed since 0001/01/01
     *
     * @throws \Exception
     */
    protected function initElapsedDays()
    {
        $totalDays = 0;
        $year = (int) $this->year;
        $month = (int) $this->month;
        $day = (int) $this->day;
        
        $totalDays += ($year - 1) * 365;
        // Add on leap days.
        // Hey, PHP 7!
        $this->elapsedLeapDays = intdiv($year, 4);
        $totalDays += $this->elapsedLeapDays;
        // Add on the (hard-coded) days in completed months this year.
        // e.g.
        // * if we're in May (5), then add in all of April's days, then fall through to January
        // * if we're in January (1), then no completed's month days at all.
        $monthDays = 0;
        switch ((int) $month) {
            case 12:
                $monthDays += 30;
            case 11:
                $monthDays += 31;
            case 10:
                $monthDays += 30;
            case 9:
                $monthDays += 31;
            case 8:
                $monthDays += 31;
            case 7:
                $monthDays += 30;
            case 6:
                $monthDays += 31;
            case 5:
                $monthDays += 30;
            case 4:
                $monthDays += 31;
            case 3:
                $monthDays += 28;
            case 2:
                $monthDays += 31;
            case 1:
                $monthDays += 0;
        }
        $totalDays += $monthDays;
        $totalDays += $day - 1;
        $this->elapsedThisYear = $monthDays + $day - 1;
        // And if this year is a leap year...
        if ($month > 2 && ($year % 4) == 0) {
            $totalDays += 1;
            $this->elapsedThisYear += 1;
        }
        $this->elapsedDays = $totalDays;
        
        return $totalDays;
    }

    /**
     * Getter.
     * *
     *
     * @return integer
     */
    public function getElapsedDays()
    {
        return $this->elapsedDays;
    }

    /**
     * Getter.
     * *
     *
     * @return integer
     */
    public function getElapsedThisYear()
    {
        return $this->elapsedThisYear;
    }

    /**
     * Getter.
     * *
     *
     * @return integer
     */
    public function getElapsedLeapDays()
    {
        return $this->elapsedLeapDays;
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
        
        $dateFrom = new MyDate($start);
        $dateTo = new MyDate($end);
        
        // Work out the total difference. We could do something elegant by comparing the interplay
        // between years / months / days of boths dates all at once, but for our purposes,
        // we'll brute-force it by working out time elapsed for both dates and comparing them.
        $fromElapsed = $dateFrom->getElapsedDays();
        $toElapsed = $dateTo->getElapsedDays();
        $diff = $toElapsed - $fromElapsed;
        $inverted = $diff < 0; // (abs($diff) != $diff);
                               
        // Now, the spec doesn't make clear what happens if e.g. two dates are 500 years apart,
                               // so that leap days are much bigger than the days between the two months.
                               // For now, I'll just compare the raw values and see how the TDD (tests) react.
        
        $yearDiff = $dateTo->getYear() - $dateFrom->getYear();
        if ($dateTo->getElapsedThisYear() < $dateFrom->getElapsedThisYear()) {
            $yearDiff -= 1;
        }
        $monthDiff = $dateTo->getMonth() - $dateFrom->getMonth();
        $dayDiff = $dateTo->getDay() - $dateFrom->getDay();
        if ($dayDiff < 0) {
            $monthDiff -= 1;
        }
        
        $return->years = $yearDiff;
        $return->months = $monthDiff;
        $return->days = $dayDiff;
        
        $return->total_days = abs($diff);
        $return->invert = $inverted;
        // symfony/var-dumper
        dump($dateFrom);
        dump($dateTo);
        dump($return);
        return $return;
    }
}
