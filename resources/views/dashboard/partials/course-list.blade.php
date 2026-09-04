@php
    $statusLabels = [
        'completed'  => 'หลักสูตรที่เรียนจบแล้ว',
        'inProgress' => 'หลักสูตรที่กำลังเรียน',
        'notStarted' => 'หลักสูตรที่ยังไม่เริ่มเรียน',
        'failed'     => 'หลักสูตรที่ต้องสอบซ่อม',
    ];
@endphp

<div class="course-list-modal-header">
    <h5 class="course-list-modal-title">{{ $statusLabels[$status] ?? 'รายการหลักสูตร' }}</h5>
    <span class="course-list-modal-count">ทั้งหมด {{ $courseList->total() }} รายการ</span>
</div>

@if ($courseList->isEmpty())
    <div class="course-list-empty">ไม่มีรายการ</div>
@else
    <ul class="course-list">
        @foreach ($courseList as $item)
            <li class="course-list__item">
                <div class="course-list__row">
                    <div class="course-list__title" title="{{ $item['course_title'] }}">{{ $item['course_title'] }}</div>

                    @if ($status !== 'completed')
                        <a href="{{ route('course', ['course_id' => $item['course_id']]) }}" class="course-list__action">ไปหน้าหลักสูตร</a>
                    @endif
                </div>

                <div class="course-list__meta">
                    <span>หมดอายุ: {{ $item['deadline_text'] }}</span>

                    @if (in_array($status, ['inProgress', 'notStarted']))
                        <span>ความคืบหน้า {{ $item['percent'] }}%</span>
                    @elseif ($status === 'failed' && $item['score_total'])
                        <span>คะแนน {{ $item['score_number'] }}/{{ $item['score_total'] }}</span>
                    @endif
                </div>
            </li>
        @endforeach
    </ul>

    @if ($courseList->lastPage() > 1)
        <div class="course-list-pagination">
            <button
                type="button"
                class="course-list-pagination__btn"
                data-page="{{ $courseList->currentPage() - 1 }}"
                {{ $courseList->onFirstPage() ? 'disabled' : '' }}
            >‹ ก่อนหน้า</button>

            <span class="course-list-pagination__info">
                หน้า {{ $courseList->currentPage() }} / {{ $courseList->lastPage() }}
            </span>

            <button
                type="button"
                class="course-list-pagination__btn"
                data-page="{{ $courseList->currentPage() + 1 }}"
                {{ $courseList->currentPage() >= $courseList->lastPage() ? 'disabled' : '' }}
            >ถัดไป ›</button>
        </div>
    @endif
@endif
