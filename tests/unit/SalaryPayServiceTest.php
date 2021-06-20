<?php


namespace Tests\Unit;


use App\Enum\ConfigEnum;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class SalaryPayServiceTest extends TestCase
{
    /** @test */
    public function payment_day_must_be_the_last_weekday_if_last_day_of_the_month_is_weekend()
    {
        $month = Carbon::create('2021','10');
        $salaryPayService = new \App\Services\SalaryPayService();
        $payDate = $salaryPayService->calculatePaymentDate($month);
        //Assert That Last Day Of the this month is a weekend
        $this->assertContains($month->lastOfMonth()->format('D'),ConfigEnum::WEEEKENDS);
        //PayDate Must Be 29 Because 30 is a weekend in this date
        $this->assertEquals("2021-10-29",$payDate);
    }

    /** @test */
    public function bonus_payment_day_must_be_the_next_wednesday_if_15th_is_weekend()
    {
        $month = Carbon::create('2021','08');
        $salaryPayService = new \App\Services\SalaryPayService();
        $payDate = $salaryPayService->calculateBonusPaymentDate($month);
        //Assert That 15th of this month is a weekend
        $fifteenth = Carbon::create($month->format('Y'), $month->format('m'), 15);
        $this->assertContains($fifteenth->format('D'),ConfigEnum::WEEEKENDS);
        //Bonus Pay date is the next wednesday
        $this->assertEquals("2021-08-18",$payDate);
    }

    /** @test */
    public function payment_day_must_be_last_day_of_the_month()
    {
        $month = Carbon::create('2021','12');
        $salaryPayService = new \App\Services\SalaryPayService();
        $payDate = $salaryPayService->calculatePaymentDate($month);
        //Assert That Last Day Of the this month is not a weekend
        $this->assertNotContains($month->lastOfMonth()->format('D'),ConfigEnum::WEEEKENDS);
        //Pay Date Must Be 31
        $this->assertEquals("2021-12-31",$payDate);
    }

    /** @test */
    public function bonus_payment_day_must_be_15th_of_the_month()
    {
        $month = Carbon::create('2021','12');
        $salaryPayService = new \App\Services\SalaryPayService();
        $payDate = $salaryPayService->calculateBonusPaymentDate($month);
        //Assert That 15th of this month is not a weekend
        $fifteenth = Carbon::create($month->format('Y'), $month->format('m'), 15);
        $this->assertNotContains($fifteenth->format('D'),ConfigEnum::WEEEKENDS);
        //Bonus Pay date is the 15th
        $this->assertEquals("2021-12-15",$payDate);
    }

}