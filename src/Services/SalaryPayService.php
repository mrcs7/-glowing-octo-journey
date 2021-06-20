<?php


namespace App\Services;


use App\DateAndTime;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class SalaryPayService
{
    private $dateTimeService;

    public function __construct()
    {
        $this->dateTimeService =  new Carbon();
    }

    public function calculatePayDates(): array
    {
        $payDates = [];
        $monthsArray = $this->getRemainingMonthsForTheCurrentYear();
        foreach ($monthsArray as $month) {
            $month = $this->dateTimeService->create($month->format('Y'), $month->format('m'));
            $monthlyPayment['month'] = $month->format('M');
            $monthlyPayment['payDay'] = $this->calculatePaymentDate($month);
            $monthlyPayment['bonusPayDay'] = $this->calculateBonusPaymentDate($month);
            $payDates[] = $monthlyPayment;

        }
        return $payDates;
    }

    public function getRemainingMonthsForTheCurrentYear() : array
    {
        $months = [];
        foreach (CarbonPeriod::create($this->dateTimeService->now(), '1 month', $this->dateTimeService->lastOfYear()) as $month) {
            $months[] = $month;
        }
        return $months;
    }

    public function calculatePaymentDate($month)
    {
        $lastDayOfTheMonth = $month->lastOfMonth();
        $payDay = $lastDayOfTheMonth->toDateString();
        if (in_array($lastDayOfTheMonth->format('D'), \App\Enum\ConfigEnum::WEEEKENDS)) {
            $payDay = $lastDayOfTheMonth->previous('Friday')->toDateString();
        }
        return $payDay;

    }

    public function calculateBonusPaymentDate($month)
    {
        $fifteenthDay = $this->dateTimeService->create($month->format('Y'), $month->format('m'), 15);
        $bonusPayDay = $fifteenthDay->toDateString();
        if (in_array($fifteenthDay->format('D'), \App\Enum\ConfigEnum::WEEEKENDS)) {
            $bonusPayDay = $fifteenthDay->next('Wednesday')->toDateString();
        }
        return $bonusPayDay;
    }


}