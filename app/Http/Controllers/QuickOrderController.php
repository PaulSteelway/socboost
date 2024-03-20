<?php

namespace App\Http\Controllers;
use App;
use App\Models\Category;
use App\Models\Packet;
use Illuminate\Http\Request;

class QuickOrderController extends Controller
{

    /**
     * Display a listing of categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $currentLocale = App::getLocale();

        $categories = Category::whereNull('parent_id')->select('id', "name_$currentLocale as name")->get();
        if ($categories->isEmpty()) {
            return response()->json(['status' => 'error', 'code' => 404], 404);
        }


        
        return response()->json(['status'=>'success','result' => $categories], 200);
    }
    public function show($id)
    {
        $currentLocale = App::getLocale();

        $categories = Category::where('parent_id', $id)
            ->select('id', "name_$currentLocale as name")
            ->get();

        if ($categories->isEmpty()) {
            $packets = Packet::where('category_id', $id)->get();

            if ($packets->isNotEmpty()) {
                return response()->json(['status' => 'orders', 'result' => $packets], 200);
            }

            return response()->json(['status' => 'error', 'code' => 404], 404);
        }

        return response()->json(['status' => 'success', 'result' => $categories], 200);
    }
}
