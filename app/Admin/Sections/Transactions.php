<?php

namespace App\Admin\Sections;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use AdminColumnFilter;
use SleepingOwl\Admin\Contracts\Initializable;

use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Section;

use App\Models\TransactionType;


use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\Cancel;


class Transactions extends Section implements Initializable
{
  public function initialize() {
    $this->addToNavigation()
      ->setPriority(300);
  }

  protected $checkAccess = false;
  protected $alias = 'users-transactions';

  public function getIcon() {
    return 'fas fa-coins';
  }
  public function getTitle() {
    return 'Транзакции';
  }
  public function getEditTitle() {
    return 'Редактирование транзакции';
  }
  public function getCreateTitle() {
    return 'Добавление транзакции';
  }


  public function onDisplay() {

    $display = AdminDisplay::datatables()
      ->setHtmlAttribute('class', 'table-danger table-striped table-sm th-center lightcolumn')
      ->setOrder([[1, 'desc']])
      ->with(['type', 'user', 'paymentSystem'])
      ->setDisplaySearch(true);

    $display->setColumns([
      AdminColumn::boolean('approved', 'Ок'),

      AdminColumn::text('', 'Создана', 'created_at')
        ->setOrderable(function($query, $direction) {
          $query->orderBy('created_at', $direction);
        })
        ->setSearchable(false)
        ->setWidth('160px'),

      AdminColumn::link('user.name', 'Имя / почта', 'user.email')
        ->setSearchCallback(function ($column, $query, $search){
          return $query->orWhereHas('user', function ($q) use ($search) {
            $q
              ->where('email', 'like', '%'.$search.'%')
              // ->orWhere('name', 'like', '%'.$search.'%')
              ;
          });
        }),


      AdminColumn::text('amount', 'Сумма')
        ->setHtmlAttribute('class', 'text-center')
        ->setWidth('100px')
        ->setSearchable(false),

      AdminColumn::text('paymentSystem.name', 'Система')
        ->setOrderable(function($query, $direction) {
          $query->orderBy('payment_system_id', $direction);
        })
        ->setSearchable(false)
        ->setWidth('100px'),

      AdminColumn::text('type.name', 'Тип')
        ->setOrderable(function($query, $direction) {
          $query->orderBy('type_id', $direction);
        })
        ->setSearchable(false)
        ->setWidth('100px'),

    ]);

    $filters = [
      AdminColumnFilter::select()
        ->setOptions([
          1 => 'проведена',
          0 => 'не проведена',
        ])
        ->setWidth('5rem')
        ->setColumnName('approved')
        ->setPlaceholder('Все транзакции'),

      AdminColumnFilter::select()
        ->setModelForOptions(TransactionType::class, 'name')
        ->setColumnName('type_id')
        ->setWidth('10rem')
        ->setPlaceholder('Все виды'),

      AdminColumnFilter::date()
        ->setColumnName('created_at')
        ->setPlaceholder('Дата транзакции')
        ->setPickerFormat('d-m-Y'),
    ];

    $display->setColumnFilters($filters);

    $display->getColumnFilters()->setPlacement('card.heading');

    return $display;
  }


  // public function onEdit($id) {
  //
  //   $form = AdminForm::card()->addBody([
  //     AdminFormElement::columns()->addColumn([
  //       AdminFormElement::text('id', 'ID')
  //         ->setReadonly(true),
  //       AdminFormElement::columns()->addColumn([
  //         AdminFormElement::text('name', 'Имя')
  //           ->addValidationRule('max:190', __('adm.valid.max190')),
  //       ], 6)->addColumn([
  //         AdminFormElement::text('email', 'Почта')
  //           ->required()
  //           ->unique()
  //           ->addValidationRule('max:190', __('adm.valid.max190')),
  //       ]),
  //
  //
  //     ], 9)->addColumn([
  //       AdminFormElement::text('created_at', 'Создан')
  //         ->setReadonly(true),
  //       AdminFormElement::text('last_login', 'Последний вход')
  //         ->setReadonly(true),
  //
  //     ]),
  //     AdminFormElement::html('<hr>'),
  //
  //   ]);
  //
  //   $form->getButtons()->setButtons([
  //     'save_and_close'  => new SaveAndClose(),
  //     'cancel'  => (new Cancel()),
  //   ]);
  //
  //   return $form;
  // }

  // public function onCreate() {
  //   return $this->onEdit(null);
  // }
  // public function onDelete($id) {}

}
