Проверка задания завершилась неудачей.
<?php
$scope = $taskAction->scope()->first();
$task  = $userTask->task;
?>
Заголовок задания: {{ $task->title }}
Задача: {{ $scope->getScopeDescription() }}
Адрес ресурса: {{ $taskAction->source_address }}
Последняя проверка: {{ $taskAction->last_check_datetime }}

Попробуйте снова подключить к аккаунту социальную сеть "{{ $task->social_category }}".