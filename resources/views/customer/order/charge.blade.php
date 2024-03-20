<div class="row">
    <div class="label-name col-md-4">{{ __("Charge") }}:</div>
    <div class="price-block col-md-8" style="display: flex; flex-wrap: wrap;">
        <div>
            <span class="priceAll">~</span>
            <em>$</em>
        </div>
        @auth
            <div id="discountPrice">
                <span class="priceAll priceWithDiscount">1500</span>
                <em class>$</em>
            </div>
        @endauth
        <div style="flex-basis: 100%; height: 0;"></div>
        <div id="shortageBlock" style="display: none;">
            <small>
                {{__('Not enough money on the wallet balance.')}}
                <a href="#" target="_blank" id="replenishLink">{{__('Replenish')}}</a>
            </small>
        </div>
    </div>
</div>
