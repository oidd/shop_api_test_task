<?php

namespace App\Service\Reports;

use App\Service\ResourceServices\OrderService;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportService
{
    public function __construct(
        public Spreadsheet $spreadsheet,
        public OrderService $orderService
    )
    {}

    public function reportForDateRange(Carbon $start = null, Carbon $end = null)
    {
        $orders = $this->orderService->index(
            ['created_at_range' => "{$start->format('Y.m.d')}-{$end->format('Y.m.d')}",],
            ['customer_id']
        )->get();

        $number = 1;

        $sheet = $this->spreadsheet->getActiveSheet();

        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        for ($i = 1; $i < 8; $i++)
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);

        $headers = [
            'Order id',
            'Customer name',
            'Customer email',
            'Order status',
            'Products',
            'Count',
            'Amount'
        ];

        for ($i = 1; $i < count($headers) + 1; $i++)
            $sheet->setCellValue([$i, 1], $headers[$i-1]);

        $number++;

        foreach ($orders as $order) {
            $sheet->setCellValue("A{$number}", $order->id);
            $sheet->setCellValue("B{$number}", $order->customer->name);
            $sheet->setCellValue("C{$number}", $order->customer->email);
            $sheet->setCellValue("D{$number}", $order->status);

            $products = $order->products;

            for ($i = 0; $i < count($products); $i++, $number++)
            {
                $sheet->setCellValue("E" . $number, $products[$i]->name);
                $sheet->setCellValue("F" . $number, $products[$i]->pivot->count);
                $sheet->setCellValue("G" . $number, $products[$i]->price * $products[$i]->pivot->count);
            }

            $sheet->getCell("E{$number}")->getStyle()->getFont()->setBold(true);
            $sheet->setCellValue("E{$number}", 'SUMMARY');
            $sheet->setCellValue("G{$number}", $order->amount);

            $number += 1;
        }

        return new Xlsx($this->spreadsheet);
    }

}
