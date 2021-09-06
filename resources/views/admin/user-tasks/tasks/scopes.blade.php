<strong>Youtube:</strong>

<?php
if (isset($task)) {
    $scope1Id = \App\Models\UserTasks\TaskScopes::where('key', 'youtube_video_watch')->first();
    $existsScope1Info = $task->actions()
        ->where('task_scope_id', $scope1Id->id)
        ->first();
}
?>
<div class="form-group">
    <label for="scope1" class="col-md-4 control-label">Просмотры видео</label>
@if(!isset($existsScope1Info))
    <div class="col-md-6">
        <input id="scope1" type="text" class="form-control" name="scope[youtube_video_watch]" value="" placeholder="https://www.youtube.com/watch?v=IAuDGkJdvXM">
    </div>
@else
    <a href="{{ $existsScope1Info->source_address }}" target="_blank">{{ $existsScope1Info->source_address }}</a>
@endif
</div>

<?php
if (isset($task)) {
    $scope2Id = \App\Models\UserTasks\TaskScopes::where('key', 'youtube_channel_subscription')->first();
    $existsScope2Info = $task->actions()
        ->where('task_scope_id', $scope2Id->id)
        ->first();
}
?>
<div class="form-group">
    <label for="scope2" class="col-md-4 control-label">Подписки на канал</label>
    @if(!isset($existsScope2Info))
        <div class="col-md-6">
            <input id="scope2" type="text" class="form-control" name="scope[youtube_channel_subscription]" value="" placeholder="https://www.youtube.com/user/fozzium">
        </div>
    @else
        <a href="{{ $existsScope2Info->source_address }}" target="_blank">{{ $existsScope2Info->source_address }}</a>
    @endif
</div>

<?php
if (isset($task)) {
    $scope6Id = \App\Models\UserTasks\TaskScopes::where('key', 'youtube_video_like')->first();
    $existsScope6Info = $task->actions()
        ->where('task_scope_id', $scope6Id->id)
        ->first();
}
?>
<div class="form-group">
    <label for="scope6" class="col-md-4 control-label">Лайк видео</label>
    @if(!isset($existsScope6Info))
        <div class="col-md-6">
            <input id="scope6" type="text" class="form-control" name="scope[youtube_video_like]" value="" placeholder="https://www.youtube.com/watch?v=teojy6WjlKA">
        </div>
    @else
        <a href="{{ $existsScope6Info->source_address }}" target="_blank">{{ $existsScope6Info->source_address }}</a>
    @endif
</div>

<?php
if (isset($task)) {
    $scope7Id = \App\Models\UserTasks\TaskScopes::where('key', 'youtube_video_comment')->first();
    $existsScope7Info = $task->actions()
        ->where('task_scope_id', $scope7Id->id)
        ->first();
}
?>
<div class="form-group">
    <label for="scope7" class="col-md-4 control-label">Комментарий к видео (более 10 символов)</label>
    @if(!isset($existsScope7Info))
        <div class="col-md-6">
            <input id="scope7" type="text" class="form-control" name="scope[youtube_video_comment]" value="" placeholder="https://www.youtube.com/watch?v=teojy6WjlKA">
        </div>
    @else
        <a href="{{ $existsScope7Info->source_address }}" target="_blank">{{ $existsScope7Info->source_address }}</a>
    @endif
</div>

<hr>

<strong>Vkontakte:</strong>

<?php
if (isset($task)) {
    $scope3Id = \App\Models\UserTasks\TaskScopes::where('key', 'vk_page_subscription')->first();
    $existsScope3Info = $task->actions()
        ->where('task_scope_id', $scope3Id->id)
        ->first();
}
?>
<div class="form-group">
    <label for="scope3" class="col-md-4 control-label">Подписки на группу (только группа добавленная в конфигурацию)</label>
@if(!isset($existsScope3Info))
    <div class="col-md-6">
        <input id="scope3" type="text" class="form-control" name="scope[vk_page_subscription]" value="" placeholder="https://vk.com/hyipium">
    </div>
@else
    <a href="{{ $existsScope3Info->source_address }}" target="_blank">{{ $existsScope3Info->source_address }}</a>
