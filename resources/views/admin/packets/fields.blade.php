<!-- Service Field -->
<div class="form-group col-sm-4">
    {!! Form::label('category_id', __('Category:')) !!}
    {!! Form::select('category_id', $categories, null, ['class' => 'form-control',  'placeholder' => 'Select category...']) !!}
</div>

<!-- Service Field -->
<div class="form-group col-sm-4">
    {!! Form::label('service', __('Service:')) !!}
    {!! Form::select('service',  array_combine(config('enumerations.services'), config('enumerations.services')), null, ['class' => 'form-control']) !!}
</div>

<!-- Service ID Field -->
<div class="form-group col-sm-3">
    {!! Form::label('service_id', __('Service ID:')) !!}
    {!! Form::text('service_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Is Manual Field -->
<div class="form-group col-sm-1">
    {!! Form::label('is_manual', __('Is Manual:')) !!}
    {!! Form::select('is_manual', [0 => 'No', 1 => 'Yes'], null, ['class' => 'form-control']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name_ru', __('Name (RU):')) !!}
    {!! Form::text('name_ru', null, ['class' => 'form-control']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name_en', __('Name (EN):')) !!}
    {!! Form::text('name_en', null, ['class' => 'form-control']) !!}
</div>

<!-- Min Field -->
<div class="form-group col-sm-1">
    {!! Form::label('only_for_vip', __('Only for VIP:')) !!}
    {!! Form::checkbox('only_for_vip', '1',  null) !!}
</div>

<!-- Min Field -->
<div class="form-group col-sm-2">
    {!! Form::label('min', __('Min:')) !!}
    {!! Form::number('min', null, ['class' => 'form-control', 'min' => 1, 'step' => 1]) !!}
</div>

<!-- Max Field -->
<div class="form-group col-sm-2">
    {!! Form::label('max', __('Max:')) !!}
    {!! Form::number('max', null, ['class' => 'form-control', 'min' => 1, 'step' => 1]) !!}
</div>

<!-- Step Field -->
<div class="form-group col-sm-1">
    {!! Form::label('step', __('Step:')) !!}
    {!! Form::number('step', null, ['class' => 'form-control', 'min' => 1, 'step' => 1]) !!}
</div>

<!-- Step Fixed Field -->
<div class="form-group col-sm-1">
    {!! Form::label('step_fixed', __('Step Fixed:')) !!}
    {!! Form::select('step_fixed', [0 => 'No', 1 => 'Yes'], null, ['class' => 'form-control']) !!}
</div>

<!-- Price Field -->
<div class="form-group col-sm-2">
    {!! Form::label('price', __('Price:')) !!}
    {!! Form::number('price', null, ['class' => 'form-control', 'min' => 0.01, 'step' => 0.01]) !!}
</div>

<!-- Link Field -->
<div class="form-group col-sm-3">
    {!! Form::label('link', __('Example Link:')) !!}
    {!! Form::text('link', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-3">
    {!! Form::label('icon_title1', 'Icon 1 Title:') !!}
    {!! Form::text('icon_title1', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3">
    {!! Form::label('icon_title2', 'Icon 2 Title:') !!}
    {!! Form::text('icon_title2', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3">
    {!! Form::label('icon_title3', 'Icon 3 Title:') !!}
    {!! Form::text('icon_title3', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3">
    {!! Form::label('icon_title4', 'Icon 4 Title:') !!}
    {!! Form::text('icon_title4', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3">
    {!! Form::label('icon_title1_ru', 'Icon 1 Title RUS:') !!}
    {!! Form::text('icon_title1_ru', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3">
    {!! Form::label('icon_title2_ru', 'Icon 2 Title RUS:') !!}
    {!! Form::text('icon_title2_ru', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3">
    {!! Form::label('icon_title3_ru', 'Icon 3 Title RUS:') !!}
    {!! Form::text('icon_title3_ru', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3">
    {!! Form::label('icon_title4_ru', 'Icon 4 Title RUS:') !!}
    {!! Form::text('icon_title4_ru', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-3">
    {!! Form::label('icon_subtitle1', 'Icon 1 Sub title:') !!}
    {!! Form::text('icon_subtitle1', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3">
    {!! Form::label('icon_subtitle2', 'Icon 2 Sub title:') !!}
    {!! Form::text('icon_subtitle2', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3">
    {!! Form::label('icon_subtitle3', 'Icon 3 Sub title:') !!}
    {!! Form::text('icon_subtitle3', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3">
    {!! Form::label('icon_subtitle4', 'Icon 4 Sub title:') !!}
    {!! Form::text('icon_subtitle4', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3">
    {!! Form::label('icon_subtitle1_ru', 'Icon 1 Sub title RUS:') !!}
    {!! Form::text('icon_subtitle1_ru', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3">
    {!! Form::label('icon_subtitle2_ru', 'Icon 2 Sub title RUS:') !!}
    {!! Form::text('icon_subtitle2_ru', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3">
    {!! Form::label('icon_subtitle3_ru', 'Icon 3 Sub title RUS:') !!}
    {!! Form::text('icon_subtitle3_ru', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3">
    {!! Form::label('icon_subtitle4_ru', 'Icon 4 Sub title RUS:') !!}
    {!! Form::text('icon_subtitle4_ru', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3">
    {!! Form::label('info_en', 'Info EN:') !!}
    {!! Form::text('info_en', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3">
    {!! Form::label('info_ru', 'Info RU:') !!}
    {!! Form::text('info_ru', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('Save'), ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.packets.index') !!}" class="btn btn-default">{{__('Cancel')}}</a>
</div>
