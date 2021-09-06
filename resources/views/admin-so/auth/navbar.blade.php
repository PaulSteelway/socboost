<li>
  {{-- {{ route('clear_cache') }} --}}
  {{-- <a href="#" title="Очистить кеш сайта">
    <i class="fas fa-eraser"></i>
    <span class="hidden-sm">Очистить кеш</span>
  </a> --}}
</li>

<li>
  <a href="/" target="socialbooster">
    <i class="fas fa-external-link-alt"></i>
    <span class="hidden-sm">На сайт</span>
  </a>
</li>


  @if ($user)
    <li class="dropdown user user-menu">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
          {{-- <img src="{{ $user->avatar_url_or_blank }}" class="user-image" /> --}}
          <span class="hidden-sm">{{ $user->name }}</span>
      </a>
      <ul class="dropdown-menu">
        <li class="user-header">
          {{-- <img src="{{ $user->avatar_url_or_blank }}" class="img-circle" /> --}}
          <p>
            {{ $user->name }}
            <small>@lang('sleeping_owl::lang.auth.since', ['date' => $user->created_at->format('d.m.Y')])</small>

          </p>
        </li>
        <li class="user-footer">
          <a href="{{ URL::to(config('sleeping_owl.url_prefix') . '/users/' . auth()->user()->id . '/edit') }}" class="pr-5">
            <i class="fas fa-user-cog"></i> Кабинет
          </a>
          <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> @lang('sleeping_owl::lang.auth.logout')
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
          </form>
        </li>
      </ul>
    </li>
  @endif
