@extends('layouts.app')

@section('scripts')
    @parent
    <script type="text/javascript">
        var windowClose = function(){
            $("a[class='dropdown-item']").trigger("click");
        }
    </script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('テスト終わり') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('お疲れ様でした') }}
                    <p>得点は <span id="wait-time" style="color: red; font-size: 20px">{{$result}}</span> 点でした</p>
                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button id="close" onclick="windowClose()" class="btn btn-primary">
                                {{ __('戻る') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
