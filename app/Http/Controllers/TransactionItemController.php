<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionItem;

class TransactionItemController extends Controller
{
    public function createTransactionItem(Request $item)
    {
        return TransactionItem::create([
            'transaction_id'=>$item->transaction_id,
            'product_id'=>$item->product_id,
            'price_at_purchase'=>$item->price_at_purchase,
            'quantity'=>$item->quantity,
        ]);
    }

    public function updateTransactionItem(Request $request)
    {
        $transactionItem_data = $request->all();
        $transactionItem = TransactionItem::where('id',$request->id)->first();
        $transactionItem->update($transactionItem_data);
        return $transactionItem;
    }

    public function deleteTransactionItem($id)
    {
        return TransactionItem::where('id',$id)->delete();
    }

    public function fetchAllTransactionItems()
    {
        return TransactionItem::get();
    }
    public function fetchTransactionItemById($id)
    {
        return TransactionItem::where('id',$id)->first();
    }
    
}
