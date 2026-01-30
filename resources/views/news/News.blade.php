@extends('layout/mainlayout')
@section('content')
<body>

    <div class="span-19">
        <div id="content">
            <div class="parallax overflow-hidden page-section bg-blue-300" style="margin-top: 81px;">
                <div class="container parallax-layer" data-opacity="true">
                    <div class="media media-grid v-middle">
                        <div class="media-left">
                            <span class="icon-block half bg-blue-500 text-white" style="height: 45px;"><i class="fa fa-fw fa-newspaper-o"></i></span>
                        </div>
                        <div class="media-body">
                            <h3 class="text-display-2 text-white margin-none">ข่าวสาร</h3>
                            <p class="text-white text-subhead" style="font-size: 1.6rem;">รวมข่าวสารของ Brother</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="page-section">
                    <div class="row">
                        <div class="col-md-9">
                            @foreach($news as $value)
                            <div class="panel panel-default paper-shadow" data-z="0.5" style="margin-bottom: 25px;">
                                <div class="panel-body">
                                    <div class="media media-clearfix-xs">
                                        <div class="media-left text-center">
                                            <div class="cover width-150 width-100pc-xs overlay cover-image-full hover">
                                                <img src="{{ asset('images/uploads/news/'.$value->cms_id.'/original/'.$value->cms_picture)}}">
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="text-headline margin-v-5-0" style="font-size: 28px;"><a href="{{ route('new_detail', ['id' => $value->cms_id]) }}" title="{{ $value->cms_title}}">{{ $value->cms_title}}</a></h4>

                                            <p style="color: rgb(33, 33, 33);">{{ $value->cms_short_title}}</p>
                                            <hr class="margin-v-8">
                                            <div class="media v-middle">
                                                <div class="media-body">
                                                    <h6> posted by
                                                        <i class="fa fa-fw fa-comment" style="color:#42A5F5;"></i> {{ $value->profile->firstname ?? '-'}} &nbsp; | <i class="fa fa-fw fa-calendar" style="color:#42A5F5;"></i> {{ $value->update_date ?? '-'}} <br>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div style="float: right;">
                                <ul class="pagination margin-top-none" id="yw0">
                                    <li class="first "><a href="{{url('new')}}">&lt;&lt; หน้าแรก</a></li>
                                    @if ($news->currentPage() > 1)
                                    <li class="previous "><a href="{{ $news->previousPageUrl() }}" class="pagination-link">หน้าที่แล้ว</a></li>
                                    @endif
                                    @for ($i = max(1, $news->currentPage() - 3); $i <= min($news->lastPage(), $news->currentPage() + 3); $i++)
                                    <li class="page"><a href="{{ $news->url($i) }}" class="pagination-link {{ ($i == $news->currentPage()) ? 'active' : '' }}">{{ $i }}</a></li>
                                    @endfor
                                    @if ($news->currentPage() < $news->lastPage())
                                    <li class="next"><a href="{{ $news->nextPageUrl() }}" class="pagination-link">หน้าถัดไป</a></li>
                                    @endif
                                    @if ($news->currentPage() < $news->lastPage())
                                    <li class="last"><a href="{{ url('new?page='.$news->lastPage()) }}"  class="pagination-link">หน้าสุดท้าย &gt;&gt;</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <form action="{{url('new_search')}}" method="get">
                                <div class="panel panel-default" data-toggle="panel-collapse" data-open="true">
                                    <div class="panel-heading panel-collapse-trigger collapse in" data-toggle="collapse" data-target="#4fe5d772-ac6b-7188-4cfa-b2666261bdf7" aria-expanded="true" style="">
                                        <h4 class="panel-title" style="font-weight: bold;">ค้นหา</h4>
                                    </div>

                                    <div id="4fe5d772-ac6b-7188-4cfa-b2666261bdf7" class="collapse in">
                                        <div class="panel-body">
                                            <div class="form-group input-group margin-none">
                                                <div class="row margin-none">
                                                    <div class="col-xs-12 padding-none">
                                                        <input class="form-control" type="text" id="search_text" name="search_text" placeholder="คำค้นหา">
                                                    </div>
                                                </div>
                                                <div class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title" style="font-weight: bold;">ข่าวล่าสุด</h4>
                                </div>
                                @foreach($news_desc as $new)
                                <ul class="list-group list-group-menu">
                                    <li class="list-group-item">
                                        <a href="{{ route('new_detail', ['id' => $new->cms_id]) }}"><i class="fa fa-chevron-right fa-fw"></i> {{ $new->cms_title }}</a>
                                    </li>
                                </ul>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- content -->
    </div>

</body>
@endsection