<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;

use Maatwebsite\Excel\Events\AfterSheet;

class PersonalAssessmentDetailExport implements
    FromArray,
    WithStyles,
    ShouldAutoSize,
    WithEvents
{
    protected $user;
    protected $assessments;

    public function __construct($user, $assessments)
    {
        $this->user = $user;
        $this->assessments = $assessments;
    }

    public function array(): array
    {
        $rows = [];

        /*
        |--------------------------------------------------------------------------
        | TITLE
        |--------------------------------------------------------------------------
        */

        $rows[] = [
            'แบบแจ้งและประเมินผลการฝึกอบรมตามตำแหน่งงาน'
        ];

        /*
        |--------------------------------------------------------------------------
        | EMPTY
        |--------------------------------------------------------------------------
        */

        $rows[] = [];

        /*
        |--------------------------------------------------------------------------
        | USER INFO HEADER
        |--------------------------------------------------------------------------
        */

        $rows[] = [
            'ชื่อ - สกุล',
            $this->user->Profiles->firstname.' '.$this->user->Profiles->lastname,
            '',
            'รหัส',
            $this->user->staff_id,
            '',
            'ตำแหน่ง',
            optional($this->user->Orgchart)->title
        ];

        /*
        |--------------------------------------------------------------------------
        | EMPTY
        |--------------------------------------------------------------------------
        */

        $rows[] = [];

        /*
        |--------------------------------------------------------------------------
        | TABLE HEADER
        |--------------------------------------------------------------------------
        */

        $rows[] = [
            'ลำดับ',
            'หัวข้ออบรม',
            'วันที่',
            'ชั่วโมง',
            'ถาม-ตอบ',
            'ปฏิบัติจริง',
            'ข้อสอบ',
            'ผลงาน',
            'คะแนน',
            'ผลประเมิน'
        ];

        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        foreach($this->assessments as $index => $assessment){

            if($assessment->level == 3){

                $result = '✔ ผ่าน';

            }elseif($assessment->level == 2){

                $result = '⚠ Under Supervision';

            }else{

                $result = '✖ ไม่ผ่าน';
            }

            $rows[] = [

                $index + 1,

                $assessment->topic->topic_name ?? '-',

                $assessment->assessment_date,

                $assessment->training_hours,

                $assessment->qa_score,

                $assessment->practice_score,

                $assessment->exam_score,

                $assessment->work_score,

                $assessment->total_score.'%',

                $result
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [

            // Title
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16
                ]
            ],

            // Table Header
            5 => [
                'font' => [
                    'bold' => true
                ]
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function(AfterSheet $event){

            $sheet = $event->sheet->getDelegate();

            /*
            |--------------------------------------------------------------------------
            | Merge Title
            |--------------------------------------------------------------------------
            */

            $sheet->mergeCells('A1:J1');

            /*
            |--------------------------------------------------------------------------
            | Merge User Info
            |--------------------------------------------------------------------------
            */

            $sheet->mergeCells('B3:C3');
            $sheet->mergeCells('E3:F3');
            $sheet->mergeCells('H3:J3');

            /*
            |--------------------------------------------------------------------------
            | Title Style
            |--------------------------------------------------------------------------
            */

            $sheet->getStyle('A1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 18
                ],

                'alignment' => [
                    'horizontal' =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                ]
            ]);

            /*
            |--------------------------------------------------------------------------
            | Header Color
            |--------------------------------------------------------------------------
            */

            $sheet->getStyle('A5:J5')
                ->applyFromArray([

                    'font' => [
                        'bold' => true
                    ],

                    'fill' => [

                        'fillType' => Fill::FILL_SOLID,

                        'startColor' => [
                            'rgb' => 'FFF200'
                        ]
                    ]
                ]);

            /*
            |--------------------------------------------------------------------------
            | Borders
            |--------------------------------------------------------------------------
            */

            $highestRow = $sheet->getHighestRow();

            $sheet->getStyle('A3:J'.$highestRow)
                ->applyFromArray([

                    'borders' => [

                        'allBorders' => [

                            'borderStyle' =>
                            \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                        ]
                    ]
                ]);

            /*
            |--------------------------------------------------------------------------
            | Alignment
            |--------------------------------------------------------------------------
            */

            $sheet->getStyle('A1:J'.$highestRow)
                ->getAlignment()
                ->setVertical(
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                );

            $sheet->getStyle('A5:J'.$highestRow)
                ->getAlignment()
                ->setHorizontal(
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                );

            /*
            |--------------------------------------------------------------------------
            | Row Height
            |--------------------------------------------------------------------------
            */

            $sheet->getRowDimension(1)->setRowHeight(30);

            /*
            |--------------------------------------------------------------------------
            | Freeze
            |--------------------------------------------------------------------------
            */

            $sheet->freezePane('A6');
        }
        ];
    }
}
