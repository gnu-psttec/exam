<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (!$request->session()->has('nowTime')) {
            date_default_timezone_set('Asia/Tokyo');
            $now = date('Y-m-d H:i',time());
            $request->session()->put('nowTime', $now);
        }
        $questions = DB::select('select q.q_id as q_id, q.content as content, a.answer_a as answer_a, a.answer_b as answer_b, a.answer_c as answer_c, a.answer_d as answer_d, a.answer as answer, a.point as point from question q inner join supplier_master a on q.q_id = a.q_id where q.delete_flg = 0 and a.delete_flg = 0');
    
        return view('test', ['questions' => $questions]);
    }
}
