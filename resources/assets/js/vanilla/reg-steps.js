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
    finish: "{{__('Confirm')}}",
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
      if($('#profile-phone').val() == ''){
        registerNewUser()
        return false
      }else{
        if($('#registration-steps .actions li:last-child a').length == 1){
          $('#registration-steps .actions li:last-child').append('<a href="#" class="sumbit-without-code" onclick="registerNewUser()" role="menuitem">Confirm later</a>');
        }
        sendSms();
      }
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
      $('#profile-phone-val').text($('#profile-phone').val())

    }
  },
  onCanceled: function (event) {
  },
  onFinishing: function (event, currentIndex) {
    return true;
  },
  onFinished: function (event, currentIndex) {
    checkSmsCode()
  },
});
