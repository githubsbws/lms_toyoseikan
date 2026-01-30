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
                    <!-- start con 8 -->
                    @if(Auth::user())
                    <h2 class="title-layout">
                      ค้นหาเอกสาร
                    </h2>
                    <div class="row">
                      <input type="text" class="form-control" id="search" placeholder="Search OCR" />
                      <div id="result-count" style="margin-bottom:10px;"></div>
                      <div id="results"></div>
                    </div>
                    @endif
                    <h2 class="title-layout">
                        <span>ข่าวสาร</span> <small>รวมข่าวสารของ Brother </small>
                        @if(Auth::check())
                        <a href="{{url('new')}}" class="pull-right btn btn-xs btn-light text-1 text-uppercase">ดูทั้งหมด <i class="fa fa-" aria-hidden="true"></i>
                        @else
                        <a href="{{url('logins')}}" class="pull-right btn btn-xs btn-light text-1 text-uppercase">ดูทั้งหมด <i class="fa fa-" aria-hidden="true"></i>
                        @endif
                        </a>
                    </h2>
                    <div class="row">
                        @foreach ($news as $new)
                        <div class="col-lg-6 col-md-6">
                            <div class="news-box gridalicious" data-toggle="gridalicious">

                                <div class="galcolumn" id="item02grJH" style="width: 95.7983%; padding-left: 15px; padding-bottom: 15px; float: left; box-sizing: border-box;">
                                    <div class="card" style="margin-bottom: 15px; zoom: 1; opacity: 1;">
                                      <img class="img-news" src="{{asset('images/uploads/news/'.$new->cms_id.'/original/'.$new->cms_picture)}}">
                                        {{-- <div class="img-news" style="background: {{asset('images/uploads/news/'.$new->cms_id.'/thumb/'.$new->cms_picture)}};"></div> --}}
                                        <div class="card-body">
                                            <div class="text-headline">{{ \Illuminate\Support\Str::limit($new->cms_title, $limit = 40, $end = '...') }}</div>
                                            <?php 
                                            $detail =  htmlspecialchars_decode($new->cms_detail);
                                            $data = \Illuminate\Support\Str::limit($new->cms_short_title, $limit = 100, $end = '...');
                                            ?>
                                            <p class="detail-news">{{ $data }}</p>
                                            <span class="d-block mt-2">
                                                <a href="{{ route('new_detail', ['id' => $new->cms_id]) }}" class="btn btn-xs btn-light text-1 text-uppercase">เพิ่มเติม
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div id="clear2grJH" style="clear: both; height: 0px; width: 0px; display: block;"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>


                    <div>

                        <div class="card card-default">
                            <h2 class="title-layout-2"><span>ดาวน์โหลดไฟล์เอกสาร</span>
              
                      <!--         <button href="/index.php/document/index" class="pull-right btn btn-xs btn-light text-1 text-uppercase ">ดูทั้งหมด <i class="fa fa-" aria-hidden="true"></i>
                              </button> </h2> -->
              
                               <a href="{{url('download')}}" data-toggle="modal" class="pull-right btn btn-xs btn-light text-1 text-uppercase " fdprocessedid="fyzyev">ดูทั้งหมด <i class="fa fa-" aria-hidden="true"></i>
                              </a> </h2>

                            
                                                             <br>
                                              <!-- <button type="button" class="accordion mg-0 download-dc" data-toggle="modal" data-target="#exampleModal"><img src="/themes/bws/images/icons8-pdf-48.png">                 </button>
                              <div class="form-group">
                                                    <li class="list-group-item"><a href="/index.php/site/download?file_id=1127">
                                      SM_PJ623_663.pdf <span class="pull-right"><i class="fa fa-download"></i> ดาวน์โหลด </span></a></li>
                                                    <li class="list-group-item"><a href="/index.php/site/download?file_id=1128">
                                      SM_PJ763_763MFI.pdf <span class="pull-right"><i class="fa fa-download"></i> ดาวน์โหลด </span></a></li>
                                                    <li class="list-group-item"><a href="/index.php/site/download?file_id=1129">
                                      SM_PJ673.pdf <span class="pull-right"><i class="fa fa-download"></i> ดาวน์โหลด </span></a></li>
                                                </div> -->
                              @if(Auth::check())
                              <div class="panel panel-default paper-shadow download-group" data-z="0.5" style="margin-bottom: 5px;">
                                <ul class=" list-group">
                                  @foreach($download as $dow)
                                <li class="list-group-item">
                                  <div class="media v-middle">
                                    <div class="media-body">
                                      <h4 class="text-headline margin-none" style="font-size: 26px;font-weight: bold;">หัวข้อ - {{$dow->title_name}}</h4>
                                    </div>
                                    <div class="media-right">
                                    </div>
                                  </div>
                                </li>
              
                                <li class="list-group-item media v-middle">
                                  @php
                                  $dow_cate = Downloadcategoty::where('title_id',$dow->title_id)->where('active','y')->paginate(3);
                                  @endphp
                                  @foreach($dow_cate as $cate)
                                  @php
                                  $file = DownloadFile::join('download_filedoc','download_filedoc.file_id','=','download_file.file_id')->where('download_file.download_id',$cate->download_id)->where('download_file.active','y')->get();
                                  @endphp
                                  <div class="panel panel-default" data-toggle="panel-collapse" data-open="false">
                                    <div class="panel-heading dowload-header panel-collapse-trigger collapse in" data-toggle="collapse" data-target="#{{$cate->download_id}}" aria-expanded="true" style="">
                                      <h3 class="panel-title" style="font-size: 22px;">หมวดหมุ่ - {{$cate->download_name}}</h3>
                                    </div>
              
                                    
                                  <div id="{{$cate->download_id}}" class="collapse"><div class="panel-body list-group">
              
                                      <div class="panel panel-default">
                                        <div class="panel-heading detail-change-icon">
                                          <a data-toggle="collapse" class="download-change0" href="#collapse-0">
                                            <h4 class="panel-title dowload-title">
                                                                              <!-- <span class="pull-right"><i class="icon-change-2 fa fa-plus " aria-hidden="true"></i></span> -->
                                            </h4>
                                          </a>
              
              
              
                                        </div>
                                        <div id="collapse-0" class="panel-collapse collapse in">
                                          <ul class="list-group list-group-menu">
                                            @foreach($file as $f)
                                             <li class="list-group-item">
                                              <!-- 
                                                <a href="/index.php/site/download?file_id=1127"> -->
                                                    <a href="{{ route('download.downloadfiles', ['id' => $f->filedoc_id]) }}">
                                                      {{-- <a href="{{ route('index.downloadfile', ['id' => $f->filedoc_id]) }}" data-toggle="modal"> --}}
              
                                                  <img src="{{asset('themes/bws/images/icons8-pdf-48.png')}}" alt="person" style="width: 22px;">
                                                  {{$f->filedoc_name}} <span class="pull-right"><i class="fa fa-download"></i> ดาวน์โหลด </span>
                                                </a>
                                              </li>
                                            @endforeach
              
              
                                            {{-- <div class="modal fade" id="modal-ckeck-key-filedow1127">
                                                <div class="modal-dialog">
                                                  <div class="modal-content">
                                                    <div class="modal-header">
                                                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                      <h4 class="modal-title modalhead"><i class="fa fa-sign-in" aria-hidden="true"></i> ยืนยันรหัสผ่าน</h4>
                                                    </div>
                                                    <form action="/index.php/site/Chkkey" method="post" enctype="multipart/form-data">
                                                      <div class="modal-body">
                                                        <div class="row">
                                                          <div class="col-sm-8 col-sm-offset-2 text-center">
                                                            <h3 class="font-weight-bold">กรุณากรอกรหัส key เพื่่อยืนยันตัวตน</h3>
                                                            <input type="hidden" name="id" class="form-control" value="filedow">
                                                            <input type="hidden" name="valfile" class="form-control" value="1127">
              
                                                            <input type="password" name="check_key" class="form-control" autocomplete="off">
              
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">ตกลง</button>
                                                        <button class="btn btn-warning" href="#" data-dismiss="modal" aria-hidden="true">ยกเลิก</button>
                                                      </div>
                                                    </form>
                                                  </div>
                                                </div>
                                              </div> --}}
                                             </ul>
                                        </div>
              
              
                                      </div>
              
                                      </div>
                                    </div>
                                  </div>
                                  @endforeach
              
                                </li>
                                @endforeach
                                </ul>
                              </div>
                              @else
                              <li class="list-group-item">
                                <div class="media v-middle">
                                  <div class="media-body">
                                    <h4 class="text-headline margin-none" style="font-size: 26px;">กรุณาเข้าสู่ระบบเพื่อดาวน์โหลดไฟล์เอกสาร</h4>
                                  </div>
                                  <div class="media-right">
                                  </div>
                                </div>
                              </li>
                              @endif
              
                            
              
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
