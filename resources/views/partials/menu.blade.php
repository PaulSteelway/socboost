<ul>
  @foreach ($categories as $key => $category)
    <li class="{{ $categoty['icon_class'] }}">
      <span>{{ app()->getLocale() == 'en' ? $category['name_en'] : $category['name_ru'] }}</span>
      <ul>
        @if(!empty($category['subcategories']))
          @foreach($category['subcategories'] as $cat)
            <li>
              <a class="menu" href="{{ route('order.category', $cat['url']) }}">
                {{ app()->getLocale() == 'en' ? $cat['name_en'] : $cat['name_ru'] }}
              </a>
            </li>
          @endforeach
        @endif
      </ul>
    @endforeach
  </ul>
