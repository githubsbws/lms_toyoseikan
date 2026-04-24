<?php

namespace App\Imports;

use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\IOFactory;

class QuesImportEssay implements OnEachRow
{
    private $id;
    private $imagesByRow = [];

    public function __construct($id, $imagesByRow)
    {
        $this->id = $id;
        $this->imagesByRow = $imagesByRow;
    }

      public function onRow(Row $row)
    {
        $userId = auth()->id();

        $index = $row->getIndex();

        \Log::info('STEP 3: MATCH', [
            'row_index' => $index,
            'images' => $this->imagesByRow[$index] ?? null
        ]);
        $data = array_values($row->toArray());

        if ($index < 2) return;

        $question = trim($data[1] ?? '');
        $answer   = trim($data[4] ?? '');

        if (!$question) return;

        $q = Question::create([
            'ques_type'  => '3',
            'ques_title' => $question,
            'group_id'   => $this->id,
            'active'     => 'y',
            'answer'     => $answer,
            'create_by'  => $userId,
        ]);

        // 🔥 ใช้รูปที่ส่งมา
        if (!empty($this->imagesByRow[$index])) {

            foreach ($this->imagesByRow[$index] as $img) {

                DB::table('question_images')->insert([
                    'ques_id' => $q->ques_id,
                    'path'    => $img,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}