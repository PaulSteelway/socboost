<?php

namespace App\Admin\Sections;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use SleepingOwl\Admin\Contracts\Initializable;

use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Section;


use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\Cancel;


class Users extends Section implements Initializable
{
  public function initialize() {
    $this->addToNavigation()
      ->setPriority(200);
  }

  protected $checkAccess = true;
  protected $alias = 'users';

  public function getIcon() {
    return 'fa fa-user';
  }
  public function getTitle() {
    return 'Пользователи';
  }
  public function getEditTitle() {
    return 'Редактирование пользователя';
  }
  public function getCreateTitle() {
    return 'Добавление пользователя';
  }


  public function onDisplay() {

    $display = AdminDisplay::datatables()
      ->setHtmlAttribute('class', 'table-primary table-striped table-sm th-center lightcolumn')
      ->setOrder([[0, 'desc']])
      ->with(['wallets'])
      ->setDisplaySearch(true);

    $display->setColumns([
      AdminColumn::text('my_id', 'Ref ID / Создан', 'created_at')
        ->setOrderable(function($query, $direction) {
          $query->orderBy('created_at', $direction);
        })
        ->setSearchable(false)
        ->setWidth('160px'),

      AdminColumn::link('name', 'Имя / почта', 'email')
        ->setSearchCallback(function ($column, $query, $search){
          return $query
            ->where('email', 'like', '%'.$search.'%')
            // ->orWhere('name', 'like', '%'.$search.'%')
            ;
        }),


      AdminColumn::boolean('is_premium', 'Премиум')
        ->setOrderable(function($query, $direction) {
          $query->orderBy('is_premium', $direction);
        })
        ->setSearchable(false)
        ->setWidth('100px'),



      AdminColumn::text('', 'Последний логин', 'last_login')
        ->setOrderable(function($query, $direction) {
          $query->orderBy('last_login', $direction);
        })
        ->setSearchable(false)
        ->setWidth('160px'),
    ]);

    return $display;
  }


  public function onEdit($id) {

    $form = AdminForm::card()->addBody([
      AdminFormElement::columns()->addColumn([
        AdminFormElement::text('id', 'ID')
          ->setReadonly(true),
        AdminFormElement::columns()->addColumn([
          AdminFormElement::text('name', 'Имя')
            ->addValidationRule('max:190', __('adm.valid.max190')),
          AdminFormElement::text('phone', 'Телефон')
            ->addValidationRule('max:190', __('adm.valid.max190')),
        ], 6)->addColumn([
          AdminFormElement::text('email', 'Почта')
            ->required()
            ->unique()
            ->addValidationRule('max:190', __('adm.valid.max190')),
          AdminFormElement::text('login', 'Логин')
            ->unique()
            ->addValidationRule('max:190', __('adm.valid.max190')),
        ]),


      ], 9)->addColumn([
        AdminFormElement::text('created_at', 'Создан')
          ->setReadonly(true),
        AdminFormElement::text('last_login', 'Последний вход')
          ->setReadonly(true),
        AdminFormElement::checkbox('discount_modal', 'Модальная реклама')
          ->setReadonly(true),
        AdminFormElement::checkbox('is_premium', 'Премиум'),

      ]),
      AdminFormElement::html('<hr>'),

    ]);

    $form->getButtons()->setButtons([
      'save_and_close'  => new SaveAndClose(),
      'cancel'  => (new Cancel()),
    ]);

    return $form;
  }

  public function onCreate() {
    return $this->onEdit(null);
  }
  public function onDelete($id) {}

}
