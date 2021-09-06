<div class="panel-group our-services__accordion" id="our-services__accordion">
  <div class="container">
    <div class="row">

      @foreach ($categories as $key => $oneCategory)
        <div class="col-12 col-lg-4">
          <div class="our-services__panel-container">

            <div class="our-services__panel-heading">
              <h4 class="our-services__panel-title">
                <a class="accordion-toggle our-services__accordion-link collapsed" data-toggle="collapse" href="#{{ $oneCategory['icon_class'] }}-panel">
                  <div class="our-services__accordion-img">
{{--                    <div class="sm-{{ $oneCategory['icon_class'] }} d-none d-md-block"></div>--}}
{{--                    <img class="d-block d-md-none mob-img" src="" data-src="{{ asset('/' . $oneCategory['icon_img']) }}" width="24"  alt="">--}}
                    <img src="{{ asset('/' . $oneCategory['icon_img']) }}" data-src="{{ asset('/' . $oneCategory['icon_img']) }}" width="24"  alt="">
                  </div>
                  <span class="our-services__accordion-link-name">{{ $oneCategory['name_'. app()->getLocale()] }}</span>
                </a>
              </h4>
            </div>

            <div id="{{ $oneCategory['icon_class'] }}-panel" class="panel-collapse collapse our-services__panel">
              @if(!empty($oneCategory['subcategories']))
                <div class="panel-body">
                  <ul class="our-services__panel-list">
                    @foreach($oneCategory['subcategories'] as $cat)
                      <li class="our-services__panel-item">
                        <div class="our-services__icon-container">
{{--                          <div class="d-none d-md-block sm-icon sm-{{ $cat['icon_class'] }}"></div>--}}
{{--                          <div class="d-none d-md-block our-services__icon sm-{{ $cat['icon_class'] }}_active our-services__icon--hover"></div>--}}
{{--                          <img class="d-block d-md-none mob-img our-services__icon" src="" data-src="{{ asset('/' . $cat['icon_img']) }}" alt="">--}}
{{--                          <img class="d-block d-md-none mob-img our-services__icon our-services__icon--hover" src="" data-src="{{ asset('/' . $cat['icon_img_active']) }}" alt="">--}}
                          <img class="our-services__icon" src="{{ asset('/' . $cat['icon_img']) }}" data-src="{{ asset('/' . $cat['icon_img']) }}" alt="">
                          <img class="our-services__icon our-services__icon--hover" src="{{ asset('/' . $cat['icon_img']) }}" data-src="{{ asset('/' . $cat['icon_img_active']) }}" alt="">
                        </div>
                        <a href="{{ route('order.category', $cat['url']) }}" class="our-services__panel-link">
                          {{ $cat['name_' . app()->getLocale()] }}
                        </a>
                      </li>
                    @endforeach
                  </ul>
                </div>
              @endif
            </div>

          </div>
        </div>
      @endforeach

    </div>
  </div>
</div>
