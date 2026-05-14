@if($data)

<div class="container-fluid">

    {{-- Header --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <h4 class="font-weight-bold text-primary mb-2">
                {{ $data->course_title }}
            </h4>

            <div class="text-muted">
                ผู้เข้าสอบ :
                <strong>
                    {{ $data->firstname }}
                    {{ $data->lastname }}
                </strong>
            </div>

        </div>
    </div>

    {{-- Question --}}
    <div class="card shadow-sm border-0 mb-4">
        <input type="hidden"
            id="exam_course_id"
            value="{{ $data->course_id }}">

        <input type="hidden"
            id="exam_user_id"
            value="{{ $data->user_id }}">

        <div class="card-header bg-info text-white">
            <i class="fas fa-question-circle"></i>
            คำถาม
        </div>

        <div class="card-body">

            <div class="p-3 rounded bg-light border">
                {!! strip_tags(html_entity_decode($data->ques_title), '<b><strong><i><em><u>') !!}
            </div>

        </div>

    </div>

    {{-- Correct Answer --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-success text-white">
            <i class="fas fa-check-circle"></i>
            เฉลย
        </div>

        <div class="card-body">

            <div class="p-4 rounded border bg-light"
                 style="min-height:180px; font-size:15px; line-height:1.8;">
                {!! strip_tags(html_entity_decode($data->answer), '<b><strong><i><em><u>') !!}
            </div>

        </div>

    </div>

    {{-- User Answer --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-warning">
            <i class="fas fa-user-edit"></i>
            คำตอบของผู้เข้าสอบ
        </div>

        <div class="card-body">

            <div class="p-4 rounded border bg-light"
                 style="min-height:180px; font-size:15px; line-height:1.8;">
                {!! strip_tags(html_entity_decode($data->answer_text), '<b><strong><i><em><u>') !!}

            </div>

        </div>

    </div>

    {{-- Score --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-primary text-white">
            <i class="fas fa-star"></i>
            ให้คะแนน
        </div>

        <div class="card-body">

    <div class="row align-items-end">

        {{-- คะแนน --}}
        <div class="col-md-8">

            <label class="font-weight-bold mb-2">
                <i class="fas fa-star text-warning"></i>
                คะแนน
            </label>

            <div class="input-group">

                <input type="number"
                       name="score"
                       class="form-control form-control-lg score-input"
                       max="{{ $data->exam_weight }}"
                       data-max="{{ $data->exam_weight }}"
                       min="0"
                       placeholder="กรอกคะแนน">

            </div>

            <small class="text-muted mt-2 d-block">
                คะแนนเต็ม {{ $data->exam_weight }} คะแนน
            </small>

        </div>

    </div>

</div>

    </div>

    {{-- Button --}}
    <div class="text-right mt-4">

        <button type="button"
                id="saveExamScore"
                class="btn btn-primary btn-lg px-4">

            <i class="fas fa-paper-plane"></i>
            ส่งคำตอบ

        </button>

    </div>

</div>

@endif
