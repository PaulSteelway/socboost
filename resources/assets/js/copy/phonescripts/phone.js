var itiPhoneField;
var phoneInput;
var errorMsg;

$(document).ready((function () {
    var errorMap = ['Invalid number', 'Invalid country code', 'Too short', 'Too long', 'Invalid number'];
    if (currentLocale === 'ru') {
        errorMap = ['Недействительный номер', 'Недействительный код страны', 'Недостаточно цифр', 'Лишние цифры', 'Недействительный номер'];
    }

    phoneInput = document.querySelector('#profile-phone');
    errorMsg = document.querySelector('#error-msg');

    var reset = function () {
        phoneInput.classList.remove('error');
        errorMsg.innerHTML = '';
        errorMsg.classList.add('hide');
    };

    // on blur: validate
    if (phoneInput) {
        phoneInput.addEventListener('blur', function () {
            reset();
            if (phoneInput.value.trim()) {
                phoneInput.value = itiPhoneField.getNumber();
                if (itiPhoneField.isValidNumber()) {
                    phoneInput.setCustomValidity('');
                } else {
                    var errorCode = itiPhoneField.getValidationError();
                    errorMsg.innerHTML = errorMap[errorCode];
                    errorMsg.classList.remove('hide');
                    phoneInput.classList.add('error');
                    phoneInput.setCustomValidity(errorMap[errorCode]);
                }
            } else {
                console.log(phoneInput.value.trim(), 'else');

                phoneInput.setCustomValidity('');
            }
        });

        // on keyup / change flag: reset
        phoneInput.addEventListener('change', reset);
        phoneInput.addEventListener('keyup', reset);
    }

}));

function initItiPhoneField(country = null) {
    if (phoneInput) {
        itiPhoneField = window.intlTelInput(phoneInput, {
            preferredCountries: ['ru', 'ua', 'us', 'gb'],
            separateDialCode: false,
            nationalMode: false,
            initialCountry: country ? country : 'auto',
            geoIpLookup: function (callback) {
                $.ajax({
                    url: '/auth/location',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    success: function (resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "";
                        callback(countryCode);
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });
            },
            utilsScript: '/js/phonescripts/utils.js'
        });
    }
}

// function submitProfileForm() {
//     if (itiPhoneField.isValidNumber()) {
//         phoneInput.value = itiPhoneField.getNumber();
//         document.getElementById("profile-form").submit();
//     }
// }
