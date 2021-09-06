@include('partials.inform')

<form name="checkout" method="post" action="{{ route('checkout')}}">

    <div class="row">
        <div class="label-name col-md-4">{{ __("Packet:") }}</div>
        <div class="col-md-8">
            <select class="form-control packet-options"
                    name="packet" {{ count($packets) == 1 ? 'readonly="readonly"' : '' }}>
                @foreach($packets as $key => $packet)
                    @if($packet['only_for_vip'] && (Auth::guest() || !Auth::user()->is_premium))
                        @continue
                    @endif
                    <option value="{{$packet['id']}}" data-price="{{$packet['price']}}" data-lang="{{app()->getLocale()}}"
                            data-min="{{$packet['min']}}" data-max="{{$packet['max']}}"
                            {{ (old('packet') && old('packet') == $packet['id']) ? 'selected' : ($key == 0 ? 'selected' : '') }}>
                        {{ app()->getLocale() == 'en' ? $packet['name_en'] : $packet['name_ru'] }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="label-name col-md-4">{{ __("Price / 1:") }}</div>
        <div class="col-md-8 priceOne">
            <span id="price_base"></span>
            {{ app()->getLocale() == 'en' ? '$' : '₽' }}
        </div>
    </div>


    <div class="row">
        <div class="label-name col-md-4">{{ __("Quantity") }}:</div>
        <div class="col-md-8">
            <input name="count" class="form-control" type="text" id="orderQty"
                   value="{{ old('count') ? old('count') : 1}}" required>
            <div>
                <small>{{__('Limits:')}} <span id="count_limits"></span></small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="label-name col-md-4">{{ __("Link") }}:</div>
        <div class="col-md-8">
            <input name="link" class="form-control" type="text" value="{{ old('link') }}" required>
        </div>
    </div>

    @include('customer.order.charge')

    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-8">
            <input type="submit" class="btn-success" value="{{ __("Submit") }}">
        </div>
    </div>

    {{ csrf_field() }}
</form>
