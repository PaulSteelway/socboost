$(function () {
    $('select[name="options[mass_posts_count]"]').change(function (e) {
        // console.log($(this).val());
        if ($(this).val() == '0') {
            $('.input_mass_posts_count').show();
        } else {
            $('.input_mass_posts_count').hide();
            $('input[name="parse_posts_count"]').val($(this).val());
        }
    });
    //
    $('select[name="options[future_posts_count_day]"]').change(function (e) {
        // console.log($(this).val());
        if ($(this).val() == '0') {
            $('.input_mass_posts_count_day').show();
        } else {
            $('.input_mass_posts_count_day').hide();
            $('input[name="parse_posts_count_day"]').val($(this).val());
        }
    });

    $('select[name="options[future_posts_count_month]"]').change(function (e) {
        // console.log($(this).val());
        if ($(this).val() == '0') {
            $('.input_mass_posts_count_month').show();
        } else {
            $('.input_mass_posts_count_month').hide();
            $('input[name="parse_posts_count_month"]').val($(this).val());
        }
    });

    $('.only_digits').keyup(function () {
        if ($(this).val()) $(this).val(parseInt($(this).val()));
    });

    $('.mass_parse_table').on('click', '.delete_row', function () {
        $(this).parent().parent().remove();
        var total_count = 0;
        $('.row_count').each(function () {
            total_count = total_count + parseInt($(this).html());
        });
        $('input[name="count"]').val(total_count);
        calc_order_sum();
    });

    $("input[name='parse_posts_count'],input[name='from_parse_likes_count'],input[name='parse_likes_count']").on("change keyup paste click", function () {
        var total_posts = $("input[name='parse_posts_count']").val();
        var rand_from = parseInt($("input[name='from_parse_likes_count']").val());
        var rand_to = parseInt($("input[name='parse_likes_count']").val());
        var total_likes = 0;

        var data = [];
        for (i = 0; i < total_posts; i++) {
            var current_rand = Math.floor(Math.random() * (rand_to - rand_from + 1)) + rand_from;
            data.push(current_rand);
            total_likes += current_rand;
        }
        $('input[name="count"]').val(total_likes);
        $('input[name="random_values"]').val(data.toString());

        calc_order_sum();

    });


    $('#preload').hide();

    var locale  = $('#curLoc').val();
    var start_option = $('select.packet-options option:selected');
    if (typeof (start_option.data('price')) !== 'undefined') {
        socialboosterPriceByAmount(start_option.data('price'), start_option.data('lang'));
    }
    if (typeof (start_option.data('min')) !== 'undefined' && typeof (start_option.data('max')) !== 'undefined') {
        $('#count_limits').html(start_option.data('min') + ' - ' + start_option.data('max'));
        $('.minMaxLimits').html(start_option.data('min') + ' - ' + start_option.data('max'));
    }
    if (typeof (start_option.data('item')) !== 'undefined') {
        setPacketTitleConditions(locale, start_option.data('item'));
        setInputWBtnSteps(start_option.data('item'));
    }
    $('select.packet-options').change(function () {
        var selected_option = $('select.packet-options option:selected');
        socialboosterPriceByAmount(selected_option.data('price'), start_option.data('lang'));
        $('#count_limits').html(selected_option.data('min') + ' - ' + selected_option.data('max'));
        $('.minMaxLimits').html(selected_option.data('min') + ' - ' + selected_option.data('max'));
        setPacketTitleConditions(locale, selected_option.data('item'));
        setInputWBtnSteps(selected_option.data('item'), true);
        disableReplenishLink();
        calc_order_sum();
    });

    $('select.shop-options-s').change(function () {
        calc_order_sum();
    });
    $('input[name="count"]').keyup(function () {
        calc_order_sum();
    });
    $('#orderPosts, #orderQtyMax').change(function () {
        calc_order_sum();
    });
    calc_order_sum();

    $('.current-service__packet-link').attr('placeholder', $('select#packet option:selected').data('link'));
    $('select#packet').change(function () {
        $('.current-service__packet-link').attr('placeholder', $('select#packet option:selected').data('link'));
    });

    $('.inp-w-btn input').before('<span class="inpMinus">-</span>').after('<span class="inpPlus">+</span>');
    $('.inpMinus').click(function () {
        let inputQty = $(this).parent().find(':input');
        let step = parseInt(inputQty.data('step'));
        let newValue = !inputQty.val() || parseInt(inputQty.val()) < (step + 1) ? 0 : parseInt(inputQty.val()) - step;
        inputQty.val(newValue);
        calc_order_sum();
    });
    $('.inpPlus').click(function () {
        let inputQty = $(this).parent().find(':input');
        let step = parseInt(inputQty.data('step'));
        let newValue = !inputQty.val() ? step : parseInt(inputQty.val()) + step;
        inputQty.val(newValue);
        calc_order_sum();
    });

    $('.subs-close-btn').click(function () {
        let subscription_id = $(this).data('id');
        let confirmed = confirm(`Do you really want to close Subscription #${subscription_id}?`);
        if (confirmed) {
            $.ajax({
                url: `/subscriptions/${subscription_id}`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'DELETE',
                success: function (response) {
                    location.reload();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    });
});


function percentage(num, per) {
    discount = 100 - per;
    return (num / 100) * discount;
}

function calc_order_sum() {

    var price_one_full = parseFloat($('#price_base').text());
    // console.log('price_one_full = ', price_one_full);

    $('select.shop-options-s option:selected').each(function () {
        price_one_full += parseFloat($(this).attr('class'));
    });

    var count = 0;
    if ($('#orderPosts').length) {
        let posts = $('#orderPosts').val();
        let qtyMax = $('#orderQtyMax').val();
        count = (posts ? parseInt(posts) : 0) * (qtyMax ? parseInt(qtyMax) : 0);
    } else {
        count = $('input[name="count"]').val();
        if (count == '') {
            count = 0;
        } else {
            count = parseInt(count);
        }
    }
    var discount = +$('#dsc').val();

    var price_all = price_one_full * count;
    price_all = price_all * 100;
    var discount_price = percentage(price_all, discount);
    price_all = Math.round(price_all);
    discount_price = Math.round(discount_price);
    price_all = price_all / 100;
    discount_price = discount_price / 100;
    if(discount && discount !== 0){
        $('.priceAll').html(price_all).css({color:'grey', 'text-decoration-line': 'line-through', 'text-decoration-color': 'red'});
        $('.priceWithDiscount').html(discount_price).css({color:'black', 'text-decoration': 'none'});
    }else{
        $('.priceAll').html(price_all);
        $('#discountPrice').html('');
    }
    if (isAuth) {
        checkBalance(count);
    }
}

function socialboosterPriceByAmount(price, lang) {
    if (lang === 'en') {
        $.ajax({
            type: 'GET',
            url: '/price_convert',
            data: {'price': price},
            success: function (res) {
                    $('#price_base').html('<strong>' + res['price'] + '</strong>');
                    $('.price_info').html('<strong>' + res['price'] + '</strong>');
                $('.price_info_sum').html('<strong>' + priceRound(res['price'] * 250) + '</strong>');

                calc_order_sum();
            },
            error: function (error) {
                console.log(error);
                $('#price_base').html('<strong>' + price + '</strong>');
                $('.price_info').html('<strong>' + price + '</strong>');
                $('.price_info_sum').html('<strong>' + priceRound(price * 250) + '</strong>');

                calc_order_sum();
            },
        });
    } else {
        $('#price_base').html('<strong>' + price + '</strong>');
        $('.price_info').html('<strong>' + price + '</strong>');
        $('.price_info_sum').html('<strong>' + priceRound(price * 250) + '</strong>');
        calc_order_sum();
    }
}

function checkBalance(qty) {
    $.ajax({
        type: 'GET',
        url: '/check_balance',
        data: {
            'qty': qty,
            'price': $('select.packet-options option:selected').data('price')
        },
        success: function (res) {
            if (res['link']) {
                $('#replenishLink').attr('href', res['link']);
                $('#shortageBlock').show();
            } else {
                disableReplenishLink();
            }
        },
        error: function (error) {
            console.log(error);
        },
    });
}

function priceRound(price) {
    return (Math.floor(price * 100) / 100);
}

function setPacketTitleConditions(locale, item) {
    $('#title1').text(locale === 'en' ? item['icon_title1'] : item['icon_title1_ru']);
    $('#subtitle1').text(locale === 'en' ? item['icon_subtitle1'] : item['icon_subtitle1_ru']);

    $('#title2').text(locale === 'en' ? item['icon_title2'] : item['icon_title2_ru']);
    $('#subtitle2').text(locale === 'en' ? item['icon_subtitle2'] : item['icon_subtitle2_ru']);

    $('#title3').text(locale === 'en' ? item['icon_title3'] : item['icon_title3_ru']);
    $('#subtitle3').text(locale === 'en' ? item['icon_subtitle3'] : item['icon_subtitle3_ru']);

    $('#title4').text(locale === 'en' ? item['icon_title4'] : item['icon_title4_ru']);
    $('#subtitle4').text(locale === 'en' ? item['icon_subtitle4'] : item['icon_subtitle4_ru']);

    let alertBlock = $('.service-page__alert');
    let alertMessage = $('.service-page__alert-text');
    if (locale === 'en') {
        if (item['info_en']) {
            alertMessage.text(item['info_en']);
            alertBlock.show();
        } else {
            alertBlock.hide();
        }
    } else {
        if (item['info_ru']) {
            alertMessage.text(item['info_ru']);
            alertBlock.show();
        } else {
            alertBlock.hide();
        }
    }
}

function setInputWBtnSteps(item, update = false) {
    $('.inp-w-btn input').each(function () {
        if ($(this).attr('id').includes('Qty')) {
            $(this).data('step', item['step']);
            if (!($(this).val()) || update) {
                $(this).val(item['step']);
            }
            if (item['step_fixed'] === 1) {
                $(this).attr('readonly', true);
            } else {
                $(this).attr('readonly', false);
            }
        }
    });
}

function disableReplenishLink() {
    $('#replenishLink').attr('href', '#');
    $('#shortageBlock').hide();
}

function submitMainOrderForm(event) {
    event.preventDefault();
    $('#mainOrderForm').submit();
}
