<?php

use Carbon\Carbon;

require "vendor/autoload.php";

$salaryPayService = new \App\Services\SalaryPayService();

echo (new \App\Services\CsvExport())->export($salaryPayService->calculatePayDates()).PHP_EOL;