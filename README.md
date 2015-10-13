Avanti PHP Test
===============

Calculate the difference between two given dates without using any of the PHP date functions, objects or packages not written by yourself.

This includes:
 * DateTime
 * IntlGregorianCalendar
 * Calendar
 * Any composer packages implementing date functions (ie https://packagist.org/search/?q=date)

Assumptions
-----------

* Dates will be provided in the format “YYYY/MM/DD” for example “2015/03/21”.
* All dates will be based on the Gregorian calendar.
* There should be a single interface for calculating the difference in the format:

```php
$difference = MyDate::diff($start, $end);
```

* The return value should be an object in the format:

```php
stdClass {
    int   $years,        // The number of years between the two dates.
    int   $months,       // The number of months between the two dates less the years.
    int   $days,         // The number of days between the two dates less the months and the years.
    int   $total_days,   // The total days between the two dates including the months and years.
    bool  $invert        // true if the the difference is negative (i.e. $start > $end).
}
```

* You are free to structure the rest of your code however you like and will be asked to explain your decisions.
* Basic unit tests are provided however you are free to add tests to cover anything additional you feel is required.


Requirements
------------
* [PHP]: http://php.net
* [Composer]: https://getcomposer.org 
* [PHPUnit]: https://phpunit.de/getting-started.html 


Getting Started
---------------
* Fork and clone this repository
* From the root run `composer install`
* Start with the src/MyDate.php file and code until you are satisfied
* ZIP up your solution and send it in

Testing
-------
Basic unit tests are provided in the tests folder. To run these steps simply run:

```
phpunit --bootstrap= src/autoload.php tests/MyDateTest.php
```

Notes
-----
If you create new class files be sure to update the src/autoload.php file to load these for the tests.
