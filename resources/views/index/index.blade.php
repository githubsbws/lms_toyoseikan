@extends('layout/mainlayout')
@section('title', 'Brother e-learning')
@section('content')
@php
use App\Models\Downloadcategoty;
use App\Models\DownloadFile;

@endphp
<style>
 .page-cover {
    position: relative;
    width: 100%;
    overflow: hidden;
    margin-top: 80px;
}

.image-slider {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    position: relative;
}

.slide {
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

.slide.active {
    opacity: 1;
    position: relative;
}

.slide img {
    width: 100%;
    height: auto;
    object-fit: cover;
}

.prev, .next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    padding: 10px;
    border: none;
    cursor: pointer;
}

.prev {
    left: 10px;
}

.next {
    right: 10px;
}
</style>
<body>
    <div class="container">
      <div class="page-cover">
        <div class="image-slider">
          @foreach($img as $item)
              <div class="slide">
                  <a href="{{ $item->imgslide_link }}">
                      <img src="{{asset('images/uploads/imgslides/'.$item->imgslide_picture)}}" alt="">
                  </a>
              </div>
          @endforeach
        </div>
        @if(count($img) > 1)
        <button class="prev" onclick="moveSlide(-1)">&#9664; </button>
        <button class="next" onclick="moveSlide(1)"> &#9654;</button>
        @else

        @endif
      </div>
      <script>
        let slideIndex = 0;
        const slides = document.querySelectorAll('.slide');

        function showSlides(n) {
            slides.forEach(slide => slide.classList.remove('active'));
            slides[n].classList.add('active');
        }

        function moveSlide(step) {
            slideIndex += step;
            if (slideIndex >= slides.length) slideIndex = 0;
            if (slideIndex < 0) slideIndex = slides.length - 1;
            showSlides(slideIndex);
        }

        // เริ่มต้นแสดงสไลด์แรก
        showSlides(slideIndex);
    </script>
        <div class="page-section-heading">
            <div class="row">
                <div class="col-lg-8 col-md-8 pd-20">
                    <div class="row">
                    </div>
                    <div>
                        <div class="card card-default">
                        </div>
                    </div>
                </div> <!-- end con 8 -->


                <div class="col-lg-4 col-md-4 box-video login pd-20">
                    <!-- start con 4 -->


                    <h2 class="title-layout"><span>วีดีโอแนะนำ</span> </h2>
                    <video width="100%" controls="">
                        <source src=" {{asset('themes/bws/video/brother-video-1.mp4')}}" type="video/mp4">
                        <source src="mov_bbb.ogg" type="video/ogg">
                        Your browser does not support HTML5 video.
                    </video>

                    <h2 class="title-layout"><span>เกี่ยวกับบริษัท</span> </h2>

                    <div class="group-link">
                        <div class="depart-well-regis">
                            <a href="{{route('contactus_f')}}"> ติดต่อเรา </a>
                        </div>
                        <div class="depart">
                            <a href="{{route('conditions')}}"> เงื่อนไขการใช้งาน </a>
                        </div>
                    </div>
                </div> <!-- end con 4 -->
            </div>
        </div>
    </div>
</body>
<script>
document.getElementById('search').addEventListener('keyup', async function() {
    const query = this.value.trim();

    // ถ้า query ว่าง ให้ล้างผลลัพธ์
    if (query.length === 0) {
        document.getElementById('results').innerHTML = '';
        document.getElementById('result-count').innerHTML = '';
        return;
    }

    try {
        const res = await fetch(`/ocr/search?q=${encodeURIComponent(query)}`);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const data = await res.json();
        const hits = data.data || []; // ดึง array ของผลลัพธ์จาก data.data

        // แสดงจำนวนผลลัพธ์
        document.getElementById('result-count').innerHTML = `<h5 class="title-layout">พบ ${hits.length} ผลลัพธ์ที่ตรงกับ <span style="color:red">"${query}"</span></h5>`;

        let html = '';

        hits.forEach(hit => {
            let text = hit.highlight_text ?? hit.text; // ใช้ highlight_text ถ้ามี

            const pdfBaseUrl = '/images/uploads/ocr';
            const pdfUrl = `${pdfBaseUrl}/${hit.folder_name}/${hit.filename}#page=${hit.page_number}`;
            const filename = hit.highlight_filename ?? hit.filename ?? '-';

            html += `<div style="padding:5px; border-bottom:1px solid #ccc;">
                        <strong>ชื่อเอกสาร:</strong> ${filename}<br>
                        <strong>หน้าที่:</strong> ${hit.page_number || '-'}<br>
                        <strong>บรรทัด:</strong> ${text}<br>
                        <a href="${pdfUrl}" class="btn btn-primary btn-lg paper-shadow relative" target="_blank" style="color: white !important; text-decoration: underline !important;">
                            เปิดเอกสาร หน้า ${hit.page_number}
                        </a>
                    </div>`;
        });

        document.getElementById('results').innerHTML = html;

    } catch (err) {
        console.error('Search failed', err);
        document.getElementById('results').innerHTML = '<div style="color:red;">Search failed</div>';
        document.getElementById('result-count').innerHTML = '';
    }
});
</script>

@endsection
