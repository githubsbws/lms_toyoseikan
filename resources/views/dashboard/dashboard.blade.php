@extends('layout/mainlayout')
@section('title', 'Dashboard')
@section('content')
<?php

use App\Models\Lesson;
use App\Models\Learn;
use App\Models\Score;
use App\Models\Ques_ans;
?>

<style>
    .row-eq-height {
        display: flex;
        flex-wrap: wrap;
    }

    @media (width < 768px) {
        .row-eq-height {
            display: block;
        }
    }

    .row-eq-height>[class*='col-'] {
        display: flex;
    }

    .card {
        border-radius: 5px;
        padding-block: 10px;
        padding-inline: 20px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1), 0 2px 4px 0 rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 100%;
    }

    .card>.card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;


        h5 {
            margin: 0;
            font-weight: bold;
        }

        @media (width<=320px) {
            h5 {
                font-size: smaller;
            }
        }

        a {
            color: #337ab7;
        }
    }

    .card>.card-footer {
        margin-top: auto;
        display: flex;
        justify-content: center;
        align-items: center;

        a {
            color: #337ab7;
        }

        a.btn-footer-blue {
            border: 1px solid #1d71b8;
            background-color: #ffffff;
            color: #1d71b8;
            text-align: center;
            padding: 8px 20px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.2s;
            justify-content: flex-start;
        }

        a.btn-footer-blue:hover {
            background-color: #f4f9fd;
        }
    }

    .card>.card-body {
        margin-bottom: 10px;
        font-size: smaller;
        height: auto !important;
        border: none !important;
        text-align: start;
    }

    .row>div {
        margin-bottom: 20px;
    }

    section {
        margin-bottom: 20px;
    }

    @media (width < 376px) {

        .section-3 .course-row-responsive {
            flex-direction: column !important;
            text-align: center;
            gap: 5px;
        }

        .section-3 .course-row-responsive>div {
            width: 90% !important;
        }

        .section-3 .course-row-responsive .progress {
            width: 80% !important;
        }
    }

    @media (width < 768px) {
        .card {
            padding-inline: 10px;
        }

        .progress-split-layout {
            flex-direction: column;
        }

        .progress-circle-block {
            margin-top: 0 !important;
        }

        .progress-circle-svg {
            position: absolute;
            left: -25%;
        }

        .progress-circle-text {
            top: 80% !important;
            left: 55% !important;
        }
    }

    @media (width < 1440px) {
        .section-3 .course-row-responsive .progress {
            width: 60% !important;
        }
    }

    /* --- CSS เฉพาะส่วนงานของ Section 1 (Flexbox) --- */
    .section-1 {

        /* สร้างคลาสแบ่ง 5 คอลัมน์ สำหรับหน้าจอขนาดใหญ่ */
        @media (min-width: 1200px) {
            .custom-5-col {
                width: 20%;
            }
        }

        .card-stat {
            flex-direction: row;
            align-items: flex-start;
            padding: 16px;
            min-height: 105px;
            position: relative;
            gap: 12px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 13px;

        }

        .stat-content {
            display: flex;
            flex-direction: column;
            flex: 1;
            margin-left: 15px;
        }

        .stat-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .stat-value-row {
            display: flex;
            align-items: baseline;
            gap: 15px;
        }

        .stat-qty {
            font-size: 28px;
            font-weight: bold;
            color: #1d71b8;
            line-height: 1;
        }

        .stat-qty2 {
            font-size: 28px;
            font-weight: bold;
            color: #2e7d32;
            line-height: 1;
        }

        .stat-qty3 {
            font-size: 28px;
            font-weight: bold;
            color: #f59e0b;
            line-height: 1;
        }

        .stat-qty4 {
            font-size: 28px;
            font-weight: bold;
            color: #7b1fa2;
            line-height: 1;
        }


        .stat-qty5 {
            font-size: 28px;
            font-weight: bold;
            color: #d32f2f;
            line-height: 1;
        }

        .stat-unit {
            font-size: 15px;
            color: #333333;
            font-weight: 600;

        }

        .stat-footer-text {
            font-size: 12px;
            color: #888888;
            margin-top: 5px;
            font-weight: 600;
        }

        .stat-badge {
            position: absolute;
            bottom: 16px;
            right: 20px;
            font-size: 15px;
            font-weight: bold;
            padding: 2px 10px;
            border-radius: 12px;
        }

        /* ชุดสี (Colors) ซ้อนอยู่ด้านใน Section 1 */
        .color-blue {
            .stat-icon {
                background-color: #f0f7ff;
                color: #1d71b8;
                width: 60px;
                height: 60px;
            }

            .stat-title {
                color: #1d71b8;
                font-size: 20px;
            }
        }

        .color-green {
            .stat-icon {
                background-color: #e8f5e9;
                color: #2e7d32;
                width: 60px;
                height: 60px;
            }

            .stat-title {
                color: #2e7d32;
                font-size: 20px;
            }

            .stat-badge {
                background-color: #e8f5e9;
                color: #2e7d32;
            }
        }

        .color-yellow {
            .stat-icon {
                background-color: #fff8e1;
                color: #f59e0b;
                width: 60px;
                height: 60px;
            }

            .stat-title {
                color: #f59e0b;
            }

            .stat-badge {
                background-color: #fff8e1;
                color: #f59e0b;
            }
        }

        .color-purple {
            .stat-icon {
                background-color: #f3e5f5;
                color: #7b1fa2;
                width: 60px;
                height: 60px;
            }

            .stat-title {
                color: #7b1fa2;
            }

            .stat-badge {
                background-color: #f3e5f5;
                color: #7b1fa2;
            }
        }

        .color-red {
            .stat-icon {
                background-color: #ffebee;
                color: #d32f2f;
                width: 60px;
                height: 60px;
            }

            .stat-title {
                color: #d32f2f;
            }

            .stat-badge {
                background-color: #ffebee;
                color: #d32f2f;
            }
        }
    }

    .section-2 {

        .progress-split-layout {
            display: flex;
            align-items: center;
            justify-content: flex-start;

            gap: 120px;
        }

        .progress-split-left {
            width: 130px;
            flex-shrink: 0;

        }

        .progress-split-right {
            flex: 1;
        }

        /* 2. SVG กราฟวงกลม */
        .progress-circle-block {
            position: relative;
            width: 130px;
            height: 130px;
            margin: 0 auto;
            margin-top: -40px;
        }

        .progress-circle-svg {
            transform: rotate(-90deg);
            width: 155%;
            height: 155%;
        }

        .progress-circle-bg {
            fill: none;
            stroke: #eef2f5;
            stroke-width: 8;
        }

        .progress-circle-value {
            fill: none;
            stroke: #2ecc71;
            stroke-width: 8;
            stroke-linecap: round;
        }

        .progress-circle-text {
            position: absolute;
            top: 80%;
            left: 80%;
            transform: translate(-50%, -50%);
            text-align: center;
            width: 100%;
        }

        .progress-circle-pct {
            display: block;
            font-size: 42px;
            font-weight: bold;
            color: #111111;
            line-height: 1;
        }

        .progress-circle-label {
            display: block;
            font-size: 12px;
            color: #141414;
            margin-top: 4px;
            font-weight: 600
        }

        /* 3. รายการสเตตัสฝั่งขวา */
        .progress-status-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 28px;
            margin-top: 25px;
        }

        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 15px;
        }

        .status-dot-title {
            display: flex;
            align-items: center;
            color: #111111;
            font-weight: 600;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 10px;
            display: inline-block;
        }

        .status-dot.green {
            background-color: #2ecc71;
        }

        .status-dot.orange {
            background-color: #f39c12;
        }

        .status-dot.grey {
            background-color: #e0e0e0;
        }

        .status-result-badge {
            font-size: 12px;
            color: #666;
            text-align: center;
            display: flex;
        }

        .status-result-badge .bold-text {
            font-weight: bold;
            font-size: 16px;
            width: 45px;
            text-align: right;
            margin-right: 18px;
            margin-left: 0;
        }

        .text-green {
            color: #2ecc71;
        }

        .status-result-badge>span:last-child {
            width: 65px;
            text-align: left;
        }

        .text-orange {
            color: #f39c12;
        }

        .text-grey {
            color: #999999;
        }


        /* --- แผนการเรียนของฉัน (หลอดแนวนอน) --- */
        .linear-progress-group {
            display: flex;
            flex-direction: column;
            gap: 28px;
            margin-top: 15px;
        }

        .linear-row-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .linear-title-area {
            display: flex;
            align-items: center;
            gap: 15px;
            width: 200px;
            flex-shrink: 0;
        }

        .linear-title-area i {
            font-size: 20px;
            width: 16px;
            text-align: center;
        }

        .linear-title-area span {
            font-size: 15px;
            font-weight: 600;
            color: #111;
        }

        .linear-bar-wrapper {
            flex: 1;
            margin: 0 20px;
            height: 6px;
            background-color: #edf2f6;
            border-radius: 10px;
            overflow: hidden;
        }

        .linear-bar-fill {
            height: 100%;
            border-radius: 10px;
        }

        .linear-qty-area {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            width: 150px;
            flex-shrink: 0;
        }

        .linear-qty-area .pct {
            width: 35px;
            text-align: right;
            font-size: 14px;
            font-weight: 600;
            color: #0f0f0f;
        }

        .linear-qty-area .fraction {
            width: 100px;
            text-align: left;
            font-size: 14px;
            font-weight: 600;
            color: #5c5c61;
            margin-left: 20px;
            white-space: nowrap;
        }

        .icon-box {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .icon-blue {
            background: #eef6ff;
            color: #1d71b8;
        }

        .icon-green {
            background: #eaf8ee;
            color: #2ecc71;
        }

        .icon-orange {
            background: #fff8e8;
            color: #f39c12;
        }

        .icon-box i {
            margin-top: 2px;
            margin-left: -5px;
        }


        /* 3. การ์ดที่ 3: รายการบทเรียนกำหนดเวลา */
        .lesson-flex-list {
            display: flex;
            flex-direction: column;
            gap: 13px;
            margin-top: 10px;
        }

        .lesson-row-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            border-bottom: 1px dashed #eef2f5;
        }

        .lesson-row-card:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .lesson-info-left {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .lesson-info-left i {
            font-size: 18px;
            color: #1d71b8;
            margin-top: 5px;
            margin-left: 1px;
        }

        .lesson-name-sub {
            display: flex;
            flex-direction: column;
        }

        .lesson-main-name {
            font-size: 14px;
            font-weight: 600;
            color: #111;
            margin-bottom: 2px;
        }

        .lesson-deadline-date {
            font-size: 13px;
            font-weight: bold;
            color: #ff3b30;
            text-align: right;
        }

        .lesson-deadline-date span {
            display: block;
            font-size: 11px;
            color: #ff3b30;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .lesson-sub-name {
            font-weight: 600;
            text-align: start;
        }
    }
