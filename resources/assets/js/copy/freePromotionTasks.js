function openIdentityModal() {
    event.preventDefault();
    $('#freeActionModalBody').html('');
    $('#contentAttachingAccounts').clone().appendTo('#freeActionModalBody');
    $('#freeActionModal').modal('show');
}

function openInstViaLike() {
    $('#freeActionModalBody').html('');
    $('#contentAttachingInstagramViaLike').clone().appendTo('#freeActionModalBody');
}

function openInstViaLikeLink(url) {
    event.preventDefault();
    var username = $('.modal-form__input[name="username"]').val();
    if (username) {
        $('.fp_modal__actions--submit').css('display', 'none');
        $('.fp_modal__actions--check').css('display', 'block');

        var top = (window.screen.height - 600) / 2;
        var left = (window.screen.width - 600) / 2;
        var popup_window = window.open(url, "popupWindow", "width=600,height=600,scrollbars=yes,top=" + top + ",left=" + left);

        var timer = setInterval(function () {
            if (popup_window.closed) {
                clearInterval(timer);
                checkConnectViaLike();
            }
        }, 500);
    }
}

function checkConnectViaLike() {
    var form = $('#instViaLikeForm');
    $.ajax({
        url: form.attr('action'),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        data: form.serialize(),
        success: function (response) {
            location.reload();
        },
        error: function (error) {
            console.log(error);
            $('#instViaLikeFormError').text(error.responseJSON.error);
            $('.fp_modal__actions--check').css('display', 'none');
            $('.fp_modal__actions--submit').css('display', 'block');
        }
    });
}

function openTaskPage(url, task, popup_window) {
    // var top = (window.screen.height - 600) / 2;
    // var left = (window.screen.width - 600) / 2;
    // var popup_window = window.open(url, "popupWindow", "width=600,height=600,scrollbars=yes,top=" + top + ",left=" + left);
    popup_window.location = url;

    var timer = setInterval(function () {
        if (popup_window.closed) {
            clearInterval(timer);
            checkTaskExecution(task);
        }
    }, 500);
}

function checkTaskExecution(task) {
    $.ajax({
        url: '/tasks/verificationImplementation',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        data: {'task_id': task},
        success: function (response) {
            location.reload();
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function createPromotionTask() {
    let form = $('#freePromotionTaskForm');
    let url = form.attr('action');

    if (form.find('select[name="social_id"] option:selected').text() === 'Instagram' && form.find('select[name="category_id"] option:selected').text() === 'Подписчики') {
        let formData = {};
        form.serializeArray().map(function (item) {
            formData[item.name] = item.value;
        });
        if ($.ajaxSettings && $.ajaxSettings.headers) {
          delete $.ajaxSettings.headers['X-CSRF-TOKEN'];
        }
        $.get(formData['link'] + '?__a=1', function (data) {
            if (typeof data !== 'undefined' && typeof data['graphql'] !== 'undefined' && typeof data['graphql']['user'] !== 'undefined' && typeof data['graphql']['user']['id'] !== 'undefined'){
                formData['account_id'] = data['graphql']['user']['id'];
                sendAjaxRequest(url, formData);
            }else{
                toastr.error('Ссылка Instagram невалидная!');
            }
        }).fail(function () {
            toastr.options = {
                    "positionClass": "toast-bottom-right",
                };
                toastr.error('Ссылка Instagram невалидная!');
            });
    } else {
        sendAjaxRequest(url, form.serialize());
    }
}

function sendAjaxRequest(url, data) {
    $.ajax({
        url: url,
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        dataType: 'JSON',
        success: function () {
            location.reload();
        },
        error: function (response) {
            location.reload();
        }
    });
}

function checkTotalRewardSum(reward, qty) {
    $.ajax({
        url: `/tasks/get_total_rewards?reward=${reward}&qty=${qty}`,
        method: 'GET',
        success: function (data) {
            if (data.rewards) {
                $('#totalRewardsSum').html(data.rewards);
                $('#totalRewardsContainer').show();
            } else {
                $('#totalRewardsContainer').hide();
            }
        }
    });
}

$(document).ready((function () {
    var taskRewardAmount = $('#taskRewardAmount');
    var taskExecutionQty = $('#taskExecutionQty');
    checkTotalRewardSum(taskRewardAmount.val(), taskExecutionQty.val());

    taskRewardAmount.change(function () {
        checkTotalRewardSum(taskRewardAmount.val(), taskExecutionQty.val());
    });

    taskExecutionQty.change(function () {
        checkTotalRewardSum(taskRewardAmount.val(), taskExecutionQty.val());
    });

    $('.taskExecutionBtn').click(function () {
        var button = $(this);
        var url = button.data('link');
        var task = button.data('task');
        if (button.hasClass('taskExecutionBtn')) {
            if (button.hasClass('checking')) {
                checkTaskExecution(task);
            } else {
                var top = (window.screen.height - 600) / 2;
                var left = (window.screen.width - 600) / 2;
                var popup_window = window.open('', "popupWindow", "width=600,height=600,scrollbars=yes,top=" + top + ",left=" + left);
                $.ajax({
                    url: `/tasks/check_task_validity?task_id=${task}`,
                    method: 'GET',
                    success: function (data) {
                        if (data.valid) {
                            button.css('background-color', '#4BB543').addClass('checking').text('Проверить');
                            openTaskPage(url, task, popup_window);
                        } else {
                            popup_window.close();
                            button.css('background-color', 'grey');
                            button.removeClass('taskExecutionBtn');
                            toastr.options = {
                                "positionClass": "toast-bottom-right",
                            };
                            toastr.error('Данное задание уже не актуально.');
                        }
                    }
                });
            }
        }
    });
}));
