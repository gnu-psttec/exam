@extends('layouts.app')

@section('scripts')
    @parent
    <script type="text/javascript">
        var waitTime = 5;
        var waitTimeFunc = function(){
            waitTime--;
            $("#wait-time").text(waitTime);
            if(waitTime<=0){
                clearInterval(waitTimeFunc);
                window.location.href = "test";
            }
        }
        setInterval(waitTimeFunc, 1000);
    </script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('ようこそ') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('ログイン成功') }}
                    <p>テスト開始するまであと <span id="wait-time" style="color: red; font-size: 20px">5</span> 秒</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
