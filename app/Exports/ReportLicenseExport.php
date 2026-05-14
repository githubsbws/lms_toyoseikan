<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;

use Maatwebsite\Excel\Events\AfterSheet;

class ReportLicenseExport implements
    FromArray,
    WithStyles,
    ShouldAutoSize,
    WithEvents
{
    protected $users;
    protected $operateMachines;
    protected $parameterSettings;
    protected $licenses;

    public function __construct(
        $users,
        $operateMachines,
        $parameterSettings,
        $licenses
    ){
        $this->users = $users;
        $this->operateMachines = $operateMachines;
        $this->parameterSettings = $parameterSettings;
        $this->licenses = $licenses;
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
            'Position'
        ];

        // Operate Machine Group
        for($i=0; $i<count($this->operateMachines); $i++){
            $header1[] = $i == 0 ? 'Operate Machine' : '';
        }

        // Parameter Setting Group
        for($i=0; $i<count($this->parameterSettings); $i++){
            $header1[] = $i == 0 ? 'Parameter Setting' : '';
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
            ''
        ];

        foreach($this->operateMachines as $machine){
            $header2[] = $machine->operation_name;
        }

        foreach($this->parameterSettings as $setting){
            $header2[] = $setting->parameter_name;
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
                optional($user->Orgchart)->title
            ];

            $userLicenses = $this->licenses[$user->id] ?? collect();

            $operateMap = $userLicenses
                ->whereNotNull('operation_machine_id')
                ->keyBy('operation_machine_id');

            $parameterMap = $userLicenses
                ->whereNotNull('parameter_setting_id')
                ->keyBy('parameter_setting_id');

            /*
            |--------------------------------------------------------------------------
            | Operate Machine
            |--------------------------------------------------------------------------
            */

            foreach($this->operateMachines as $machine){

                $license = $operateMap[$machine->id] ?? null;

                if(!$license){

                    $row[] = 'N/A';

                }else{

                    if($license->license_level == 3){
                        $row[] = 'LP3';
                    }elseif($license->license_level == 2){
                        $row[] = 'LP2';
                    }else{
                        $row[] = 'LP1';
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Parameter Setting
            |--------------------------------------------------------------------------
            */

            foreach($this->parameterSettings as $setting){

                $license = $parameterMap[$setting->id] ?? null;

                $row[] = $license ? 'O' : '-';
            }

            $rows[] = $row;
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [

            1 => [
                'font' => [
                    'bold' => true,
                ]
            ],

            2 => [
                'font' => [
                    'bold' => true,
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
                | Merge Left Header
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');

                /*
                |--------------------------------------------------------------------------
                | Merge Operate Machine
                |--------------------------------------------------------------------------
                */

                $operateStart = 5;
                $operateEnd = $operateStart + count($this->operateMachines) - 1;

                $sheet->mergeCells(
                    $this->columnLetter($operateStart).'1:'.
                    $this->columnLetter($operateEnd).'1'
                );

                /*
                |--------------------------------------------------------------------------
                | Merge Parameter Setting
                |--------------------------------------------------------------------------
                */

                $parameterStart = $operateEnd + 1;
                $parameterEnd = $parameterStart + count($this->parameterSettings) - 1;

                $sheet->mergeCells(
                    $this->columnLetter($parameterStart).'1:'.
                    $this->columnLetter($parameterEnd).'1'
                );

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

                $sheet->freezePane('E3');
            }
        ];
    }

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
