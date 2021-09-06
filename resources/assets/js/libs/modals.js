$(document).ready(function() {

  $('#authModal').on('show.bs.modal', function (e) {
    $('#regOrderModal').modal('hide');
  });


  $('#regModal').on('show.bs.modal', function (e) {
    initItiPhoneField();
  });


  $('#phoneChangeModal').on('show.bs.modal', function (e) {
    initItiPhoneModal();
  });


  $('#login-form').submit(function (event) {
    event.preventDefault();
    var form = $(this);
    var url = form.attr('action');

    let data = form.serialize();
    data += '&g-recaptcha-response=' + grecaptcha.getResponse();

    $.ajax({
      url: $(this).attr('action'),
      data: form.serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      dataType: 'JSON',
      success: function (response) {
        location.href = "/";
      },
      error: function (response) {
        grecaptcha.reset();
        var errors = '<div class="alert alert-danger"> <ul>';
        if (response.responseJSON.errors) {
          const keys = Object.keys(response.responseJSON.errors);
          for (let i = 0; i < keys.length; i++) {
            errors += "<li class='error-item'>" + response.responseJSON.errors[keys[i]] + "</li>";
          }
        } else {
          errors += "<li class='error-item' >" + response.message + "</li>";
        }
        errors += '</ul></div>';
        $('#login-errors').html(errors);
        setTimeout(function () {
          $('#recover-errors').html('');
        }, 5000);
      }
    });
  });


  $('#register-form').submit(function (event) {
    event.preventDefault()
    var form = $(this);
    var url = form.attr('action');
    $.ajax({
      url: $(this).attr('action'),
      data: form.serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      dataType: 'JSON',
      success: function (response) {
        location.href = "/";
      },
      error: function (response) {
        if (response.status === 200 || response.status === 201) {
          location.href = "/";
        } else {
          var errors = '<div class="alert alert-danger"> <ul>';
          if (response.responseJSON && response.responseJSON.errors) {
            const keys = Object.keys(response.responseJSON.errors)
            for (let i = 0; i < keys.length; i++) {
              errors += "<li class='error-item'>" + response.responseJSON.errors[keys[i]] + "</li>"
            }
          } else {
            errors += "<li class='error-item'>" + response.message + "</li>"
          }
          errors += '</ul></div>'
          $('#register-errors').html(errors)
          setTimeout(function () {
            $('#recover-errors').html('');
          }, 5000);
        }
      }
    });
  });


  $('#recover-psw').submit(function (event) {
    event.preventDefault()
    var form = $(this);
    var url = form.attr('action');
    $.ajax({
      url: $(this).attr('action'),
      data: form.serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      dataType: 'JSON',
      success: function (response) {
        location.reload(true);
      },
      error: function (response) {
        var errors = '<div class="alert alert-danger"> <ul>';
        if (response.responseJSON.errors) {
          const keys = Object.keys(response.responseJSON.errors)
          for (let i = 0; i < keys.length; i++) {
            errors += "<li class='error-item'>" + response.responseJSON.errors[keys[i]] + "</li>"
          }
        } else {
          errors += "<li class='error-item'>" + response.message + "</li>"
        }
        errors += '</ul></div>'
        $('#recover-errors').html(errors)
        setTimeout(function () {
          $('#recover-errors').html('')
        }, 5000)
      }
    });
  });


  $('#register-order-form').submit(function (event) {
    event.preventDefault();
    var form = $(this);
    $.ajax({
      url: form.attr('action'),
      data: form.serialize(),
      type: 'POST',
      dataType: 'JSON',
      beforeSend: function(){
        $('#preloaderAjax').show();
      },
      success: function (response) {
        $('#regOrderModal').modal('hide');
        if (response.data) {
          if (response.locale === 'en') {
            var stripe = Stripe("pk_live_51InOolLSwNUFB8IwzGILFmAkCqrvOMYB28qO4OacXcneTHH9ocDgpTPN0PSMg7h6F3F0UobBImBFt4XuSOKpMEtS00HOLykKdU");
            stripe.redirectToCheckout({'sessionId': response.data});
            // window.location.replace(response.data);
            console.log('1---');
          } else {
            console.log('2---');
            window.location.replace(response.data);
            // if (response.data) {
            //   var payment = new UnitPay();
            //         payment.createWidget(response.data);
            //         payment.success(function (params) {
            //             $('#mainOrderForm').submit();
            //         });
            //         payment.error(function (message, params) {
            //             console.log(message);
            //             console.log(params);
            //         });
            //         return false;
            // }
          }
        } else {
          console.log('3---');
          window.location.reload();
        }
      },
      error: function (response) {
        if (response.status === 200 || response.status === 201) {
          console.log(response);
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
          $('#register-order-errors').html(errors);
        }
      },
      complete: function(){
        $("#preloaderAjax").hide();
      }
    });
  });


  $('#register-form-by-email').submit(function (event) {
    event.preventDefault();
    var form = $(this);
    $.ajax({
      url: form.attr('action'),
      data: form.serialize(),
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: 'POST',
      dataType: 'JSON',
      success: function (response) {
        $('#regOrderModal').modal('hide');
        window.location.reload();
      },
      error: function (response) {
        if (response.status === 200 || response.status === 201) {
          window.location.reload();

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
          $('#register-email-errors').html(errors);
        }
      }
    });
  });



  // checks

  $('#testOrderBtn').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var packageId = button.data('package-id'); // Extract info from data-* attributes
    var packagePrice = button.data('package-price'); // Extract info from data-* attributes
    var packageqty = button.data('package-qty'); // Extract info from data-* attributes
    var packageName = button.data('package-name'); // Extract info from data-* attributes

    var modal = $(this);
    modal.find('.modal-title').text(packageName);
    modal.find(".modal-body input[name='count']").val(packageqty);
    modal.find(".modal-body input[name='package_id']").val(packageId);
    $('#packagePricePlan').text(packagePrice);
  });


  $('#complainTask').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var taskId = button.data('task-id'); // Extract info from data-* attributes

    var modal = $(this);
    modal.find(".modal-body input[name='task_id']").val(taskId);
  });

});
