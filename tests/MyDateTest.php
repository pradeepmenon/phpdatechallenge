<?php

class MyDateTest extends PHPUnit_Framework_TestCase
{

    public function providerValidDates()
    {
        return array(
            array(
                '0001/01/01'
            ),
            array(
                '2017/03/06'
            ),
            array(
                '2016/02/29'
            )
        ); // A valid leap day.
    }

    /**
     * Verify basic date validation.
     *
     * @dataProvider providerValidDates
     * @group validate
     */
    public function testValidDates($date)
    {
        $dateObject = new MyDate($date);
        $this->assertEquals((string) $dateObject, $date);
    }

    public function providerInvalidDates()
    {
        return array(
            array(
                'foo'
            ),
            array(
                '2017/01/32'
            ), // Not a valid day.
            array(
                '2017/13/01'
            ), // Not a valid month.
            array(
                '2017/09/31'
            ), // Not a valid day of the month.
            array(
                '2017/02/29'
            )
        ); // Not a valid leap day.
    }

    public function providerElapsed()
    {
        return array(
            array(
                '01/01/01',
                0
            ),
            array(
                '0002/01/01',
                365
            ),
            array(
                '1001/01/01',
                1000 * 365 + (1000 / 4)
            ),
            array(
                '2001/01/01',
                2000 * 365 + (2000 / 4)
            ),
            array(
                '2002/01/01',
                2001 * 365 + (2000 / 4)
            ),
            array(
                '2002/01/15',
                2001 * 365 + (2000 / 4) + 14
            )
        );
    }

    /**
     * Verify MyDate::Elapsed()
     *
     * @dataProvider providerElapsed
     * @group validate
     */
    public function testElapsed($date, $expected)
    {
        $date = new MyDate($date);
        $this->assertSame($expected, $date->getElapsedDays());
    }

    /**
     * @dataProvider providerInvalidDates
     * @group validate
     * @expectedException Exception
     */
    public function testInvalidDates($date)
    {
        $date = new MyDate($date);
    }

    public function testSimpleTotalDays()
    {
        $this->assertTotalDays('2014/01/01', '2014/01/04');
    }

    public function testSimpleDays()
    {
        $this->assertDays('2014/01/01', '2014/01/04');
    }

    public function testSimpleMonths()
    {
        $this->assertMonths('2014/01/01', '2014/03/01');
    }

    public function testSimpleYears()
    {
        $this->assertYears('2014/01/01', '2015/01/01');
    }

    public function testInvertDayTrue()
    {
        $this->assertInvert('2015/01/02', '2015/01/01');
    }

    public function testInvertMonthTrue()
    {
        $this->assertInvert('2015/02/02', '2015/01/01');
    }

    public function testInvertYearTrue()
    {
        $this->assertInvert('2016/01/01', '2015/01/01');
    }

    public function testInvertDayFalse()
    {
        $this->assertInvert('2015/01/01', '2015/01/02');
    }

    public function testInvertMonthFalse()
    {
        $this->assertInvert('2015/01/01', '2015/02/01');
    }

    public function testInvertYearFalse()
    {
        $this->assertInvert('2015/01/01', '2016/01/01');
    }

    public function testComplexTotalDays()
    {
        $this->assertTotalDays('2013/01/01', '2015/05/15');
    }

    public function testComplexDays()
    {
        $this->assertDays('2013/03/21', '2015/07/31');
    }

    public function testComplexMonths()
    {
        $this->assertMonths('2013/06/15', '2015/03/01');
    }

    public function testComplexYears()
    {
        $this->assertYears('2013/09/13', '2015/07/01');
    }

    public function testLeapYearTotalDays()
    {
        $this->assertTotalDays('2013/01/01', '2017/05/15');
    }

    public function testLeapYearDays()
    {
        $this->assertDays('2013/03/21', '2017/07/31');
    }

    public function testLeapYearMonths()
    {
        $this->assertMonths('2013/06/15', '2017/03/01');
    }

    public function testLeapYearYears()
    {
        $this->assertYears('2013/09/13', '2017/07/01');
    }

    public function testInvertLeapYearTrue()
    {
        $this->assertInvert('2017/03/16', '2013/06/18');
    }

    public function testMultipleLeapYearTotalDays()
    {
        $this->assertTotalDays('2013/01/01', '2029/05/15');
    }

    public function testMultipleLeapYearDays()
    {
        $this->assertDays('2013/03/21', '2028/07/31');
    }

    public function testMultipleLeapYearMonths()
    {
        $this->assertMonths('2013/06/15', '2029/03/01');
    }

    public function testMultipleLeapYearYears()
    {
        $this->assertYears('2013/09/13', '2029/07/01');
    }

    public function testInvertMultipleLeapYearTrue()
    {
        $this->assertInvert('2029/03/16', '2013/06/18');
    }

    /**
     * Verify MyDate's behaviour against inbuilt PHP.
     *
     * @param string $s            
     * @param string $e            
     */
    private function assertYears($s, $e)
    {
        $d = MyDate::diff($s, $e);
        $a = $this->dateDiff($s, $e);
        $this->assertSame($a->y, $d->years, "Years in '$s', '$e' do not match");
    }

    /**
     * Verify MyDate's behaviour against inbuilt PHP.
     *
     * @param string $s            
     * @param string $e            
     */
    private function assertMonths($s, $e)
    {
        $d = MyDate::diff($s, $e);
        $a = $this->dateDiff($s, $e);
        $this->assertSame($a->m, $d->months, "Months in '$s', '$e' do not match");
    }

    /**
     * Verify MyDate's behaviour against inbuilt PHP.
     *
     * @param string $s            
     * @param string $e            
     */
    private function assertDays($s, $e)
    {
        $d = MyDate::diff($s, $e);
        $a = $this->dateDiff($s, $e);
        $this->assertSame($a->d, $d->days, "days in '$s', '$e' do not match");
    }

    /**
     * Verify MyDate's behaviour against inbuilt PHP.
     *
     * @param string $s            
     * @param string $e            
     */
    private function assertTotalDays($s, $e)
    {
        $d = MyDate::diff($s, $e);
        $a = $this->dateDiff($s, $e);
        $this->assertSame($a->days, $d->total_days, "Total Days in '$s', '$e' do not match");
    }

    /**
     * Verify MyDate's behaviour against inbuilt PHP.
     *
     * @param string $s            
     * @param string $e            
     */
    private function assertInvert($s, $e)
    {
        $d = MyDate::diff($s, $e);
        $a = $this->dateDiff($s, $e);
        $this->assertSame((bool) $a->invert, $d->invert, "Invert in '$s', '$e' do not match");
    }

    /**
     * Helper method that uses inbuilt PHP for verification.
     *
     * @param string $start            
     * @param string $end            
     * @return \DateInterval
     */
    private function dateDiff($start, $end)
    {
        $start = DateTime::createFromFormat('Y/m/d', $start);
        $end = DateTime::createFromFormat('Y/m/d', $end);
        return $start->diff($end);
    }
}