@endif
</div>

<?php
if (isset($task)) {
    $scope4Id = \App\Models\UserTasks\TaskScopes::where('key', 'vk_post_like')->first();
    $existsScope4Info = $task->actions()
        ->where('task_scope_id', $scope4Id->id)
        ->first();
}
?>
<div class="form-group">
    <label for="scope4" class="col-md-4 control-label">Лайки постов (только для группы добавленной в конфигурацию)</label>
@if(!isset($existsScope4Info))
    <div class="col-md-6">
        <input id="scope4" type="text" class="form-control" name="scope[vk_post_like]" value="" placeholder="https://vk.com/hyipium?w=wall-170939008_1">
    </div>
@else
    <a href="{{ $existsScope4Info->source_address }}" target="_blank">{{ $existsScope4Info->source_address }}</a>
@endif
</div>

<strong>Telegram:</strong>

<?php
if (isset($task)) {
    $scope5Id = \App\Models\UserTasks\TaskScopes::where('key', 'telegram_channel_subscription')->first();
    $existsScope5Info = $task->actions()
        ->where('task_scope_id', $scope5Id->id)
        ->first();
}
?>
<div class="form-group">
    <label for="scope5" class="col-md-4 control-label">Подписка на канал Телеграм</label>
    @if(!isset($existsScope5Info))
        <div class="col-md-6">
            <input id="scope4" type="text" class="form-control" name="scope[telegram_channel_subscription]" value="" placeholder="@channel">
        </div>
    @else
        {{ $existsScope5Info->source_address }}
    @endif
</div>

<strong>Facebook:</strong>

<?php
if (isset($task)) {
    $scope8Id = \App\Models\UserTasks\TaskScopes::where('key', 'facebook_page_like')->first();
    $existsScope8Info = $task->actions()
        ->where('task_scope_id', $scope8Id->id)
        ->first();
}
?>
<div class="form-group">
    <label for="scope8" class="col-md-4 control-label">Лайк страницы ФБ (!! только ссылки с ИД страницы - как в примере, без именных адресов)</label>
    @if(!isset($existsScope8Info))
        <div class="col-md-6">
            <input id="scope8" type="text" class="form-control" name="scope[facebook_page_like]" value="" placeholder="https://facebook.com/258860071366844">
        </div>
    @else
        {{ $existsScope8Info->source_address }}
    @endif
</div>

<?php
if (isset($task)) {
    $scope9Id = \App\Models\UserTasks\TaskScopes::where('key', 'facebook_new_friends')->first();
    $existsScope9Info = $task->actions()
        ->where('task_scope_id', $scope9Id->id)
        ->first();
}
?>
<div class="form-group">
    <label for="scope9" class="col-md-4 control-label">Проверка на добавление в друзья (!! только ссылки с ИД страницы - как в примере, без именных адресов) (пригласитель и приглашаемый должны быть подключены к одному приложению)</label>
    @if(!isset($existsScope9Info))
        <div class="col-md-6">
            <input id="scope9" type="text" class="form-control" name="scope[facebook_new_friends]" value="" placeholder="https://www.facebook.com/profile.php?id=100022790422877">
        </div>
    @else
        {{ $existsScope9Info->source_address }}
    @endif
</div>

<?php
if (isset($task)) {
    $scope10Id = \App\Models\UserTasks\TaskScopes::where('key', 'facebook_post_comment')->first();
    $existsScope10Info = $task->actions()
        ->where('task_scope_id', $scope10Id->id)
        ->first();
}
?>
<div class="form-group">
    <label for="scope10" class="col-md-4 control-label">Проверка комментариев к посту (!! только ссылки с ИД страницы и ИД ПОСТА - как в примере, без именных адресов)</label>
    @if(!isset($existsScope10Info))
        <div class="col-md-6">
            <input id="scope10" type="text" class="form-control" name="scope[facebook_post_comment]" value="" placeholder="https://www.facebook.com/258860071366844/posts/318298615422989/">
        </div>
    @else
        {{ $existsScope10Info->source_address }}
    @endif
</div>