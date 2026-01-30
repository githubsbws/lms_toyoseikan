<?php

namespace App\Http\Controllers;

use App\Models\Downloadtitle;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use App\Models\Imgslide;

class IndexController extends Controller
{
   function index(){
      $news = News::where('active','y')->limit(4)->orderBy('create_date','DESC')->get();
      $download = Downloadtitle::where('active','y')->get();
      $img = Imgslide::where('active','y')->get();
      
      return view("index.index",[
        'news' => $news,   
        'download' => $download,
        'img' => $img
      ]);
   }
}
