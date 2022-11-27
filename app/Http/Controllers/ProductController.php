<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function addSuperadminProduct(){
        return view('superadmin.products.add.add');
    }
    public function viewSuperadminProduct(){
        return view('superadmin.products.view.view');
    }
    public function addAdminProduct(){
        return view('admin.products.add.add');
    }
    public function viewAdminProduct(){
        return view('admin.products.view.view');
    }
    public function viewFrontdesktProduct(){
        return view('frontdesk.products.index');
    }
}
