<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductCategory;

class ProductController extends Controller
{

    public function createProduct(Request $product)
    {
        return Product::create([
            'name'=>$product->name,
            'product_category_id'=>$product->product_category_id,
            'price'=>$product->price,
            'remaining_quantity'=>$product->quantity,
        ]);
    }
    public function getProducts()
    {
        return Product::get();
    }

    public function createProductCategory(Request $prd)
    {
        return ProductCategory::create([
            'name'=>$prd->name,
        ]);
    }
    public function getProductCategories(Request $prd)
    {
        return ProductCategory::get();
    }

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
