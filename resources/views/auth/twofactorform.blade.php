@extends(isFreePromotionSite() ? 'layouts.free_promotion.layout' : 'layouts.customer')

{{-- @section('title', __('Register')) --}}

@section('content')
    <div class="et_pb_section  et_pb_section_2 et_pb_with_background et_section_regular">
        <div class="container">
            <div class="card card-outline-secondary" style="padding:30px 0 30px 0;">
                <div class="row">
                    <div class="col-lg-4"></div>
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                @if(session()->get('sended'))
                                    <center><p>Code sent to you {{ session()->get('sended') }}</p></center>
                                    <form name="input_code" class="form-horizontal" method="POST"
                                          action="{{ route('auth.enter.token') }}">
                                        {{ csrf_field() }}
                                        <label for="token" class="col-md-4 control-label">Input code</label>
                                        <div class="col-md-4">
                                            <input id="token" type="text" class="form-control" name="token"
                                                   placeholder="type you code" required autofocus>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            Enter with code
                                        </button>
                                    </form>
                                @else
                                    <form name="send" class="form-group" method="POST"
                                          action="{{ route('auth.send.token') }}">
                                        {{ csrf_field() }}
                                        <div class="col-md-6 col-md-offset-4">
                                            <div class="radio">
                                                <p>choice of the method code transfer</p>
                                                <p>
                                                    <input type="radio" name="choise"
                                                           id="email" value="email" checked>
                                                    <label for="email">Email</label>
                                                </p>
                                                <p>
                                                    <input type="radio" name="choise"
                                                           id="phone" value="phone">
                                                    <label for="phone">Phone</label>
                                                </p>
                                            </div>
                                            <button type="submit" class="btn btn-danger">
                                                Send code
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
