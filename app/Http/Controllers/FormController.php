<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class FormController extends Controller
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

    public function submit(Request $request)
    {
        $result = $request->all();
        
        $user_id = Auth::id();
        $user = Auth::user();
        $username = $user["name"];
        $testPoint = $result["test-point"];
        $questionCount = $result["question-count"];

        DB::insert('insert into result (user_id, score) values (?, ?)', [$user_id, $testPoint]);
        $result_id = DB::getPdo()->lastInsertId();

        for ($i=0; $i < $questionCount; $i++) { 
            $thisAnswer = array_key_exists("question-".$i,$result)?$result["question-".$i]:"";
            DB::insert('insert into result_detail (result_id, answer_id, answer) values (?, ?, ?)',
            [$result_id, $result["question-".$i."-id"], $thisAnswer]);
        }
        $request->session()->forget('nowTime');

        $mailContent = "ユーザ\"".$username."\"のテスト得点は".$testPoint."点でした";
        /*Mail::raw($mailContent, function($message){
            $message->from("info@psttec.com", "psttec");

            $message->to("sales@psttec.co.jp");

            $message->subject("テスト得点");
        });*/
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");
        $subject='EXAM FINISHED';
        $to      = "sales@psttec.com";
        $headers = 'From: info@psttec.com' . "\r\n";
        mb_send_mail($to, $subject,$mailContent , $headers);

        return view('final', ['result' => $testPoint]);
    }
}
