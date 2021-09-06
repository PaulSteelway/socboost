Создан заказ:

ИД: {{ $order->order_id }}
Название: {{ $order->name }}
Пакет ИД: {{ $order->packet_id }}
Ссылка: {{ $order->link }}
Количество: {{ $order->quantity }}
Стоимость: {{ number_format($order->price, 2, '.', '') }}
Статус: {{ $order->status }}
Создан: {{ $order->created_at }}
Заказ вручную: {{ empty($order->packet->is_manual) ? 'Нет' : 'Да' }}