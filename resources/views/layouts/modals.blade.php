<!--modal-->

@guest
  {{-- guest free --}}
  @if (isFreePromotionSite())
    @include('layouts.modals.auth-modal-free')
    @include('layouts.modals.register-modal-free')
  @endif

  {{-- guest full --}}
  @include('layouts.modals.auth-modal')
  @include('layouts.modals.register-modal')
  @include('layouts.modals.pass-modal')
@else

  {{-- auth --}}
  @include('layouts.modals.banner-modal')
@endguest



@include('layouts.modals.first-order-modal')

{{-- other --}}
@include('layouts.modals.test-order-btn')
@include('layouts.modals.phone-code-modal')
@include('layouts.modals.phone-change-modal')
@include('layouts.modals.reg-order-modal')




@push('scripts')

<script>
  $('#regModal').on('click', '#password-field', function () {
    if ($('#password').attr('type') === 'password') {
      $('#password').prop({type:"text"})
      $('.toggle-password').removeClass('fa-eye-slash').addClass('fa-eye')
    } else {
      $('.toggle-password').removeClass('fa-eye').addClass('fa-eye-slash')
      $('#password').prop({type:"password"})
    }
  })

  var returnBtn = 'Редактировать'
  if($(window).width() <= 768){
    returnBtn = 'Назад'
  }

  function showErrors() {
  }

  function registerNewUser() {
    var form = $('#register-form');
    $.ajax({
      url: form.attr('action'),
      data: form.serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      dataType: 'JSON',
      beforeSend: function(){
        $('#preloaderAjax').show();
      },
      success: function (response) {
      },
      error: function (response) {
        if (response.status === 200 || response.status === 201) {
          location.href = "/";
        } else {
          var errors = '<div class="alert alert-danger"> <ul>';
          if (response.responseJSON && response.responseJSON.errors) {
            var keys = Object.keys(response.responseJSON.errors);
            for (var i = 0; i < keys.length; i++) {
              errors += "<li class='error-item'>" + response.responseJSON.errors[keys[i]] + "</li>";
            }
          } else {
            errors += "<li class='error-item'>" + response.message + "</li>";
          }
          errors += '</ul></div>';
          $('.register-errors').html(errors);
          setTimeout(function () {
            $('.register-errors').html('');
          }, 5000);
        }
      },
      complete: function(response){
        $("#preloaderAjax").hide();
        if (response.status === 200 || response.status === 201) {
          location.href = "/";
        } else {
          var errors = '<div class="alert alert-danger"> <ul>';
          if (response.responseJSON && response.responseJSON.errors) {
            var keys = Object.keys(response.responseJSON.errors);
            for (var i = 0; i < keys.length; i++) {
              errors += "<li class='error-item'>" + response.responseJSON.errors[keys[i]] + "</li>";
            }
          } else {
            errors += "<li class='error-item'>" + response.message + "</li>";
          }
          errors += '</ul></div>';
          $('.register-errors').html(errors);
          setTimeout(function () {
            $('.register-errors').html('');
          }, 5000);
        }
      }
    });
  }


  function sendSms() {
    $.ajax({
      url: '/auth/sendSms',
      data: {phone: $('#profile-phone').val()},
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      dataType: 'JSON',
      beforeSend: function(){
        $('#preloaderAjax').show();
      },
      success: function (response) {
      },
      error: function (response) {
        if (response.status === 200 || response.status === 201) {
          // registerNewUser()
        } else {
          var errors = '<div class="alert alert-danger"> <ul>';
          if (response.responseJSON && response.responseJSON.errors) {
            var keys = Object.keys(response.responseJSON.errors);
            for (var i = 0; i < keys.length; i++) {
              errors += "<li class='error-item'>" + response.responseJSON.errors[keys[i]] + "</li>";
            }
          } else {
            errors += "<li class='error-item'>" + response.message + "</li>";
          }
          errors += '</ul></div>';
          $('.register-errors').html(errors);
          setTimeout(function () {
            $('.register-errors').html('');
          }, 5000);
        }
      },
      complete: function(){
        $("#preloaderAjax").hide();
      }
    });
  }


  function checkSmsCode() {
    $.ajax({
      url: '/auth/checkSmsCode',
      data: {sms_code: $('#sms-code').val()},
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      dataType: 'JSON',
      beforeSend: function(){
        $('#preloaderAjax').show();
      },
      success: function (response) {
        $('#phone_verified_id').val('1');
        registerNewUser()
      },
      error: function (response) {
        if (response.status === 200 || response.status === 201) {
          $('#phone_verified_id').val('1');
          registerNewUser()
        } else {
          var errors = '<div class="alert alert-danger"> <ul>';
          if (response.responseJSON && response.responseJSON.errors) {
            var keys = Object.keys(response.responseJSON.errors);
            for (var i = 0; i < keys.length; i++) {
              errors += "<li class='error-item'>" + response.responseJSON.errors[keys[i]] + "</li>";
            }
          } else {
            errors += "<li class='error-item'>" + response.message + "</li>";
          }
          errors += '</ul></div>';
          $('#register-errors').html(errors);
          setTimeout(function () {
            $('#recover-errors').html('');
          }, 5000);
        }
      },
      complete: function(){
        $("#preloaderAjax").hide();
      }
    });
  }


  var form = $('#register-form')


  $("#registration-steps").steps({
    headerTag: "h3",
    bodyTag: "section",
    /* Transition Effects */
    transitionEffect: "slideLeft",
    transitionEffectSpeed: 0,
    enableFinishButton: true,

    autoFocus: true,
    /* Labels */
    labels: {
      cancel: "Cancel",
      current: "current step:",
      pagination: "Pagination",
      finish: "{{ __('Confirm') }}",
      next: "{{__('Next')}}",
      previous: returnBtn,
      loading: "Loading ..."
    },

    /* Events */
    onStepChanging: function (event, currentIndex, newIndex) {
      // Allways allow previous action even if the current form is not valid!
      if (currentIndex > newIndex)
      {
        return true;
      }
      if(currentIndex === 0){
        registerNewUser()
      }

      // Needed in some cases if the user went back (clean up)
      if (currentIndex < newIndex)
      {
        // To remove error styles
        form.find(".body:eq(" + newIndex + ") label.error").remove();
        form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
      }
      form.validate().settings.ignore = ":disabled,:hidden";
      return form.valid();
    },
    onStepChanged: function (event, currentIndex, priorIndex) {
      if(priorIndex === 0){
        $('#email-val').text($('#email-reg').val())
      }
    },
    onCanceled: function (event) {
    },
    onFinishing: function (event, currentIndex) {
      return true;
    },
    onFinished: function (event, currentIndex) {
      registerNewUser()
      // checkSmsCode()
    },
  });

</script>

@endpush
