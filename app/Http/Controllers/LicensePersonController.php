<?php

namespace App\Http\Controllers;

use App\Facades\AuthFacade;
use Illuminate\Http\Request;

class LicensePersonController extends Controller
{
    public function indexOperate(){
        if(AuthFacade::useradmin()){
            return view("admin.index.index");
        }
        return redirect()->route('login.admin');
    }

    public function indexParameter(){
        if(AuthFacade::useradmin()){
            return view("admin.index.index");
        }
        return redirect()->route('login.admin');
    }


}
