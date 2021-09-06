<?php
$nextMinute         = \Carbon\Carbon::now()->addMinutes(1)->format('Y-m-d H:i:00');
$nextMinuteCarbon   = \Carbon\Carbon::parse($nextMinute);
$diffInSeconds      = \Carbon\Carbon::now()->diffInRealSeconds($nextMinuteCarbon);
?>
На данный момент я не могу ответить вам. Попробуйте через {{ $diffInSeconds }} сек.