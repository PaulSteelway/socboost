У вас появился новый партнер на уровне {{ $level }}
<?php
$partnerTelegram = $partner->telegramUser()->first();
?>
@if(!empty($partnerTelegram->username))
Адрес партнера: {{ null !== $partnerTelegram ? '@'.$partnerTelegram->username : '?' }}
@endif