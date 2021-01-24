<?php


namespace App\Controller;


use App\Model\LeapYear;
use Symfony\Component\HttpFoundation\Response;

class LeapYearController
{
    public function index($year): Response
    {
        $leapYear = new LeapYear();
        ob_start();
        if ($leapYear->isLeapYear($year)) {
            $message = 'Yep, this is a leap year!';
        } else {
            $message = 'Nope, this is not a leap year.';
        }
        include __DIR__.'/../pages/leap_year.php';
        return new Response(ob_get_clean());
    }
}