$(document).ready(function() {

    // function openCloudpaymentsWidget() {
    //     if (typeof cloudpayments !== 'undefined') {
    //         var widgetCloudPayments = new cp.CloudPayments({language: cloudpayments['language']});
    //         widgetCloudPayments.charge(
    //             cloudpayments['options'],
    //             function () {
    //                 window.location.replace(cloudpayments['routeSuccess']);
    //             },
    //             function () {
    //                 window.location.replace(cloudpayments['routeFail']);
    //             }
    //         );
    //     }
    // }

    // function openCloudpaymentsWidgetAutoOrder(cloudpayments) {
    //     var widgetCloudPayments = new cp.CloudPayments({language: cloudpayments['language']});
    //     widgetCloudPayments.charge(
    //         cloudpayments['options'],
    //         function () {
    //             $('#mainOrderForm').submit();
    //         },
    //         function (reason, options) {
    //             console.log(reason);
    //             console.log(options);
    //         }
    //     );
    // }


    $('#faq__accordion').on('show.bs.collapse', (function () {
        $('#faq__accordion .show').collapse('hide');
    }));

    $('.header__menu-hamburger').on('click', (function () {
        $(this).closest('.header__menu').toggleClass('header__menu--opened');
        $(this).closest('html').toggleClass('menu-opened');
    }))
    $('.has-submenu').on('click', (function (e) {
        $(this).toggleClass('submenu--opened');
    }))
    $('.submenu__item').on('click', (function (e) {
        e.stopPropagation();
        $(this).toggleClass('submenu--level2-opened');
    }))
    if ($(window).width() < 992) {
        $('.header__login--logined').on('click', (function () {
            if($(this).hasClass('header__login--logined-opened')){
                $(this).removeClass('header__login--logined-opened');
            }
            else{
                $(this).addClass('header__login--logined-opened');
            }
        }));
    }
    $('.history__td--left').on('click', (function () {
        $('.history__tr').removeClass('history__tr--opened');
        if($(this).closest('.history__tr').hasClass('history__tr--opened')){
            $(this).closest('.history__tr').removeClass('history__tr--opened');
        }
        else{
            $(this).closest('.history__tr').addClass('history__tr--opened');
        }
    }))
    $('.prices__td--left').on('click', (function () {
        $('.prices__tr').removeClass('prices__tr--opened');
        if($(this).closest('.prices__tr').hasClass('prices__tr--opened')){
            $(this).closest('.prices__tr').removeClass('prices__tr--opened');
        }
        else{
            $(this).closest('.prices__tr').addClass('prices__tr--opened');
        }
    }))

    $('.trigger-btn').on('click', (function(){
        $('#service-page__accordion').toggleClass('accordion-opened');
    }));

    $('#open-review').on('click', (function(){
        $('.leave-review').addClass('opened');
    }));
    $('.leave-review .close').on('click', (function(){
        $('.leave-review').removeClass('opened');
    }));
    $('.leave-review .leave-review__close').on('click', (function(){
        $('.leave-review').removeClass('opened');
    }));
    $('#review-file').on('change', function(){
        $('#upload-file-info').html(this.files[0].name);
    })

    $(function(){
        let handle1 = $( "#custom-handle-1" );
        let handle2 = $( "#custom-handle-2" );

        $("#slider1").slider({
            range: "max",
            value: 0,
            min: 0,
            max: 2000000,
            step: 1000,
            create: function() {
                handle1.text( $( this ).slider( "value" ) );
            },
            slide: function( event, ui )
            {
                $( "#amount" ).val( ui.value);
                handle1.text( ui.value );
                if(ui.value < 100000) {handle2.text( '0' );}
                else if(ui.value >= 200000 && ui.value < 400000) {$("#slider2").slider("option", "value", "200000"); handle2.text( '200000' );}
                else if(ui.value >= 400000 && ui.value < 600000) {$("#slider2").slider("option", "value", "400000"); handle2.text( '400000' );}
                else if(ui.value >= 600000 && ui.value < 800000) {$("#slider2").slider("option", "value", "600000"); handle2.text( '600000' );}
                else if(ui.value >= 800000 && ui.value < 1000000) {$("#slider2").slider("option", "value", "800000"); handle2.text( '800000' );}
                else if(ui.value >= 1000000 && ui.value < 1200000) {$("#slider2").slider("option", "value", "1000000"); handle2.text( '1000000' );}
                else if(ui.value >= 1200000 && ui.value < 1400000) {$("#slider2").slider("option", "value", "1200000"); handle2.text( '1200000' );}
                else if(ui.value >= 1400000 && ui.value < 1600000) {$("#slider2").slider("option", "value", "1400000"); handle2.text( '1400000' );}
                else if(ui.value >= 1600000 && ui.value < 1800000) {$("#slider2").slider("option", "value", "1600000"); handle2.text( '1600000' );}
                else if(ui.value >= 1800000 && ui.value < 2000000) {$("#slider2").slider("option", "value", "1800000"); handle2.text( '1800000' );}
                else if(ui.value >= 2000000) {$("#slider2").slider("option", "value", "2000000"); handle2.text( '2000000' );}
            }
        });


        $("#slider2").slider({
            range: "max",
            value:0,
            min: 0,
            max: 2000000,
            step: 1000,
            create: function() {
                handle2.text( $( this ).slider( "value" ) );
            },
            slide: function( event, ui2 )
            {
                $( "#amount2" ).val( ui2.value);
                handle2.text( ui2.value );
                if(ui2.value < 100000) {$("#slider1").slider("option", "value", "0"); handle1.text( '0' );}
                else if(ui2.value >= 100000 && ui2.value < 200000) {$("#slider1").slider("option", "value", "100000"); handle1.text( '100000' );}
                else if(ui2.value >= 200000 && ui2.value < 400000) {$("#slider1").slider("option", "value", "200000"); handle1.text( '200000' );}
                else if(ui2.value >= 400000 && ui2.value < 600000) {$("#slider1").slider("option", "value", "400000"); handle1.text( '400000' );}
                else if(ui2.value >= 600000 && ui2.value < 800000) {$("#slider1").slider("option", "value", "600000"); handle1.text( '600000' );}
                else if(ui2.value >= 800000 && ui2.value < 1000000) {$("#slider1").slider("option", "value", "800000"); handle1.text( '800000' );}
                else if(ui2.value >= 1000000 && ui2.value < 1200000) {$("#slider1").slider("option", "value", "1000000"); handle1.text( '1000000' );}
                else if(ui2.value >= 1200000 && ui2.value < 1400000) {$("#slider1").slider("option", "value", "1200000"); handle1.text( '1200000' );}
                else if(ui2.value >= 1400000 && ui2.value < 1600000) {$("#slider1").slider("option", "value", "1400000");handle1.text( '1400000' );}
                else if(ui2.value >= 1600000 && ui2.value < 1800000) {$("#slider1").slider("option", "value", "1600000"); handle1.text( '1600000' );}
                else if(ui2.value >= 1800000 && ui2.value < 2000000) {$("#slider1").slider("option", "value", "1800000"); handle1.text( '1800000' );}
                else if(ui2.value = 2000000) {$("#slider1").slider("option", "value", "2000000"); handle1.text( '2000000' );}

            }
        });

    });

    if (typeof cloudpayments !== 'undefined') {
        var widgetCloudPayments = new cp.CloudPayments({language: cloudpayments['language']});
        widgetCloudPayments.charge(
            cloudpayments['options'],
            function () {
                window.location.replace(cloudpayments['routeSuccess']);
            },
            function () {
                window.location.replace(cloudpayments['routeFail']);
            }
        );
    }

    if (typeof unitpay !== 'undefined') {
        var payment = new UnitPay();
        payment.createWidget(unitpay);
        payment.success(function (params) {
            window.location.reload();
        });
        payment.error(function (message, params) {
            console.log(message);
            console.log(params);
        });
        return false;
    }
});
