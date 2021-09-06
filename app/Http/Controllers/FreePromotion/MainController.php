<?php namespace App\Http\Controllers\FreePromotion;


class MainController
{
    public function index()
    {
        if(\Auth::guest()){
            return view('free_promotion.index');
        }else{
            return redirect(route('freePromotion.task.tasklist'));
        }
    }

}
