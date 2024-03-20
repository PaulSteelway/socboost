<div class="how-much-earn">
    <div class="how-much-earn__title">{{__('Сколько можно зарабатывать?')}}</div>
    <div class="how-much-earn__blocks">
        <div class="how-much-earn__block">
            <label for="amount" class="how-much-earn__label">{{__('Количество подписчиков:')}}</label>
            <input type="text" class="how-much-earn__input" id="amount" readonly>
            <div class="range-slider-container">
                <span>0</span>
                <div id="slider1">
                    <div id="custom-handle-1" class="ui-slider-handle"></div>
                </div>
                <span>1M</span>
            </div>
        </div>
        <div class="how-much-earn__block">
            <label for="amount2" class="how-much-earn__label">{{__('Ежемесячный доход:')}}</label>
            <input type="text" id="amount2" class="how-much-earn__input" readonly>
            <div class="range-slider-container">
                <span>0</span>
                <div id="slider2">
                    <div id="custom-handle-2" class="ui-slider-handle"></div>
                </div>
                <span>{{number_format(socialboosterPriceByAmount(500000), 2, '.', '')}}$</span>
            </div>
        </div>
    </div>
</div>
