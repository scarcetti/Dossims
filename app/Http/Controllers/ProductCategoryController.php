<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    public function createProductCategory(Request $prd)
    {
        return ProductCategory::create([
            'name'=>$prd->name,
        ]);
    }

    public function updateProductCategory(Request $request)
    {
        $prod_category_data = $request->all();
        $prod_category = ProductCategory::where('id',$request->id)->first();
        $prod_category->update($prod_category_data);
        return $prod_category;
    }

    public function deleteProductCategory($id)
    {
        return ProductCategory::where('id',$id)->delete();
    }

    public function fetchAllProductCategories(Request $prd)
    {
        return ProductCategory::get();
    }

    public function fetchProductCategoryById($id)
    {
        return ProductCategory::where('id',$id)->first();
    }

}