</style>

<style>
    .section-1 {
        @media (min-width: 1200px) {
            .custom-5-col {
                width: 20%;
            }
        }

        .card-stat {
            flex-direction: row;
            align-items: flex-start;
            padding: 16px;
            min-height: 105px;
            position: relative;
            gap: 12px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 13px;
        }

        .stat-content {
            display: flex;
            flex-direction: column;
            flex: 1;
            margin-left: 14px;
            row-gap: 6px;
        }

        .stat-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .stat-value-row {
            display: flex;
            align-items: baseline;
            gap: 15px;
        }

        .stat-qty {
            font-size: 28px;
            font-weight: bold;
            color: #1d71b8;
            line-height: 1;
        }

        .stat-qty2 {
            font-size: 28px;
            font-weight: bold;
            color: #2e7d32;
            line-height: 1;
        }

        .stat-qty3 {
            font-size: 28px;
            font-weight: bold;
            color: #f59e0b;
            line-height: 1;
        }

        .stat-qty4 {
            font-size: 28px;
            font-weight: bold;
            color: #7b1fa2;
            line-height: 1;
        }

        .stat-qty5 {
            font-size: 28px;
            font-weight: bold;
            color: #d32f2f;
            line-height: 1;
        }

        .stat-unit {
            font-size: 15px;
            color: #333333;
            font-weight: 600;
            font-size: 18px;
        }

        .stat-footer-text {
            font-size: 12px;
            color: #888888;
            margin-top: 5px;
            font-weight: 600;
        }

        .stat-badge {
            position: absolute;
            bottom: 16px;
            right: 20px;
            font-size: 15px;
            font-weight: bold;
            padding: 2px 10px;
            border-radius: 12px;
        }

        .color-blue {
            .stat-icon {
                background-color: #f0f7ff;
                color: #1d71b8;
                width: 60px;
                height: 60px;
            }

            .stat-title {
                color: #1d71b8;
            }
        }

        .color-green {
            .stat-icon {
                background-color: #e8f5e9;
                color: #2e7d32;
                width: 60px;
                height: 60px;
            }

            .stat-title {
                color: #2e7d32;
            }

            .stat-badge {
                background-color: #e8f5e9;
                color: #2e7d32;
            }
        }

        .color-yellow {
            .stat-icon {
                background-color: #fff8e1;
                color: #f59e0b;
                width: 60px;
                height: 60px;
            }

            .stat-title {
                color: #f59e0b;
                font-size: 20px;
            }

            .stat-badge {
                background-color: #fff8e1;
                color: #f59e0b;
            }
        }

        .color-purple {
            .stat-icon {
                background-color: #f3e5f5;
                color: #7b1fa2;
                width: 60px;
                height: 60px;
            }

            .stat-title {
                color: #7b1fa2;
                font-size: 20px;
            }

            .stat-badge {
                background-color: #f3e5f5;
                color: #7b1fa2;
            }
        }

        .color-red {
            .stat-icon {
                background-color: #ffebee;
                color: #d32f2f;
                width: 60px;
                height: 60px;
            }

            .stat-title {
                color: #d32f2f;
                font-size: 20px;
            }

            .stat-badge {
                background-color: #ffebee;
                color: #d32f2f;
            }
        }


    }

    .section-2 {
        .progress-split-layout {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 120px;
        }

        .progress-split-left {
            width: 130px;
            flex-shrink: 0;
        }

        .progress-split-right {
            flex: 1;
        }

        .progress-circle-block {
            position: relative;
            width: 130px;
            height: 130px;
            margin: 0 auto;
            
        }

        .progress-circle-svg {
            transform: rotate(-90deg);
            width: 155%;
            height: 155%;
        }

        .progress-circle-bg {
            fill: none;
            stroke: #eef2f5;
            stroke-width: 8;
        }

        .progress-circle-value {
            fill: none;
            stroke: #2ecc71;
            stroke-width: 8;
            stroke-linecap: round;
        }

        .progress-circle-text {
            position: absolute;
            top: 80%;
            left: 80%;
            transform: translate(-50%, -50%);
            text-align: center;
            width: 100%;
        }

        .progress-circle-pct {
            display: block;
            font-size: 42px;
            font-weight: bold;
            color: #111111;
            line-height: 1;
        }

        .progress-circle-label {
            display: block;
            font-size: 12px;
            color: #141414;
            margin-top: 4px;
            font-weight: 600
        }

        .progress-status-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 28px;
            margin-top: 25px;
        }

        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 15px;
        }

        .status-dot-title {
            display: flex;
            align-items: center;
            color: #111111;
            font-weight: 600;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 10px;
            display: inline-block;
        }

        .status-dot.green {
            background-color: #2ecc71;
        }

        .status-dot.orange {
            background-color: #f39c12;
        }

        .status-dot.grey {
            background-color: #e0e0e0;
        }

        .status-result-badge {
            font-size: 12px;
            color: #666;
            text-align: center;
            display: flex;
        }

        .status-result-badge .bold-text {
            font-weight: bold;
            font-size: 16px;
            width: 45px;
            text-align: right;
            margin-right: 18px;
            margin-left: 0;
        }

        .status-result-badge>span:last-child {
            width: 65px;
            text-align: left;
        }

        .text-green {
            color: #2ecc71;
        }

        .text-orange {
            color: #f39c12;
        }

        .text-grey {
            color: #999999;
        }

        .linear-progress-group {
            display: flex;
            flex-direction: column;
            gap: 28px;
            margin-top: 15px;
        }

        .linear-row-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .linear-title-area {
            display: flex;
            align-items: center;
            gap: 15px;
            width: 200px;
            flex-shrink: 0;
        }

        .linear-title-area i {
            font-size: 20px;
            width: 16px;
            text-align: center;
        }

        .linear-title-area span {
            font-size: 15px;
            font-weight: 600;
            color: #111;
        }

        .linear-bar-wrapper {
            flex: 1;
            margin: 0 20px;
            height: 6px;
            background-color: #edf2f6;
            border-radius: 10px;
            overflow: hidden;
        }

        .linear-bar-fill {
            height: 100%;
            border-radius: 10px;
        }

        .linear-qty-area {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            width: 150px;
            flex-shrink: 0;
        }

        .linear-qty-area .pct {
            width: 35px;
            text-align: right;
            font-size: 14px;
            font-weight: 600;
            color: #0f0f0f;
        }

        .linear-qty-area .fraction {
            width: 100px;
            text-align: left;
            font-size: 14px;
            font-weight: 600;
            color: #5c5c61;
            margin-left: 20px;
            white-space: nowrap;
        }

        .icon-box {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .icon-blue {
            background: #eef6ff;
            color: #1d71b8;
        }

        .icon-green {
            background: #eaf8ee;
            color: #2ecc71;
        }

        .icon-orange {
            background: #fff8e8;
            color: #f39c12;
        }

        .icon-box i {
            margin-top: 2px;
            margin-left: -5px;
        }

        .lesson-flex-list {
            display: flex;
            flex-direction: column;
            gap: 13px;
            margin-top: 10px;
        }

        .lesson-row-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            border-bottom: 1px dashed #eef2f5;
        }

        .lesson-row-card:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .lesson-info-left {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .lesson-info-left i {
            font-size: 18px;
            color: #1d71b8;
            margin-top: 5px;
            margin-left: 1px;
        }

        .lesson-name-sub {
            display: flex;
            flex-direction: column;
        }

        .lesson-main-name {
            font-size: 14px;
            font-weight: 600;
            color: #111;
            margin-bottom: 2px;
        }

        .lesson-deadline-date {
            font-size: 13px;
            font-weight: bold;
            color: #ff3b30;
            text-align: right;
        }

        .lesson-deadline-date span {
            display: block;
            font-size: 11px;
            color: #ff3b30;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .lesson-sub-name {
            font-weight: 600;
        }

        .probation-timeline {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            padding-top: 20px;
            margin-bottom: 20px;
        }

        .probation-timeline::before {
            content: '';
            position: absolute;
            top: 52px;
            left: 10%;
            right: 10%;
            height: 4px;
            background-color: #eef2f5;
            z-index: 1;
        }

        .timeline-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 2;
            width: 50%;
        }

        .circle-badge {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 13px;
            font-weight: bold;
            color: white;
            margin-bottom: 25px;
            position: relative;
            background-color: #f1f4f8;
            color: #555;
            border: 2px solid white;
        }

        .circle-badge::after {
            content: '';
            position: absolute;
            bottom: -14px;
            width: 2px;
            height: 14px;
            background-color: inherit;
        }

        .circle-badge::before {
            content: '';
            position: absolute;
            bottom: -14px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: inherit;
        }

        .circle-badge.green {
            background-color: #2ecc71;
            color: white;
        }

        .circle-badge.yellow {
            background-color: #fbd671;
            color: white;
        }

        .step-score {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 0px;
        }

        .step-pct {
            font-size: 16px;
            font-weight: bold;
        }

        .step-empty {
            font-size: 12px;
            color: #403f46;
            margin-top: 6px;
            font-weight: 600;
        }

        .text-green {
            color: #2ecc71;
        }

        .text-yellow {
            color: #f39c12;
        }

        .probation-alert {
            background-color: #eaf8ee;
            border-radius: 8px;
            padding: 12px 15px;
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-top: auto;
            border: 2px solid #dbeee1;
        }

        .probation-alert i {
            color: #2ecc71;
            font-size: 20px;
            margin-top: 13px;
        }

        .probation-alert-text {
            display: flex;
            flex-direction: column;
            font-size: 15px;
            color: #333;
            font-weight: 600;
        }


    }

    .section-3 {
        .badge-danger-circle {
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 12px;
            margin-left: 8px;
            vertical-align: middle;
        }

        .course-row-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 15px;
            margin-bottom: 15px;
            background-color: #ffffff;
            border-radius: 0 8px 8px 0;
        }

        .course-row-item.border-blue {
            border-left: 4px solid #1d71b8;
        }

        .course-row-item.border-red {
            border-left: 4px solid #e74c3c;
        }

        .course-info {
            width: 50%;
        }

        .course-info h5 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            color: #111;
        }

        .course-info span {
            font-size: 12px;
            color: #777;
            font-weight: 700;
        }

        .course-progress-area {
            width: 30%;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .mini-progress {
            flex: 1;
            height: 6px;
            background-color: #edf2f6;
            border-radius: 10px;
            overflow: hidden;
        }

        .mini-progress-bar {
            height: 100%;
            border-radius: 10px;
            background-color: #edf2f6;
        }

        .mini-progress-bar.blue {
            background-color: #1d71b8;
        }

        .progress-text {
            font-size: 13px;
            font-weight: 700;
            color: #111;
            width: 35px;
            text-align: left;
        }

        .btn-outline-blue-sm {
            background-color: #1d53b8;
            color: white;
            border-radius: 7px;
            padding: 8px 20px;
            font-size: 13px;
            text-decoration: none;
        }

        .btn-outline-red-sm {
            background-color: transparent;
            color: #e74c3c;
            border: 1px solid #e74c3c;
            border-radius: 6px;
            padding: 4px 20px;
            font-size: 13px;
            text-decoration: none;
        }

        .eval-row-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .eval-info {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .eval-info i {
            margin-top: 4px;
            font-size: 20px;
        }

        .eval-info .text-group h5 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: #111;
        }

        .eval-info .text-group span {
            display: block;
            font-size: 13px;
            color: #777;
            margin-top: 2px;
            font-weight: 700;
        }

        .eval-score-box {
            background-color: #fafbfc;
            padding: 10px 15px;
            border-radius: 8px;
            text-align: center;
            min-width: 80px;
        }

        .eval-score-box span {
            font-size: 11px;
            color: #555;
        }

        .eval-score-box h4 {
            margin: 2px 0 0 0;
            font-size: 18px;
            font-weight: 800;
        }

        .text-green {
            color: #2ecc71 !important;
        }

        .text-red {
            color: #e74c3c !important;
        }

        .card-footer {
            background-color: transparent;
            border-top: none;
            padding: 15px 0 0 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-footer a {
            display: inline-block;
            background-color: #ffffff;
            color: #1d71b8;
            font-size: 15px;
            font-weight: 600;
        }
    }

    .section-4 {
        .announce-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .announce-info {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 75%;
        }

        .announce-info i {
            color: #1d71b8;
            font-size: 18px;
        }

        .announce-info span {
            font-size: 14px;
            color: #111;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .announce-date {
            font-size: 13px;
            color: #777;
            white-space: nowrap;
        }

        .history-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .history-info {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 45%;
        }

        .history-info i {
            font-size: 18px;
        }

        .history-info h5 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            color: #111;
        }

        .history-status {
            font-size: 13px;
            color: #777;
            width: 15%;
            text-align: center;
        }

        .history-date {
            font-size: 13px;
            color: #777;
            width: 25%;
            text-align: center;
        }

        .history-score {
            font-size: 15px;
            font-weight: 700;
            color: #111;
            width: 15%;
            text-align: right;
        }

        .text-yellow {
            color: #f39c12;
        }

        .contact-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .contact-info {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 50%;
        }

        .contact-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .contact-text span {
            display: block;
            font-size: 11px;
            color: #777;
        }

        .contact-text h5 {
            margin: 2px 0 0 0;
            font-size: 14px;
            font-weight: 700;
            color: #111;
        }

        .contact-tel {
            font-size: 13px;
            color: #333;
            font-weight: 500;
        }

        .contact-icon i {
            font-size: 20px;
            color: #1d71b8;
            cursor: pointer;
        }
    }

    .breadcrumb {
        padding: 0;
    }

    .new-employ .group {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .new-employ .wrap {
        display: flex;
        flex-direction: column;
        border: 1px solid #d3d3d3;
        padding: 12px;
        border-radius: 8px;

        div:first-child {
            display: flex;
            column-gap: 18px;
        }

        div:last-child {
            display: flex;
            column-gap: 36px;
        }

        p {
            margin: 0;
        }
    }

    h5 {
        font-size: 20px;
    }

    .badge {
        color: #fff;
    }
</style>

<body>

    <!-- Normal employ -->
    <div class="main-content">
        <div class="container-fluid p-5" id="normal-employ">

            @if($dashboard['isNewEmployee'])

                <div class="new-employ">

                    <div class="group">

                        <div>
                            <h3>{{ $dashboard['employee']['name'] }}</h3>

                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">

                                    @foreach($dashboard['employee']['department'] as $item)
                                        <li class="breadcrumb-item">
                                            {{ $item }}
                                        </li>
                                    @endforeach

                                </ol>
                            </nav>
                        </div>


                        <div class="date">

                            <div class="wrap">

                                <div>
                                    <p>วันที่เริ่มงาน</p>
                                    <p>
                                        {{ Carbon\Carbon::parse($dashboard['employee']['user']->work_start)
                                            ->translatedFormat('d M Y') }}
                                    </p>
                                </div>


                                <div>
                                    <p>อายุงาน</p>
                                    <p>
                                        {{ $dashboard['employee']['serviceAge'] }}
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


            @else


                <div class="normal-employ">

                    <h3>{{ $dashboard['employee']['name'] }}</h3>


                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb">

                            @foreach($dashboard['employee']['department'] as $item)

                                <li class="breadcrumb-item">
                                    {{ $item }}
                                </li>

                            @endforeach

                        </ol>

                    </nav>

                </div>


            @endif
            

            <section class="section-1 row">
                <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 custom-5-col">
                    <div class="card card-stat color-blue">
                        <div class="stat-icon"><i class="fa-solid fa-book-open"></i></div>
                        <div class="stat-content">
                            <div class="stat-title">ทั้งหมด</div>
                            <div class="stat-value-row">
                                <span class="stat-qty">{{ $dashboard['totalCourse'] }}</span>
                                <span class="stat-unit"><a href="{{route('course')}}">ดูบทเรียน</a></span>
                            </div>
                            <div class="stat-footer-text">ที่ต้องเรียนทั้งหมด</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12 custom-5-col">
                    <div class="card card-stat color-green">
                        <div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
                        <div class="stat-content">
                            <div class="stat-title">เรียนจบแล้ว</div>
                            <div class="stat-value-row">
                                <span class="stat-qty2">{{ $dashboard['completed'] }}</span>
                            </div>
                            <div class="stat-unit">
                               <a href="{{route('course')}}"> ดูบทเรียน </a>
                            </div>
                        </div>
                        <span class="stat-badge">{{ $dashboard['completedPercent'] }}%</span>
                    </div>


                </div>

                <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12 custom-5-col">
                    <div class="card card-stat color-yellow">
                        <div class="stat-icon"><i class="fa-regular fa-clock"></i></div>
                        <div class="stat-content">
                            <div class="stat-title">กำลังเรียน</div>
                            <div class="stat-value-row">
                                <span class="stat-qty3">{{ $dashboard['inProgress'] }}</span>
                            </div>
                            <div class="stat-unit">
                                <a href="{{route('course')}}"> ดูบทเรียน </a>
                            </div>
                        </div>
                        <span class="stat-badge">{{ $dashboard['inProgressPercent'] }}%</span>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12 custom-5-col">
                    <div class="card card-stat color-purple">
                        <div class="stat-icon"><i class="fa-solid fa-circle-play"></i></div>
                        <div class="stat-content">
                            <div class="stat-title">ยังไม่เริ่ม</div>
                            <div class="stat-value-row">
                                <span class="stat-qty4">{{ $dashboard['notStarted'] }}</span>
                            </div>
                            <div class="stat-unit">
                                <a href="{{route('course')}}"> ดูบทเรียน </a>
                            </div>
                        </div>
                        <span class="stat-badge">{{ $dashboard['notStartedPercent'] }}%</span>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12 custom-5-col">
                    <div class="card card-stat color-red">
                        <div class="stat-icon"><i class="fa-solid fa-circle-xmark"></i></div>
                        <div class="stat-content">
                            <div class="stat-title">ไม่ผ่าน/ต้องซ่อม</div>
                            <div class="stat-value-row">
                                <span class="stat-qty5">{{ $dashboard['failed'] }}</span>
                            </div>
                            <div class="stat-unit">
                               <a href="{{route('course')}}"> ดูบทเรียน </a>
                            </div>
                        </div>
                        <span class="stat-badge">{{ $dashboard['failedPercent'] }}%</span>
                    </div>
                </div>
            </section>

            <section class="section-2 row row-eq-height">
                <div class="col-lg-5 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>ความก้าวหน้าการเรียนของฉัน</h5>
                        </div>
                        <div class="card-body">
                            <div class="progress-split-layout">
                                <div class="progress-split-left">
                                    <div class="progress-circle-block">
                                        <svg class="progress-circle-svg" viewBox="0 0 100 100">
                                            <circle class="progress-circle-bg" cx="50" cy="50" r="45"></circle>
                                            <circle class="progress-circle-value" cx="50" cy="50" r="45"
                                                stroke-dasharray="282.7" stroke-dashoffset="{{ $dashboard['progressOffset'] }}"></circle>
                                        </svg>
                                        <div class="progress-circle-text">
                                            <span class="progress-circle-pct"> {{ $dashboard['completedPercent'] }}%</span>
                                            <span class="progress-circle-label">ความคืบหน้า</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="progress-split-right">
                                    <ul class="progress-status-list">
                                        @foreach($dashboard['progressByCategory'] as $item)

                                        <li class="status-item">

                                            <div class="status-dot-title">
                                                <span class="status-dot {{ $item['color'] }}"></span>
                                                {{ $item['name'] }}
                                            </div>

                                            <span class="status-result-badge">
                                                <span class="bold-text">{{ $item['percent'] }}%</span>

                                                <span class="text-{{ $item['color'] }}">
                                                    {{ $item['status'] }}
                                                </span>
                                            </span>

                                        </li>

                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($dashboard['isNewEmployee'])
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card h-100" style="min-height: 422px;">
                        <div class="card-header">
                            <h5>สำหรับพนักงานใหม่ <span style="font-size: 14px; font-weight: 400; color: #555;">(ทดลองงาน 120 วัน)</span></h5>
                        </div>
                        <div class="card-body" style="display: flex; flex-direction: column;">

                            <div class="probation-timeline">
                                @foreach($dashboard['newEmployeeTimeline'] as $item)

                                    <div class="timeline-step">

                                        <div class="circle-badge 
                                            {{ $item['percent']==100?'green':'' }}">

                                            {{ $item['day'] }} วัน

                                        </div>


                                        <div>
                                            {{ $item['passed'] }}/{{ $item['total'] }}
                                        </div>

                                        <div>
                                            {{ $item['percent'] }}%
                                        </div>

                                    </div>

                                @endforeach
                            </div>

                            <div class="probation-alert">
                                <i class="fa-solid fa-circle-plus"></i>
                                <div class="probation-alert-text">
                                    <span>คุณอยู่ในช่วง 30 วันแรก (01 ม.ค. 2567 - 30 ม.ค. 2567)</span>
                                    <span>กรุณาเรียนให้ครบตามแผนเพื่อผ่านการประเมินในแต่ละรอบ</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @else
                <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>แผนการเรียนของฉัน <span
                                    style="font-size: 14px; font-weight: 400; color: #555;">(ตามตำแหน่งงาน)</span></h5>
                        </div>
                        <div class="card-body">
                            <div class="linear-progress-group">

                                @foreach($dashboard['learningPlan'] as $item)

                                    <div class="linear-row-item">

                                        <div class="linear-title-area">

                                            <div class="icon-box icon-blue">
                                                <i class="fa-solid fa-book-bookmark"></i>
                                            </div>

                                            <span>{{ $item['title'] }}</span>

                                        </div>

                                        <div class="linear-bar-wrapper">

                                            <div class="linear-bar-fill"
                                                style="width: {{ $item['percent'] }}%;
                                                        background-color: {{ $item['color'] }};">
                                            </div>

                                        </div>

                                        <div class="linear-qty-area">

                                            <span class="pct">
                                                {{ $item['percent'] }}%
                                            </span>

                                            <span class="fraction">
                                                {{ $item['passed'] }}/{{ $item['total'] }} บทเรียน
                                            </span>

                                        </div>

                                    </div>

                                    @endforeach

                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>บทเรียนที่ต้องเรียน / กำหนดเวลา</h5>
                        </div>
                        <div class="card-body">
                            <div class="lesson-flex-list">
                                @forelse($dashboard['deadlineCourses'] as $course)

                                    <div class="lesson-row-card">

                                        <div class="lesson-info-left">

                                            <div class="icon-box icon-blue">
                                                <i class="fa-solid fa-book-open"></i>
                                            </div>

                                            <div class="lesson-name-sub">

                                                <span class="lesson-main-name">
                                                    {{ $course['course_title'] }}
                                                </span>

                                                <span class="lesson-sub-name">
                                                    <a href="{{route('course')}}"> ดูบทเรียน </a>
                                                </span>

                                            </div>

                                        </div>

                                        <div class="lesson-deadline-date">

                                            <span>ครบกำหนด</span>

                                            {{ $course['deadline']
                                                ? \Carbon\Carbon::parse($course['deadline'])->translatedFormat('d M y')
                                                : '-' }}

                                        </div>

                                    </div>

                                    @empty

                                    <div class="text-center text-muted py-3">
                                        ไม่มีบทเรียนที่ต้องเรียน
                                    </div>

                                    @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-3 row row-eq-height">
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>
                                หลักสูตรที่ต้องเรียนต่อ
                                <span class="badge" style="background-color: red; margin-left: 5px;">{{ $dashboard['continueCount'] }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div style="display: flex; flex-direction: column; gap: 5px;">
                                @foreach($dashboard['continueCourses'] as $course)

                                    <div class="course-row-responsive"
                                        style="padding-left:10px;
                                                border-left:5px solid #337ab7;
                                                display:flex;
                                                align-items:center;
                                                justify-content:space-between;">

                                        <div style="width:50%;">

                                            <h5 style="font-weight:bold;">
                                                {{ $course['course_title'] }}
                                            </h5>

                                            <span>
                                                {!! html_entity_decode($course['course_short_title']) !!}
                                            </span>

                                        </div>

                                        <div style="width:25%;
                                                    display:flex;
                                                    align-items:center;
                                                    gap:8px;">

                                            <div class="progress"
                                                style="width:100%;height:10px;margin:0;">

                                                <div class="progress-bar"
                                                    role="progressbar"
                                                    style="width:{{ $course['percent'] }}%;">
                                                </div>

                                            </div>

                                            <span>{{ $course['percent'] }}%</span>

                                        </div>

                                        <div style="width:20%;text-align:center;">

                                            <a href="{{ url('course/'.$course['course_id']) }}"
                                            class="btn btn-primary">

                                                ดูบทเรียน

                                            </a>

                                        </div>

                                    </div>

                                    @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>
                                หลักสูตรที่ไม่ผ่าน / ต้องซ่อม
                                <span class="badge" style="background-color: red; margin-left: 5px;">{{ $dashboard['failCount'] }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div style="display: flex; flex-direction: column; gap: 5px;">
                                @forelse($dashboard['failCourses'] as $course)
                                
                                    <div class="course-row-responsive"
                                        style="padding-left:10px;
                                                border-left:5px solid red;
                                                display:flex;
                                                justify-content:space-between;
                                                align-items:center;">

                                        <div style="width:50%;">

                                            <h5 style="font-weight:bold;">
                                                {{ $course['course_title'] }}
                                            </h5>

                                            <span>
                                                {{ $course['course_short_title'] }}
                                            </span>

                                        </div>

                                        <div style="width:25%;">

                                            <h5>
                                                คะแนน
                                                <span style="font-weight:bold;">
                                                    {{ $course['percent'] }}%
                                                </span>
                                            </h5>

                                        </div>

                                        <div style="width:20%;text-align:center;">

                                            <a href="{{ url('course/'.$course['course_id']) }}"
                                            class="btn"
                                            style="border:1px solid red;color:red;">

                                                ซ่อม

                                            </a>

                                        </div>

                                    </div>

                                    @empty

                                    <div class="text-center text-muted py-3">
                                        ไม่มีหลักสูตรที่ต้องซ่อม
                                    </div>

                                    @endforelse
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>
                                ผลประเมินล่าสุด
                            </h5>
                        </div>
                        <div class="card-body">
                            <div style="display: flex; flex-direction: column; gap: 5px;">
                                <div style="display:flex; flex-direction:column; gap:20px;">
                                    @forelse($dashboard['latestAssessments'] as $item)

                                        <div class="eval-row-item">

                                            <div class="eval-info">

                                                @if($item['pass'])
                                                    <i class="fa-solid fa-circle-check text-green"></i>
                                                @else
                                                    <i class="fa-solid fa-circle-xmark text-red"></i>
                                                @endif


                                                <div class="text-group">

                                                    <h5>
                                                        {{ $item['title'] }}
                                                    </h5>

                                                    <span>
                                                        {{ $item['short_title'] }}
                                                    </span>

                                                    <span>
                                                        วันที่สอบ:
                                                        {{ $item['date'] }}
                                                    </span>

                                                </div>

                                            </div>


                                            <div class="eval-score-box">

                                                <span>
                                                    คะแนน
                                                </span>

                                                @if($item['pass'])

                                                    <h4 class="text-green">
                                                        {{ $item['score'] }}%
                                                    </h4>

                                                @else

                                                    <h4 class="text-red">
                                                        ไม่ผ่าน
                                                    </h4>

                                                @endif

                                            </div>

                                        </div>


                                    @empty

                                        <div class="text-center text-muted py-3">
                                            ไม่มีผลประเมินล่าสุด
                                        </div>

                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-4 row row-eq-height">
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>ประกาศจากบริษัท</h5>
                        </div>
                        <div class="card-body">
                            <div style="display: flex; flex-direction: column; gap:20px">
                                <div
                                    style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
                                    <div
                                        style="display: flex; flex-direction: row; align-items: center; width: 80%; gap: 10px;">
                                        <i class="fa-solid fa-bullhorn fa-xl" style="color: rgb(116, 192, 252);"></i>
                                        <span
                                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 90%;">แจ้งเปลี่ยนแปลงเวลาอบรม
                                            Safety Training
                                            ประจำเดือน พ.ศ. 2567</span>
                                    </div>
                                    <span style="width: 20%; text-align: end;">08 พ.ค. 67</span>
                                </div>
                                <div
                                    style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
                                    <div
                                        style="display: flex; flex-direction: row; align-items: center; width: 80%; gap: 10px;">
                                        <i class="fa-solid fa-bullhorn fa-xl" style="color: rgb(116, 192, 252);"></i>
                                        <span
                                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 90%;">ขอเชิญเข้าร่วมกิจกรรม
                                            5ส Big Cleaning Day</span>
                                    </div>
                                    <span style="width: 20%; text-align: end;">05 พ.ค. 67</span>
                                </div>
                                <div
                                    style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
                                    <div
                                        style="display: flex; flex-direction: row; align-items: center; width: 80%; gap: 10px;">
                                        <i class="fa-solid fa-bullhorn fa-xl" style="color: rgb(116, 192, 252);"></i>
                                        <span
                                            style=" white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 90%;">อัปเดตเอกสาร
                                            Work Instruction Line 1</span>
                                    </div>
                                    <span style="width: 20%; text-align: end;">02 พ.ค. 67</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>ประวัติการเรียนล่าสุด</h5>
                        </div>
                        <div class="card-body">
                            <div style="display: flex; flex-direction: column;">
                                @foreach($dashboard['learningHistory'] as $item)

                                    <div class="history-row">

                                        <div class="history-info">

                                            <i class="fa-solid {{ $item['icon'] }}"
                                            style="color:{{ $item['color'] }}"></i>

                                            <h5>
                                                {{ $item['course_title'] }}
                                            </h5>

                                        </div>


                                        <span>
                                            {{ $item['status'] }}
                                        </span>


                                        <span>
                                            {{ $item['date'] }}
                                        </span>


                                        <h4>
                                            {{ $item['percent'] }}%
                                        </h4>

                                    </div>

                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>ติดต่อ / บุคคลที่เกี่ยวข้อง</h5>
                        </div>
                        <div class="card-body">
                            <div style="display: flex; flex-direction: column; gap:5px;">
                                <div
                                    style="display:flex; flex-direction:row; align-items: center; justify-content: space-between;">
                                    <div
                                        style="display: flex; flex-direction: row; align-items: center; gap: 5px; width: 50%;">
                                        <img src="https://plus.unsplash.com/premium_photo-1689568126014-06fea9d5d341?fm=jpg&q=60&w=3000&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cHJvZmlsZXxlbnwwfHwwfHx8MA%3D%3D"
                                            alt="profile image" style="border-radius: 100%; width: 40px; height: 40px;">
                                        <div style="display: flex; flex-direction: column;">
                                            <span>หัวหน้างาน</span>
                                            <h5 style="margin: 0;"><strong>คุณสมชาย ใจดี</strong></h5>
                                        </div>
                                    </div>
                                    <span>02-123-4567</span>
                                    <i class="fa-regular fa-comment-dots fa-xl" style="color: rgb(116, 192, 252);"></i>
                                </div>
                                <div
                                    style="display:flex; flex-direction:row; align-items: center; justify-content: space-between;">
                                    <div
                                        style="display: flex; flex-direction: row; align-items: center; gap: 5px; width: 50%;">
                                        <img src="https://plus.unsplash.com/premium_photo-1689568126014-06fea9d5d341?fm=jpg&q=60&w=3000&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cHJvZmlsZXxlbnwwfHwwfHx8MA%3D%3D"
                                            alt="profile image" style="border-radius: 100%; width: 40px; height: 40px;">
                                        <div style="display: flex; flex-direction: column;">
                                            <span>ผู้ประสานงานอบรม</span>
                                            <h5 style="margin: 0;"><strong>คุณวิภา รักการอบรม</strong></h5>
                                        </div>
                                    </div>
                                    <span>02-234-5678</span>
                                    <i class="fa-regular fa-comment-dots fa-xl" style="color: rgb(116, 192, 252);"></i>
                                </div>
                                <div
                                    style="display:flex; flex-direction:row; align-items: center; justify-content: space-between;">
                                    <div
                                        style="display: flex; flex-direction: row; align-items: center; gap: 5px; width: 50%;">
                                        <img src="https://plus.unsplash.com/premium_photo-1689568126014-06fea9d5d341?fm=jpg&q=60&w=3000&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cHJvZmlsZXxlbnwwfHwwfHx8MA%3D%3D"
                                            alt="profile image" style="border-radius: 100%; width: 40px; height: 40px;">
                                        <div style="display: flex; flex-direction: column;">
                                            <span>ฝ่าย HR</span>
                                            <h5 style="margin: 0;"><strong>คุณมณิวรรณ สุขใจ</strong></h5>
                                        </div>
                                    </div>
                                    <span>02-345-6789</span>
                                    <a href=""><i class="fa-regular fa-comment-dots fa-xl"
                                            style="color: rgb(116, 192, 252);"></i></a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </section>
        </div>
    </div>



    <!-- New Employ -->
     {{-- <div class="main-content">
        <div class="container-fluid p-5">

            <div class="new-employ">
                <div class="group">
                    <div>
                        <h3>นาย พนักงานปกติ</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">Operator A -Team A</li>
                                <li class="breadcrumb-item">Line 1</li>
                                <li class="breadcrumb-item">Filling</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="date">
                        <div class="wrap">
                            <div class="">
                                <p>วันที่เริ่มงาน</p>
                                <p>01 ม.ค. 2567 </p>

                            </div>

                            <div class="">
                                <p>อายุงาน</p>
                                <p>4 เดือน 2 วัน </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section class="section-1 row">
                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 custom-5-col">
                    <div class="card card-stat color-blue">
                        <div class="stat-icon"><i class="fa-solid fa-book-open"></i></div>
                        <div class="stat-content">
                            <div class="stat-title">ทั้งหมด</div>
                            <div class="stat-value-row">
                                <span class="stat-qty">32</span>
                                <span class="stat-unit">ดูบทเรียน</span>
                            </div>
                            <div class="stat-footer-text">ที่ต้องเรียนทั้งหมด</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 custom-5-col">
                    <div class="card card-stat color-green">
                        <div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
                        <div class="stat-content">
                            <div class="stat-title">เรียนจบแล้ว</div>
                            <div class="stat-value-row">
                                <span class="stat-qty2">18</span>
                            </div>
                            <div class="stat-unit">
                                ดูบทเรียน
                            </div>
                        </div>
                        <span class="stat-badge">56%</span>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 custom-5-col">
                    <div class="card card-stat color-yellow">
                        <div class="stat-icon"><i class="fa-regular fa-clock"></i></div>
                        <div class="stat-content">
                            <div class="stat-title">กำลังเรียน</div>
                            <div class="stat-value-row">
                                <span class="stat-qty3">6</span>
                            </div>
                            <div class="stat-unit">
                                ดูบทเรียน
                            </div>
                        </div>
                        <span class="stat-badge">19%</span>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 custom-5-col">
                    <div class="card card-stat color-purple">
                        <div class="stat-icon"><i class="fa-solid fa-circle-play"></i></div>
                        <div class="stat-content">
                            <div class="stat-title">ยังไม่เริ่ม</div>
                            <div class="stat-value-row">
                                <span class="stat-qty4">6</span>
                            </div>
                            <div class="stat-unit">
                                ดูบทเรียน
                            </div>
                        </div>
                        <span class="stat-badge">19%</span>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 custom-5-col">
                    <div class="card card-stat color-red">
                        <div class="stat-icon"><i class="fa-solid fa-circle-xmark"></i></div>
                        <div class="stat-content">
                            <div class="stat-title">ไม่ผ่าน/ต้องซ่อม</div>
                            <div class="stat-value-row">
                                <span class="stat-qty5">2</span>
                            </div>
                            <div class="stat-unit">
                                ดูบทเรียน
                            </div>
                        </div>
                        <span class="stat-badge">6%</span>
                    </div>
                </div>
            </section>

            <section class="section-2 row row-eq-height">
                <div class="col-lg-5 col-md-12 col-12">
                    <div class="card h-100" style="min-height: 422px;">
                        <div class="card-header">
                            <h5>ความก้าวหน้าการเรียนของฉัน</h5>
                        </div>
                        <div class="card-body">
                            <div class="progress-split-layout">
                                <div class="progress-split-left">
                                    <div class="progress-circle-block" id="main-progress-block" data-percent="56">
                                        <svg class="progress-circle-svg" viewBox="0 0 100 100">
                                            <circle class="progress-circle-bg" cx="50" cy="50" r="45"></circle>
                                            <circle class="progress-circle-value" id="main-progress-value" cx="50" cy="50" r="45" stroke-dasharray="282.7"></circle>
                                        </svg>
                                        <div class="progress-circle-text">
                                            <span class="progress-circle-pct" id="main-progress-text">0%</span>
                                            <span class="progress-circle-label">ความคืบหน้า</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="progress-split-right">
                                    <ul class="progress-status-list">
                                        <li class="status-item">
                                            <div class="status-dot-title"><span class="status-dot green"></span>ความปลอดภัย (Safety)</div>
                                            <span class="status-result-badge"><span class="bold-text">100%</span> <span class="text-green">ผ่าน</span></span>
                                        </li>
                                        <li class="status-item">
                                            <div class="status-dot-title"><span class="status-dot orange"></span>คุณภาพ (Quality)</div>
                                            <span class="status-result-badge"><span class="bold-text">75%</span> <span class="text-orange">กำลังเรียน</span></span>
                                        </li>
                                        <li class="status-item">
                                            <div class="status-dot-title"><span class="status-dot orange"></span>การปฏิบัติงานเครื่องจักร</div>
                                            <span class="status-result-badge"><span class="bold-text">40%</span> <span class="text-orange">กำลังเรียน</span></span>
                                        </li>
                                        <li class="status-item">
                                            <div class="status-dot-title"><span class="status-dot grey"></span>การบำรุงรักษาเบื้องต้น (PM)</div>
                                            <span class="status-result-badge"><span class="bold-text">0%</span> <span class="text-grey">ยังไม่เริ่ม</span></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card h-100" style="min-height: 422px;">
                        <div class="card-header">
                            <h5>สำหรับพนักงานใหม่ <span style="font-size: 14px; font-weight: 400; color: #555;">(ทดลองงาน 120 วัน)</span></h5>
                        </div>
                        <div class="card-body" style="display: flex; flex-direction: column;">

                            <div class="probation-timeline">
                                <div class="timeline-step">
                                    <div class="circle-badge green">30 วัน</div>
                                    <div class="step-score text-green">8/10</div>
                                    <div class="step-pct text-green">80%</div>
                                </div>
                                <div class="timeline-step">
                                    <div class="circle-badge yellow">60 วัน</div>
                                    <div class="step-score text-yellow">6/10</div>
                                    <div class="step-pct text-yellow">60%</div>
                                </div>
                                <div class="timeline-step">
                                    <div class="circle-badge">90 วัน</div>
                                    <div class="step-score" style="color: #333;">2/10</div>
                                    <div class="step-pct" style="color: #333;">20%</div>
                                </div>
                                <div class="timeline-step">
                                    <div class="circle-badge">120 วัน</div>
                                    <div class="step-score" style="color: #333;">-</div>
                                    <div class="step-empty">ยังไม่ถึงเวลา</div>
                                </div>
                            </div>

                            <div class="probation-alert">
                                <i class="fa-solid fa-circle-plus"></i>
                                <div class="probation-alert-text">
                                    <span>คุณอยู่ในช่วง 30 วันแรก (01 ม.ค. 2567 - 30 ม.ค. 2567)</span>
                                    <span>กรุณาเรียนให้ครบตามแผนเพื่อผ่านการประเมินในแต่ละรอบ</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card h-100" style="min-height: 380px;">
                        <div class="card-header">
                            <h5>บทเรียนที่ต้องเรียน / กำหนดเวลา</h5>
                        </div>
                        <div class="card-body">
                            <div class="lesson-flex-list">

                                <div class="lesson-row-card">
                                    <div class="lesson-info-left">
                                        <div class="icon-box icon-blue">
                                            <i class="fa-solid fa-book-open"></i>
                                        </div>
                                        <div class="lesson-name-sub">
                                            <span class="lesson-main-name">GMP Refresher Training</span>
                                            <span class="lesson-sub-name">ดูบทเรียน</span>
                                        </div>
                                    </div>
                                    <div class="lesson-deadline-date"><span>ครบกำหนด</span>10 พ.ค. 67</div>
                                </div>

                                <div class="lesson-row-card">
                                    <div class="lesson-info-left">
                                        <div class="icon-box icon-blue">
                                            <i class="fa-solid fa-book-open"></i>
                                        </div>
                                        <div class="lesson-name-sub">
                                            <span class="lesson-main-name">QC Basic </span>
                                            <span class="lesson-sub-name">ดูบทเรียน</span>
                                        </div>
                                    </div>
                                    <div class="lesson-deadline-date"><span>ครบกำหนด</span>15 พ.ค. 67</div>
                                </div>

                                <div class="lesson-row-card">
                                    <div class="lesson-info-left">
                                        <div class="icon-box icon-blue">
                                            <i class="fa-solid fa-book-open"></i>
                                        </div>
                                        <div class="lesson-name-sub">
                                            <span class="lesson-main-name">Machine Setup </span>
                                            <span class="lesson-sub-name">ดูบทเรียน</span>
                                        </div>
                                    </div>
                                    <div class="lesson-deadline-date"><span>ครบกำหนด</span>20 พ.ค. 67</div>
                                </div>

                                <div class="lesson-row-card">
                                    <div class="lesson-info-left">
                                        <div class="icon-box icon-blue">
                                            <i class="fa-solid fa-book-open"></i>
                                        </div>
                                        <div class="lesson-name-sub">
                                            <span class="lesson-main-name">Machine Setup Basics</span>
                                            <span class="lesson-sub-name">ดูบทเรียน</span>
                                        </div>
                                    </div>
                                    <div class="lesson-deadline-date"><span>ครบกำหนด</span>20 พ.ค. 67</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="bottom-card-group">
                <section class="section-3 row row-eq-height">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>
                                    หลักสูตรที่ต้องเรียนต่อ
                                    <span class="badge" style="background-color: red; margin-left: 5px;">6</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div style="display: flex; flex-direction: column; gap: 5px;">
                                    <div class="course-row-responsive" style="padding-left: 10px; border-left: 5px solid #337ab7; display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                                        <div style="display: flex; flex-direction: column; width: 50%;">
                                            <h5 style="font-weight: bold;">การตั้งค่าเครื่องจักรเบื้องต้น
                                            </h5>
                                            <span>Machine Setup Basics</span>
                                        </div>
                                        <div style="width: 25%; display: flex; flex-direction: row; justify-content: center; align-items:center; gap: 1px;">
                                            <div class="progress" style="width: 100%; height: 10px; margin: 0;">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;">
                                                </div>

                                            </div>
                                            <span>80%</span>
                                        </div>

                                        <div style="width: 20%; display:flex; justify-content: center;"><a href="#" class="btn btn-primary" role="button">ดูบทเรียน</a></div>
                                    </div>
                                    <div class="course-row-responsive" style="padding-left: 10px; border-left: 5px solid #337ab7; display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                                        <div style="display: flex; flex-direction: column; width: 50%;">
                                            <h5 style="font-weight: bold;">การควบคุมคุณภาพในการผลิต</h5>
                                            <span>In-progress Quality Control</span>
                                        </div>
                                        <div style="width: 25%; display: flex; flex-direction: row; justify-content: center; align-items:center; gap: 1px;">
                                            <div class="progress" style="width: 100%; height: 10px; margin: 0;">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
                                                </div>

                                            </div>
                                            <span>40%</span>
                                        </div>

                                        <div style="width: 20%; display:flex; justify-content: center;"><a href="#" class="btn btn-primary" role="button">ดูบทเรียน</a></div>
                                    </div>
                                    <div class="course-row-responsive" style="padding-left: 10px; border-left: 5px solid #337ab7; display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                                        <div style="display: flex; flex-direction: column; width: 50%;">
                                            <h5 style="font-weight: bold;">การตรวจสอบ 5 ส</h5>
                                            <span>$$ Inspection</span>
                                        </div>
                                        <div style="width: 25%; display: flex; flex-direction: row; justify-content: center; align-items:center; gap: 1px;">
                                            <div class="progress" style="width: 100%; height: 10px; margin: 0;">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                </div>

                                            </div>
                                            <span>0%</span>
                                        </div>

                                        <div style="width: 20%; display:flex; justify-content: center;"><a href="#" class="btn btn-primary" role="button">ดูบทเรียน</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>
                                    หลักสูตรที่ไม่ผ่าน / ต้องซ่อม
                                    <span class="badge" style="background-color: red; margin-left: 5px;">2</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div style="display: flex; flex-direction: column; gap: 5px;">
                                    <div class="course-row-responsive" style="padding-left: 10px; border-left: 5px solid red; display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                                        <div style="display: flex; flex-direction: column; width: 50%;">
                                            <h5 style="font-weight: bold;">ความปลอดภัยในการทำงาน</h5>
                                            <span>Safety at Work</span>
                                        </div>
                                        <div style="width: 25%;">
                                            <h5>คะแนน <span style="font-weight: bold;">65%</span></h5>
                                        </div>

                                        <div style="width: 20%; display:flex; justify-content: center;"><a href="#" class="btn" style="border: 1px solid red; color: red;" role="button">ซ่อม</a></div>
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 5px;">
                                        <div class="course-row-responsive" style="padding-left: 10px; border-left: 5px solid red; display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                                            <div style="display: flex; flex-direction: column; width: 50%;">
                                                <h5 style="font-weight: bold;">ความรู้พื้นฐานคุณภาพ</h5>
                                                <span>Quality Basics</span>
                                            </div>
                                            <div style="width: 25%;">
                                                <h5>คะแนน <span style="font-weight: bold;">70%</span></h5>
                                            </div>

                                            <div style="width: 20%; display:flex; justify-content: center;"><a href="#" class="btn" style="border: 1px solid red; color: red;" role="button">ซ่อม</a></div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>
                                    ผลประเมินล่าสุด
                                </h5>
                            </div>
                            <div class="card-body">
                                <div style="display: flex; flex-direction: column; gap: 5px;">
                                    <div style="display: flex; flex-direction: row; justify-content: space-between;">
                                        <div style="display: flex; flex-direction: row; gap:10px">
                                            <div style="padding-top: 10px;">
                                                <i class="fa-solid fa-circle-check fa-2xl" style="color: rgb(99, 230, 114);"></i>
                                            </div>
                                            <div style="display: flex; flex-direction: column;">
                                                <h5 style="font-weight: bold; margin: 0;">การตรวจสอบ 5 ส</h5>
                                                <span>5S Inspection</span>
                                                <span>วันที่สอบ: 10/05/2024</span>
                                            </div>
                                        </div>
                                        <div style=" text-align: center; padding-inline: 20px; padding-block: 5px;">
                                            <span>คะแนน</span>
                                            <h4 style="font-weight: bolder; color: #63e672;">85%</h4>
                                        </div>
                                    </div>

                                    <div style="display: flex; flex-direction: row; justify-content: space-between;">
                                        <div style="display: flex; flex-direction: row; gap:10px">
                                            <div style="padding-top: 10px;">
                                                <i class="fa-solid fa-circle-xmark fa-2xl" style="color: rgb(230, 99, 99);"></i>
                                            </div>
                                            <div style="display: flex; flex-direction: column;">
                                                <h5 style="font-weight: bold; margin: 0;">ความปลอดภัยในการทำงาน</h5>
                                                <span>Safety at Work</span>
                                                <span>วันที่สอบ: 10/05/2024</span>
                                            </div>
                                        </div>
                                        <div style="text-align: center; padding-inline: 10px; padding-block: 5px;">
                                            <span>คะแนน</span>
                                            <h4 style="font-weight: bolder; color: red;">ไม่ผ่าน</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="section-4 row row-eq-height">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>ประกาศจากบริษัท</h5>
                            </div>
                            <div class="card-body">
                                <div style="display: flex; flex-direction: column; gap:20px">
                                    <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
                                        <div style="display: flex; flex-direction: row; align-items: center; width: 80%; gap: 10px;">
                                            <i class="fa-solid fa-bullhorn fa-xl" style="color: rgb(116, 192, 252);"></i>
                                            <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 90%;">แจ้งเปลี่ยนแปลงเวลาอบรม
                                                Safety Training
                                                ประจำเดือน พ.ศ. 2567</span>
                                        </div>
                                        <span style="width: 20%; text-align: end;">08 พ.ค. 67</span>
                                    </div>
                                    <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
                                        <div style="display: flex; flex-direction: row; align-items: center; width: 80%; gap: 10px;">
                                            <i class="fa-solid fa-bullhorn fa-xl" style="color: rgb(116, 192, 252);"></i>
                                            <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 90%;">ขอเชิญเข้าร่วมกิจกรรม
                                                5ส Big Cleaning Day</span>
                                        </div>
                                        <span style="width: 20%; text-align: end;">05 พ.ค. 67</span>
                                    </div>
                                    <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
                                        <div style="display: flex; flex-direction: row; align-items: center; width: 80%; gap: 10px;">
                                            <i class="fa-solid fa-bullhorn fa-xl" style="color: rgb(116, 192, 252);"></i>
                                            <span style=" white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 90%;">อัปเดตเอกสาร
                                                Work Instruction Line 1</span>
                                        </div>
                                        <span style="width: 20%; text-align: end;">02 พ.ค. 67</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>ประวัติการเรียนล่าสุด</h5>
                            </div>
                            <div class="card-body">
                                <div style="display: flex; flex-direction: column;">
                                    <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
                                        <div style="display: flex; flex-direction: row; gap:5px; align-items: center; width: 50%;">
                                            <i class="fa-solid fa-circle-check fa-xl" style="color: rgb(99, 230, 114);"></i>
                                            <h5>GMP Refresher Training</h5>
                                        </div>
                                        <span>เรียนจบ</span>
                                        <span>10 พ.ค. 67</span>
                                        <h4>85%</h4>
                                    </div>
                                    <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
                                        <div style="display: flex; flex-direction: row; gap:5px; align-items: center; width: 50%;">
                                            <i class="fa-solid fa-circle-check fa-xl" style="color: rgb(99, 230, 114);"></i>
                                            <h5>QC Basic</h5>
                                        </div>
                                        <span>เรียนจบ</span>
                                        <span>09 พ.ค. 67</span>
                                        <h4>80%</h4>
                                    </div>
                                    <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
                                        <div style="display: flex; flex-direction: row; gap:5px; align-items: center; width: 50%;">
                                            <i class="fa-solid fa-circle-check fa-xl" style="color: rgb(99, 230, 114);"></i>
                                            <h5>Machine Setup</h5>
                                        </div>
                                        <span>เรียนจบ</span>
                                        <span>08 พ.ค. 67</span>
                                        <h4>60%</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </section>
            </div>
        </div>
    </div>  --}}

    <script>
        $(document).ready(function() {
            var block = $('#main-progress-block');
            var percent = parseInt(block.attr('data-percent')) || 0;
            var circumference = 282.74;
            var offset = circumference - (percent / 100) * circumference;
            $('#main-progress-value').css({
                'stroke-dashoffset': circumference,
                'transition': 'stroke-dashoffset 1.5s ease-out'
            });
            setTimeout(function() {
                $('#main-progress-value').css('stroke-dashoffset', offset);

                $({
                    counter: 0
                }).animate({
                    counter: percent
                }, {
                    duration: 1500,
                    easing: 'swing',
                    step: function() {
                        $('#main-progress-text').text(Math.ceil(this.counter) + '%');
                    }
                });
            }, 200);
        });
    </script>
</body>
@endsection