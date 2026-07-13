<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    @include('admin.layouts.partials.head')

</head>
<style>
    .note-editor.note-frame.fullscreen {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100% !important;
        height: 100% !important;
        z-index: 99999 !important;
        background: #fff;
    }

    body.note-fullscreen {
        overflow: hidden;
    }

    /* fullscreen เท่านั้น */
    .note-editor.note-frame.fullscreen .note-editable {
        height: calc(100vh - 120px) !important;
    }

    /* ตอนออก fullscreen ให้คืนค่า */
    .note-editor.note-frame:not(.fullscreen) .note-editable {
        height: auto !important;
    }

    .select2-container .select2-selection--single {
        height: 42px !important;
        border: 1px solid #ced4da !important;
        border-radius: 6px !important;
        background-color: #fff;
        transition: all .2s ease;
    }

    .select2-container--default 
    .select2-selection--single 
    .select2-selection__rendered {
        line-height: 42px !important;
        padding-left: 14px !important;
        color: #495057;
        font-size: 15px;
    }

    .select2-container--default 
    .select2-selection--single 
    .select2-selection__arrow {
        height: 42px !important;
        right: 10px !important;
    }


    /* ตอนกดเลือก */
    .select2-container--default.select2-container--focus 
    .select2-selection--single {
        border-color: #80bdff !important;
        box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);
    }


    /* Dropdown */
    .select2-container--default 
    .select2-results__option {
        padding: 10px 14px;
        font-size: 15px;
    }


    /* Hover รายการ */
    .select2-container--default 
    .select2-results__option--highlighted[aria-selected] {
        background-color: #007bff;
    }


    /* ช่องค้นหา */
    .select2-search--dropdown .select2-search__field {
        height: 38px;
        border-radius: 5px;
        border: 1px solid #ced4da;
        padding-left: 10px;
    }
</style>

<body>
    @if (!View::hasSection('hidesidebar'))
    @include('admin.layouts.partials.menu-left')
    @endif

    @yield('content')


    <div id="footer" class="hidden-print">

        <!--  Copyright Line -->
        <div class="copy">© 2023 - All Rights Reserved.</a></div>
        <!--  End Copyright Line -->

    </div>
</body>

</html>