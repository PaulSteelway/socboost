<?php
/** @var \App\Models\UserTasks\Tasks $task */
$task          = $userTask->task;
/** @var \App\Models\PaymentSystem $paymentSystem */
$paymentSystem = $task->paymentSystem;
/** @var \App\Models\Currency $currency */
$currency      = $task->currency;
?>
<?php
/*
 * Задание "{{ $task->title }}" успешно выполнено.
 * @if ($task->reward_amount > 0)
 * На ваш счет было отправлено вознаграждение: {{ $task->reward_amount }}{{ null !== $currency ? $currency->symbol : '' }}
 * @endif
 */
?>