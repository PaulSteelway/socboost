@extends('layouts.customer')

@section('title', "Бонусы" . ' - ' . __('site.site'))

@section('content')
    <div class="row padding-b-50">
        <div class="main col-md-9">
            <h1>Бонусы</h1>
            <div class="discount">
                <div class="discount-text">Постоянным клиентам нашего сервиса, мы дарим бесплатные <strong>бонусы до 25%</strong>, при пополнении баланса!</div>
                <table class="discount-table">
                    <tr>
                        <td>
                            Сумма пополнения
                        </td>
                        <td>
                            Бонус в подарок
                        </td>
                    </tr>
                    <tr>
                        <td>
                            от <span>1 000</span> рублей
                        </td>
                        <td>
                            <strong>+5%</strong> от суммы
                        </td>
                    </tr>
                    <tr>
                        <td>
                            от <span>3 000</span> рублей
                        </td>
                        <td>
                            <strong>+10%</strong> от суммы
                        </td>
                    </tr>
                    <tr>
                        <td>
                            от <span>10 000</span> {{ app()->getLocale() == 'en' ? '$' : '₽' }}
                        </td>
                        <td>
                            <strong>+15%</strong> от суммы
                        </td>
                    </tr>
                    <tr>
                        <td>
                            от <span>30 000</span> {{ app()->getLocale() == 'en' ? '$' : '₽' }}
                        </td>
                        <td>
                            <strong>+20%</strong> от суммы
                        </td>
                    </tr>
                    <tr>
                        <td>
                            от <span>50 000</span> {{ app()->getLocale() == 'en' ? '$' : '₽' }}
                        </td>
                        <td>
                            <strong>+25%</strong> от суммы
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
    <div class="border">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
@endsection
