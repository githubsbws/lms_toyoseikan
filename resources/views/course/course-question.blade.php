@extends('layout/mainlayout')
@section('title', 'Course')
@section('content')
    <div class="bs-example">
        <style>
            .quiz {
                list-style-type: none;
                margin-bottom: 40px;
            }

            .quiz li {
                float: left;
                margin-right: 20px;
            }

            .head-quiz {
                padding-left: 20px;
                padding-right: 20px;
            }

            thead {
                background-color: #94CFFF;
            }

            .mb-quiz {
                margin-bottom: 10px;
            }

            .form-control {
                border: 1px solid #D1D1D1;
            }

            .radio label::before {
                border: 1px solid #4193D0;
            }

            .checkbox label::before {
                border: 1px solid #4193D0;
            }

            .ml-15 {
                margin-left: 15px;
            }

            .mb-10 {
                margin-bottom: 15px;
                ;
            }

            .span5 {
                width: 500px;
            }

            label {
                font-weight: normal;
            }
        </style>

        <div class="parallax bg-white page-section">
            <div class="container parallax-layer" data-opacity="true">
                <div class="media v-middle">
                    <div class="media-body">
                        <h1 class="text-display-2 margin-none">แบบทดสอบ</h1>

                        <!--<p class="text-light lead">แบบประเมินผลการอบรม</p>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="text-center bg-transparent margin-none">
            </div>
            <div class="page-section">
                <div class="panel panel-default head-quiz">

            </div>
        </div>
    </div>
@endsection
