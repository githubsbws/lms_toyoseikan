<?php

namespace App\Exports;

use App\Models\Company;
use App\Models\Score;
use App\Models\Learn;
use App\Models\Users;
use App\Models\Lesson;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;

use Maatwebsite\Excel\Events\AfterSheet;


class ReportUserExport implements
    FromArray,
    WithStyles,
    ShouldAutoSize,
    WithEvents
{
    protected $users;
    protected $groupedCourses;
    protected $learns;

    public function __construct($users, $groupedCourses, $learns)
    {
        $this->users = $users;
        $this->groupedCourses = $groupedCourses;
        $this->learns = $learns;
    }

    public function array(): array
    {
        $rows = [];

        /*
        |--------------------------------------------------------------------------
        | HEADER ROW 1
        |--------------------------------------------------------------------------
        */

        $header1 = [
            'No',
            'Name',
            'Emp Code',
            'Position',
            'Period'
        ];

        foreach($this->groupedCourses as $group => $items){

            for($i=0; $i<count($items); $i++){
                $header1[] = $i == 0 ? $group : '';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | HEADER ROW 2
        |--------------------------------------------------------------------------
        */

        $header2 = [
            '',
            '',
            '',
            '',
            ''
        ];

        foreach($this->groupedCourses as $group => $items){

            foreach($items as $course){
                $header2[] = $course->course_title;
            }
        }

        $rows[] = $header1;
        $rows[] = $header2;

        /*
        |--------------------------------------------------------------------------
        | DATA ROWS
        |--------------------------------------------------------------------------
        */

        foreach($this->users as $index => $user){

            $row = [
                $index + 1,
                $user->firstname.' '.$user->lastname,
                $user->staff_id,
                optional($user->Orgchart)->title,
                $user->work_start
                    ? \Carbon\Carbon::parse($user->work_start)->format('d/m/Y')
                    : '-'
            ];

            $userLearns = $this->learns[$user->id] ?? collect();
            $courseMap = $userLearns->keyBy('course_id');

            foreach($this->groupedCourses as $group => $items){

                foreach($items as $course){

                    $percent = $courseMap[$course->course_id]->score ?? null;

                    $row[] = is_null($percent)
                        ? 'N/A'
                        : $percent.'%';
                }
            }

            $rows[] = $row;
        }

        return $rows;
    }

    /*
    |--------------------------------------------------------------------------
    | STYLE
    |--------------------------------------------------------------------------
    */

    public function styles(Worksheet $sheet)
    {
        return [

            // Header Row 1
            1 => [
                'font' => [
                    'bold' => true,
                ]
            ],

            // Header Row 2
            2 => [
                'font' => [
                    'bold' => true,
                ]
            ]
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | AFTER SHEET
    |--------------------------------------------------------------------------
    */

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function(AfterSheet $event){

                $sheet = $event->sheet->getDelegate();

                /*
                |--------------------------------------------------------------------------
                | Merge Header
                |--------------------------------------------------------------------------
                */

                $startCol = 6;

                foreach($this->groupedCourses as $group => $items){

                    $count = count($items);

                    $startLetter = $this->columnLetter($startCol);
                    $endLetter = $this->columnLetter($startCol + $count - 1);

                    $sheet->mergeCells(
                        $startLetter.'1:'.$endLetter.'1'
                    );

                    $startCol += $count;
                }

                /*
                |--------------------------------------------------------------------------
                | Merge Left Header
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:E2');

                /*
                |--------------------------------------------------------------------------
                | Center
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle(
                    'A1:'.$sheet->getHighestColumn().$sheet->getHighestRow()
                )->getAlignment()->setHorizontal(
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                );

                $sheet->getStyle(
                    'A1:'.$sheet->getHighestColumn().$sheet->getHighestRow()
                )->getAlignment()->setVertical(
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                );

                /*
                |--------------------------------------------------------------------------
                | Header Color
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle(
                    'A1:'.$sheet->getHighestColumn().'2'
                )->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FFF200'
                        ]
                    ]
                ]);

                /*
                |--------------------------------------------------------------------------
                | Freeze Pane
                |--------------------------------------------------------------------------
                */

                $sheet->freezePane('F3');

                /*
                |--------------------------------------------------------------------------
                | Auto Height
                |--------------------------------------------------------------------------
                */

                foreach(range(1, $sheet->getHighestRow()) as $row){
                    $sheet->getRowDimension($row)
                        ->setRowHeight(25);
                }

            }
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Convert Column Number To Excel Letter
    |--------------------------------------------------------------------------
    */

    private function columnLetter($c)
    {
        $letter = '';

        while($c > 0){
            $temp = ($c - 1) % 26;
            $letter = chr($temp + 65).$letter;
            $c = intval(($c - $temp - 1) / 26);
        }

        return $letter;
    }
}