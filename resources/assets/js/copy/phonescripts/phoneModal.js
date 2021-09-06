var itiPhoneModal;
var phoneModal;
var errorModal;

$(document).ready((function () {
    var errorMap = ['Invalid number', 'Invalid country code', 'Too short', 'Too long', 'Invalid number'];
    if (currentLocale === 'ru') {
        errorMap = ['Недействительный номер', 'Недействительный код страны', 'Недостаточно цифр', 'Лишние цифры', 'Недействительный номер'];
    }

    phoneModal = document.querySelector('#modal-phone');
    errorModal = document.querySelector('#modal-error-msg');

    var reset = function () {
        phoneModal.classList.remove('error');
        errorModal.innerHTML = '';
        errorModal.classList.add('hide');
    };

    // on blur: validate
    phoneModal.addEventListener('blur', function () {
        reset();
        if (phoneModal.value.trim()) {
            phoneInput.value = itiPhoneModal.getNumber();
            if (itiPhoneModal.isValidNumber()) {
                phoneModal.setCustomValidity('');
            } else {
                var errorCode = itiPhoneModal.getValidationError();
                errorModal.innerHTML = errorMap[errorCode];
                errorModal.classList.remove('hide');
                phoneModal.classList.add('error');
                phoneModal.setCustomValidity(errorMap[errorCode]);
            }
        } else {
            phoneInput.setCustomValidity('');
        }
    });

    // on keyup / change flag: reset
    phoneModal.addEventListener('change', reset);
    phoneModal.addEventListener('keyup', reset);
}));

function initItiPhoneModal() {
    if (phoneModal) {
        itiPhoneModal = window.intlTelInput(phoneModal, {
            preferredCountries: ['ru', 'ua', 'us', 'gb'],
            separateDialCode: false,
            nationalMode: false,
            initialCountry: 'auto',
            geoIpLookup: function (callback) {
                $.get('https://ipinfo.io', function () {
                }, 'jsonp').always(function (resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            utilsScript: '/js/phonescripts/utils.js'
        });
    }
}

// function submitPhoneChangeModalForm() {
//     if (itiPhoneModal.isValidNumber()) {
//         phoneModal.value = itiPhoneModal.getNumber();
//         document.getElementById('phone-change-form').submit();
//     }
// }