<?php


namespace App\Services;


use App\Contracts\ExportInterface;

class CsvExport implements ExportInterface
{

    public function export($listOfData)
    {
        $fileName = 'salesPayment-'.time().'.csv';
        $headers = ['Month','Payment Date','Bonus Payment Date'];
        $fp = fopen($fileName, 'w');
        fputcsv($fp, $headers);
        foreach ($listOfData as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
        return $fileName;
    }
}