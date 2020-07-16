@extends('layouts.app')

@section('css')
    @parent
    <style type="text/css">
        .head-time{
            display: inline-block;
            margin: 0;
        }
        .right-float{
            float: right;
        }
        .inner-table{
            width: 100%;
        }
        .inner-table th, .inner-table td{
            border: 0;
        }
        .inner-table td{
            width: 25%;
        }
        .red-number{
            color: red;
            font-size: 20px;
        }
        .question-now{
            border: none;
            background: transparent;
            width: 2rem;
            text-align: center;
        }
    </style>

@endsection
    
@section('scripts')
    @parent
    <script type="text/javascript">
        $(function(){
            restTimeSet();

            var $question = $(".table");
            $question.css("display","none");
            var questionNum = $question.length;
            $("#question-sum").text(questionNum);
            $("#question-now").val(1);
            $("#question-now").trigger("onchange");

            $("input[type=radio]").change(function(){
                var $this = $(this);
                var selectedAnswer = $this.val();
                var questionNum = $this.attr("name").substr($this.attr("name").indexOf("-")+1);
                var correctAnswer = $("input[name='answer-"+questionNum+"']").val();
                var point = $("input[name='point-"+questionNum+"']").val();
                if(selectedAnswer === correctAnswer)$("input[name='score-"+questionNum+"']").val(point);
                else $("input[name='score-"+questionNum+"']").val(0)
                sumPointCal();
            })
        })
        function questionChange($this){
            var questionNow = $this.value;
            var questionSum = parseInt($("#question-sum").text());
            var $question = $(".table");
            $question.css("display","none");
            $(".table:eq("+(questionNow-1)+")").css("display","");
            if(questionSum <= questionNow){
                $("#commit-btn").css("visibility","visible");
            }else{
                $("#commit-btn").css("visibility","hidden");
            }
        }
        function nextQuestion(){
            var now = parseInt($("#question-now").val());
            var sum = parseInt($("#question-sum").text());
            var nextQuestionNum = now+1;
            if(sum <= now){
                nextQuestionNum = 1;
            }
            $("#question-now").val(nextQuestionNum);
            $("#question-now").trigger("onchange");
        }
        function lastQuestion(){
            var now = parseInt($("#question-now").val());
            var sum = parseInt($("#question-sum").text());
            var nextQuestionNum = now-1;
            if(now <= 1){
                nextQuestionNum = sum;
            }
            $("#question-now").val(nextQuestionNum);
            $("#question-now").trigger("onchange");
        }
        function sumPointCal (){
            var $testPoint = $("input[name='test-point']");
            var point = 0;
            var $scoreArr = $("input[class='score']");
            for (var i = $scoreArr.length - 1; i >= 0; i--) {
                point += parseInt($($scoreArr[i]).val());
            }
            $testPoint.val(point);
        }
        function restTimeSet(){
            var testTime = 50;//テスト時間（分）
            var testTime_s = testTime*60*1000;//テスト秒数

            var $startTime = $("#start-time").text();
            var startTime = new Date($startTime);
            var nowTime = new Date();
            var startTime_s = startTime.getTime();//開始秒数
            var nowTime_s = nowTime.getTime();//現在秒数
            var elapsedTime_s = nowTime_s - startTime_s;//経った秒数
            var restTime_s = parseInt((testTime_s - elapsedTime_s)/1000);
            if(restTime_s<=0){
                $("#commit-btn").trigger("click");
                return;
            }
            $("#rest-time").text(formatSeconds(restTime_s));
            setTimeout(restTimeSet,1000);
        }
        function formatSeconds(value) {
            var theTime = parseInt(value);// 秒
            var middle= 0;// 分
            var hour= 0;// 時間

            if(theTime > 60) {
                middle= parseInt(theTime/60);
                theTime = parseInt(theTime%60);
                if(middle> 60) {
                    hour= parseInt(middle/60);
                    middle= parseInt(middle%60);
                }
            }
            var result = ""+parseInt(theTime)+"秒";
            if(middle > 0) {
                result = ""+parseInt(middle)+"分"+result;
            }
            if(hour> 0) {
                result = ""+parseInt(hour)+"時間"+result;
            }
            return result;
        }
    </script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <p class="head-time">開始時間 <span id="start-time">{{ Session::get('nowTime') }}</span></p>
                    <p class="head-time right-float">残り時間  <span id="rest-time"></span></p>
                </div>
                <form action="{{ route('submit') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <table>
                            <input type="hidden" name="test-point" value="0">
                            <input type="hidden" name="question-count" value="{{sizeof($questions)}}">
                            <tbody>
                                @foreach($questions as $key => $question)
                                <tr>
                                    <table class="table">
                                        <input type="hidden" name="question-{{$key}}-id" value="{{$question -> q_id}}">
                                        <tr>
                                            <td width="10%">
                                                問題{{$key+1}}
                                            </td>
                                            <td width="90%">
                                                {{$question -> content}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%">
                                            </td>
                                            <td width="90%">
                                                <table class="inner-table">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input type="radio" name="question-{{$key}}" value = "{{'a'}}">
                                                                    A. {{$question -> answer_a}}
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input type="radio" name="question-{{$key}}" value = "{{'b'}}">
                                                                    B. {{$question -> answer_b}}
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input type="radio" name="question-{{$key}}" value = "{{'c'}}">
                                                                    C. {{$question -> answer_c}}
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input type="radio" name="question-{{$key}}" value = "{{'d'}}">
                                                                    D. {{$question -> answer_d}}
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </tr>
                                <input type="hidden" name="answer-{{$key}}" value="{{$question -> answer}}">
                                <input type="hidden" name="point-{{$key}}" value="{{$question -> point}}">
                                <input type="hidden" class="score" name="score-{{$key}}" value="0">
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <p class="head-time">
                            第 <input id="question-now" class="question-now red-number" readonly type="text" onchange="questionChange(this)"> 問 / 総計 
                            <span id="question-sum" class="red-number"></span> 問
                        </p>
                        <button type="sumbit" id="commit-btn" style="visibility: hidden;" class="btn btn-danger right-float">提出</button>
                        <button type="button" style="margin-right: 0.375rem;" class="btn btn-primary right-float" onclick="nextQuestion()">次へ</button>
                        <button type="button" style="margin-right: 0.375rem;" class="btn btn-primary right-float" onclick="lastQuestion()">前へ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
