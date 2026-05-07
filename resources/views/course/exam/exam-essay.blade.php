@extends('layout/mainlayout')
@section('title', 'Course')
@section('content')
    <div class="container" style="max-width: 850px; margin-top: 50px; padding-bottom: 50px;">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3" style="border-bottom: 2px solid #e2e8f0;">
            <div>
                <h2 style="font-size: 26px; font-weight: bold; color: #1F7BCC; margin: 0;">{{ $course->course_name }}</h2>
                <small style="color: #64748b; font-size: 15px;">แบบทดสอบ Final Exam (อัตนัย/บรรยาย)</small>
            </div>
            <div id="quiz-progress" style="font-size: 16px; font-weight: bold; color: #1F7BCC;">
                ข้อที่ <span id="current-index-text">1</span> / {{ $questions->count() }}
            </div>
        </div>

        <form action="{{ route('course.exam.submit-essay', $course->course_id) }}" method="POST" id="exam-form">
            @csrf

            @foreach($questions as $index => $question)
                <div class="card quiz-card shadow-sm mb-4" id="question-card-{{ $index }}" style="display: {{ $index === 0 ? 'block' : 'none' }}; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">

                    <div class="card-body" style="padding: 35px; background-color: #ffffff;">

                        @if($question->images && $question->images->isNotEmpty())
                            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-bottom: 25px; background-color: #f8fafc; padding: 15px; border-radius: 10px; border: 1px dashed #cbd5e1;">
                                @foreach($question->images as $img)
                                    <div style="position: relative; max-width: 45%;">
                                        <img src="{{ asset('storage/' . $img->img_path) }}" alt="โจทย์ข้อสอบ" style="max-height: 250px; width: 100%; object-fit: contain; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <h4 style="font-size: 22px; font-weight: 600; color: #1e293b; margin-bottom: 20px; line-height: 1.5;">
                            {{ $index + 1 }}. {{ $question->ques_detail }}
                        </h4>

                        <div class="form-group" style="margin-top: 15px;">
                            <label style="font-size: 16px; font-weight: bold; color: #1F7BCC; display: block; margin-bottom: 8px;">
                                <i class="fas fa-pen-fancy mr-1"></i> พิมพ์คำตอบของคุณด้านล่าง:
                            </label>
                            <textarea
                                name="answers[{{ $question->ques_id }}]"
                                class="form-control essay-input"
                                rows="6"
                                style="border-radius: 8px; border: 2px solid #cbd5e1; font-size: 16px; padding: 15px; width: 100%; transition: border-color 0.2s;"
                                placeholder="กรุณาเขียนอธิบายคำตอบให้ละเอียดชัดเจน..."
                                required></textarea>
                        </div>

                    </div>

                    <div class="card-footer d-flex justify-content-between" style="background-color: #ffffff; border-top: 1px solid #e2e8f0; padding: 20px 35px;">
                        <button type="button" class="btn btn-secondary btn-prev" data-index="{{ $index }}" style="font-weight: bold; padding: 10px 30px; border-radius: 8px;" {{ $index === 0 ? 'disabled' : '' }}>
                            <i class="fas fa-chevron-left mr-2"></i>ข้อก่อนหน้า
                        </button>

                        @if($index === $questions->count() - 1)
                            <button type="submit" class="btn btn-success" style="font-weight: bold; padding: 10px 40px; border-radius: 8px; background-color: #28a745; font-size: 17px;">
                                ส่งข้อสอบบรรยาย <i class="fas fa-paper-plane ml-2"></i>
                            </button>
                        @else
                            <button type="button" class="btn btn-primary btn-next" data-index="{{ $index }}" style="font-weight: bold; padding: 10px 30px; border-radius: 8px; background-color: #1F7BCC;">
                                ข้อถัดไป <i class="fas fa-chevron-right ml-2"></i>
                            </button>
                        @endif
                    </div>

                </div>
            @endforeach
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalQuestions = {{ $questions->count() }};
        const nextButtons = document.querySelectorAll('.btn-next');
        const prevButtons = document.querySelectorAll('.btn-prev');

        // สำหรับอัตนัยเราจะไม่เลื่อนข้ออัตโนมัติ (เพราะต้องรอพิมพ์พิมพ์จนเสร็จ) ให้ผู้เรียนกดปุ่ม Next เอง
        nextButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const currentIdx = parseInt(this.getAttribute('data-index'));

                // เช็คหน่อยว่าพิมพ์ตอบหรือยังก่อนยอมให้ไปข้อถัดไป
                const textarea = document.querySelector(`#question-card-${currentIdx} textarea`);
                if(!textarea.value.trim()){
                    alert('กรุณากรอกคำตอบก่อนไปข้อถัดไปครับ');
                    textarea.focus();
                    return;
                }

                navigateToQuestion(currentIdx, currentIdx + 1);
            });
        });

        prevButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const currentIdx = parseInt(this.getAttribute('data-index'));
                navigateToQuestion(currentIdx, currentIdx - 1);
            });
        });

        function navigateToQuestion(fromIdx, toIdx) {
            document.getElementById(`question-card-${fromIdx}`).style.display = 'none';
            document.getElementById(`question-card-${toIdx}`).style.display = 'block';
            document.getElementById('current-index-text').innerText = toIdx + 1;
        }
    });
    </script>

    <style>
        /* เอฟเฟกต์ตอนพนักงานคลิกโฟกัสที่ช่องพิมพ์ตอบ */
        .essay-input:focus {
            border-color: #1F7BCC !important;
            box-shadow: 0 0 0 0.2rem rgba(31, 123, 204, 0.25) !important;
            outline: none;
        }
    </style>
@endsection
