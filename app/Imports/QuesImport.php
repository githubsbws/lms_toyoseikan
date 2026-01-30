<?php

namespace App\Imports;

use App\Models\Position;
use App\Models\Question;
use App\Models\Choice;
use App\Models\Users;
use App\Models\Grouptesting;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Facades\AuthFacade;

class QuesImport implements ToModel, WithHeadingRow
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function model(array $row)
    {
        // แปลงประเภทคำถาม
        $typeMapping = [
            'คำตอบแบบเลือกได้หลายคำตอบ' => '1',
            'ตอบแบบเลือกคำตอบเดียว'    => '2',
            'คำตอบแบบเลือกคำตอบเดียว'    => '2', // เผื่อ Excel เขียนไม่ตรงกัน
            'คำตอบแบบหลายบรรทัด'        => '3',
        ];

        $ques_type = $typeMapping[$row['type']] ?? null;
        $userId = auth()->id();

        if (!$ques_type || empty($row['question'])) {
            return null; // ข้ามถ้าไม่มีประเภทหรือคำถาม
        }

        // สร้างคำถาม
        $question = Question::create([
            'ques_type'   => $ques_type,
            'ques_title'  => $row['question'],
            'active'      => 'y',
            'create_date' => Carbon::now(),
            'update_date' => Carbon::now(),
            'create_by'   => $userId,
            'update_by'   => $userId,
            'group_id'    => $this->id,
        ]);

        // กรณีที่เป็นแบบมีตัวเลือก (choice)
        if (in_array($ques_type, ['1', '2'])) {
            foreach ($row as $key => $value) {
                if (Str::startsWith($key, 'choice_') && trim($value) !== '') {
                    $isAnswer = Str::startsWith($value, '*');
                    $choiceDetail = $isAnswer ? substr($value, 1) : $value;

                    Choice::create([
                        'ques_id'       => $question->ques_id,
                        'choice_detail' => trim($choiceDetail),
                        'choice_answer' => $isAnswer ? '1' : '2',
                        'choice_type'   => $row['type'],
                        'active'        => 'y',
                        'create_date'   => Carbon::now(),
                        'update_date'   => Carbon::now(),
                        'create_by'     => $userId,
                        'update_by'     => $userId,
                    ]);
                }
            }
        }

        // ถ้าเป็นแบบหลายบรรทัด จะไม่มี choice ก็ไม่มีปัญหา
    }
}
    