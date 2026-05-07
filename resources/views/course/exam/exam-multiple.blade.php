@extends('layout/mainlayout')
@section('title', 'Course')
@section('content')
    <div class="container" style="max-width: 800px; margin-top: 50px; padding-bottom: 50px;">

        <div class="d-flex justify-content-between align-items-center mb-4 pb-3" style="border-bottom: 2px solid #e2e8f0;">
            <div>
                <h2 style="font-size: 26px; font-weight: bold; color: #1F7BCC; margin: 0;">{{ $course->course_name }}</h2>
                <small style="color: #64748b; font-size: 15px;">แบบทดสอบ Final Exam (ปรนัย)</small>
            </div>
            <div id="quiz-progress" style="font-size: 16px; font-weight: bold; color: #1F7BCC;">
                ข้อที่ <span id="current-index-text">1</span> / {{ $questions->count() }}
            </div>
        </div>

        <form action="{{ route('course.exam.submit', $course->course_id) }}" method="POST" id="exam-form">
            @csrf

            @foreach($questions as $index => $question)
                {{-- การ์ดโจทย์ข้อสอบ: จะซ่อนไว้ด้วยเทคนิค display: none ยกเว้นข้อแรก --}}
                <div class="card quiz-card shadow-sm mb-4" id="question-card-{{ $index }}" style="display: {{ $index === 0 ? 'block' : 'none' }}; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">

                    <div class="card-header" style="background-color: #f8fafc; padding: 25px 30px; border-bottom: 1px solid #e2e8f0;">
                        <h4 style="font-size: 20px; font-weight: 600; color: #1e293b; margin: 0; line-height: 1.5;">
                            {{ $index + 1 }}. {{ $question->ques_detail }}
                        </h4>
                    </div>

                    <div class="card-body" style="padding: 30px; background-color: #ffffff;">
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            @foreach($question->choices as $choice)
                                <label class="choice-container" style="display: flex; align-items: center; background-color: #f1f5f9; border: 2px solid #e2e8f0; padding: 16px 20px; border-radius: 10px; cursor: pointer; transition: all 0.2s ease; margin: 0;">
                                    <input type="radio" name="answers[{{ $question->ques_id }}]" value="{{ $choice->choice_id }}" class="choice-radio" data-question-index="{{ $index }}" style="width: 20px; height: 20px; margin-right: 15px; accent-color: #1F7BCC;" required>
                                    <span style="font-size: 17px; color: #334155; font-weight: 500;">{{ $choice->choice_detail }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between" style="background-color: #ffffff; border-top: 1px solid #e2e8f0; padding: 15px 30px;">
                        <button type="button" class="btn btn-secondary btn-prev" data-index="{{ $index }}" style="font-weight: bold; padding: 8px 25px; border-radius: 8px;" {{ $index === 0 ? 'disabled' : '' }}>
                            <i class="fas fa-chevron-left mr-2"></i>ข้อก่อนหน้า
                        </button>

                        @if($index === $questions->count() - 1)
                            {{-- ข้อสุดท้ายเปลี่ยนเป็นปุ่มส่งคำตอบ --}}
                            <button type="submit" class="btn btn-success" style="font-weight: bold; padding: 8px 35px; border-radius: 8px; background-color: #28a745;">
                                ส่งคำตอบทั้งหมด <i class="fas fa-paper-plane ml-2"></i>
                            </button>
                        @else
                            <button type="button" class="btn btn-primary btn-next" data-index="{{ $index }}" style="font-weight: bold; padding: 8px 25px; border-radius: 8px; background-color: #1F7BCC;">
                                ข้อถัดไป <i class="fas fa-chevron-right ml-2"></i>
                            </button>
                        @endif
                    </div>

                </div>
            @endforeach
        </form>
    </div>

    <style>
        .choice-container:hover {
            background-color: #e2e8f0 !important;
            border-color: #cbd5e1 !important;
        }
        /* เมื่อช้อยส์ถูกเลือก บังคับเปลี่ยนสีเป็นธีมสีฟ้าทันที */
        .choice-container.selected {
            background-color: #e0f2fe !important;
            border-color: #1F7BCC !important;
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalQuestions = {{ $questions->count() }};
        const radios = document.querySelectorAll('.choice-radio');
        const nextButtons = document.querySelectorAll('.btn-next');
        const prevButtons = document.querySelectorAll('.btn-prev');

        // 1. ไฮไลท์สีช้อยส์ที่เลือก + สั่งเลื่อนข้อถัดไปอัตโนมัติด้วยดีเลย์นิดๆ เพื่อให้เห็นเอฟเฟกต์การเลือก
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                const currentIdx = parseInt(this.getAttribute('data-question-index'));

                // ลบ class selected ออกจากกลุ่มช้อยส์ในข้อเดียวกันก่อน
                const parentCard = document.getElementById(`question-card-${currentIdx}`);
                parentCard.querySelectorAll('.choice-container').forEach(el => el.classList.remove('selected'));

                // เพิ่มสีให้ข้อที่เลือก
                this.parentElement.classList.add('selected');

                // หน่วงเวลา 400ms เพื่อความสมูท แล้วเลื่อนไปข้อถัดไปเองอัตโนมัติ (ยกเว้นข้อสุดท้าย)
                if (currentIdx < totalQuestions - 1) {
                    setTimeout(() => {
                        navigateToQuestion(currentIdx, currentIdx + 1);
                    }, 400);
                }
            });
        });

        // 2. ควบคุมปุ่มกดด้วยมือ (Next / Prev)
        nextButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const currentIdx = parseInt(this.getAttribute('data-index'));
                navigateToQuestion(currentIdx, currentIdx + 1);
            });
        });

        prevButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const currentIdx = parseInt(this.getAttribute('data-index'));
                navigateToQuestion(currentIdx, currentIdx - 1);
            });
        });

        // ฟังก์ชันสลับการ์ดข้อสอบ
        function navigateToQuestion(fromIdx, toIdx) {
            document.getElementById(`question-card-${fromIdx}`).style.display = 'none';
            document.getElementById(`question-card-${toIdx}`).style.display = 'block';

            // อัปเดตตัวเลขข้อสอบด้านบน
            document.getElementById('current-index-text').innerText = toIdx + 1;
        }
    });
    </script>
@endsection
