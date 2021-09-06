<?php
$addHour        = \Carbon\Carbon::now()->addHour()->format('Y-m-d H:00:00');
$addHourCarbon  = \Carbon\Carbon::parse($addHour);
$diffInMinutes  = abs(now()->diffInMinutes($addHourCarbon));
?>
Отправлено слишком много запросов. Попробуйте снова, через {{ $diffInMinutes }} мин.