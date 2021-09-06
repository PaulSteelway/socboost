<?php
$partnerTelegram = $sender->telegramUser()->first();
$partnerLogin = !empty($partnerTelegram->username) ? '@'.$partnerTelegram->username : 'без логина Телеграм';

// Вы получили реферальную коммиссию {{ number_format($amount, $receiveWallet->currency->precision, '.', '') }}{{ $receiveWallet->currency->code }}, от пользователя {{ $partnerLogin }}, который находится на уровне {{ $level }}
?>