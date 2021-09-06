<style>
    .modal-backdrop {
        z-index: 0;
    }

    .modal-backdrop {
        opacity: 0 !important;
    }

    .modal {
        top: 150px;
    }

    .account-item {
        background: cornflowerblue;
        padding: 10px;
        border-radius: 5px;
        margin: 3px;
        color: white;
        line-height: 37px;
    }
</style>

<!-- Name Field -->
<div class="form-group col-sm-5 col-lg-5">
    {!! Form::label('name_ru', 'Name (RU):') !!}
    {!! Form::text('name_ru', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-5 col-lg-5">
    {!! Form::label('name_en', 'Name (EN):') !!}
    {!! Form::text('name_en', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-2 col-lg-2">
    {!! Form::label('order', 'Order:') !!}
    {!! Form::text('order', null, ['class' => 'form-control']) !!}
</div>
<!-- Name Field -->
<div class="form-group col-sm-6 col-lg-6">
    {!! Form::label('info_ru', 'Info (RU):') !!}
    {!! Form::text('info_ru', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-6">
    {!! Form::label('info', 'Info (EN):') !!}
    {!! Form::text('info', null, ['class' => 'form-control']) !!}
</div>

<!-- icon_class Field -->
<div class="form-group col-sm-3 col-lg-3">
    {!! Form::label('icon_class', 'Icon Class:') !!}
    {!! Form::text('icon_class', null, ['class' => 'form-control']) !!}
</div>
<!-- icon_class Field -->
<div class="form-group col-sm-3 col-lg-3" style="min-height: 59px">
    {!! Form::label('icon_img', 'Icon Image:') !!}
    <div style="display: flex">
        {!! Form::file('icon_img') !!}
        @if(!empty($product->icon_img))
            <img src="/{{$product->icon_img}}" width="24" alt="">
        @endif
    </div>
</div>

<!-- icon_class Field -->
<div class="form-group col-sm-3 col-lg-3" style="min-height: 59px">
    {!! Form::label('icon_img_active', 'Icon Image Active:') !!}
    <div style="display: flex">
        {!! Form::file('icon_img_active') !!}
        @if(!empty($product->icon_img_active))
            <img src="/{{$product->icon_img_active}}" width="24" alt="">
        @endif
    </div>
</div>

<!-- Parent Field -->
<div class="form-group col-sm-4">
    {!! Form::label('account_category_id', 'Account Category:') !!}
    {!! Form::select('account_category_id', $categories, null, ['class' => 'form-control', 'placeholder' => '']) !!}
</div>


<!-- URL Field -->
<div class="form-group col-sm-3">
    {!! Form::label('url', 'URL:') !!}
    {!! Form::text('url', null, ['class' => 'form-control']) !!}
</div>

<!-- Price Field -->
<div class="form-group col-sm-3">
    {!! Form::label('price', 'Price:') !!}
    {!! Form::number('price', null, ['class' => 'form-control']) !!}
</div>

<!-- Button trigger modal -->
<div class="form-group col-sm-12 col-lg-12">

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#accountAddModal">
        Добавить аккаунты
    </button>
</div>

<div id="accounts"  @if(!isset($product) || $product->productItems->isEmpty())  hidden @endif class="col-sm-12 col-lg-12">
    <h3>Accounts data</h3>
    <div id="exist_aacount">
        @if(isset($product) && !$product->productItems->isEmpty())
            @foreach($product->productItems as $key => $item)
                <div class="acc_row{{$key}}">
                    <div class="form-group col-sm-4 col-lg-4">
                        <label for="account">Account</label>
                        <input class="form-control" name="exist_account[{{$key}}][username]" value="{{$item->username}}"
                               type="text">
                    </div>
{{--                    <div class="form-group col-sm-3 col-lg-3">--}}
{{--                        <label for="account">Password</label>--}}
{{--                        <input class="form-control" name="exist_account[{{$key}}][password]" value="{{$item->password}}"--}}
{{--                               type="text">--}}
{{--                    </div>--}}
{{--                    <div class="form-group col-sm-2 col-lg-2">--}}
{{--                        <label for="port">Port</label>--}}
{{--                        <input class="form-control" name="exist_account[{{$key}}][port]" value="{{$item->port}}"--}}
{{--                               type="text">--}}
{{--                    </div>--}}
{{--                    <div class="form-group col-sm-2 col-lg-2">--}}
{{--                        <label for="ip">Ip</label>--}}
{{--                        <input class="form-control" name="exist_account[{{$key}}][ip]" value="{{$item->ip}}"--}}
{{--                               type="text">--}}
{{--                    </div>--}}
                    <div class="form-group col-sm-2 col-lg-2" style="margin-top: 28px;">
                        <button class="button btn-danger" onclick="removeAcc(event, '.acc_row' + {{$key}})"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div id="account_items"></div>

</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.products.index') !!}" class="btn btn-default">Cancel</a>
</div>


<!-- Modal -->
<div class="modal fade" id="accountAddModal" tabindex="-1" role="dialog"
     aria-labelledby="accountAddModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Добавить аккаунты</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Example (username:password, username:password)</label>
                    <textarea id="acc_input" oninput="proceedAccounts()" class="form-control"
                              id="exampleFormControlTextarea1" rows="3"></textarea>
                    <div id="result" style="padding: 10px"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="addInputs()">Добавить</button>
            </div>
        </div>
    </div>
</div>
<!-- /row -->

<script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
<script>
    var accounts = '';
    // var res = '';

    function addInputs() {
        $('#accounts').show()
        $('#account_items').html(accounts)
        accounts = '';
    }

    function proceedAccounts(event) {

            var accounts_data = $('#acc_input').val().split(',');
            accounts = '';
            for (let i = 0; i < accounts_data.length; i++) {
                var usernameAndPass = accounts_data[i].split(':');
                // res = res + `<span class="account-item">uname:  ${usernameAndPass[0]}  psw: ${usernameAndPass[1]};</span>`
                if(accounts_data[i]){

                    accounts += `
                    <div class="new_acc_row${i}">
                        <div class="form-group col-sm-4 col-lg-4">
                             <label for="account">Account</label>
                             <input class="form-control" name="account[${i}][username]" value="${accounts_data[i]}" type="text">
                        </div>
                        <div class="form-group col-sm-2 col-lg-2" style="margin-top: 28px;">
                            <button class="button btn-danger" onclick="removeAcc(event, '.new_acc_row' + ${i})"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>`
                }

            }
            // $('#result').html(res)
            // res = ''

    }

    function removeAcc(e, id) {
        e.preventDefault()
        $(id).remove()
    }

</script>
