@extends('layouts.customer')

@section('title', __('FAQ') . ' - ' . __('site.site'))

@section('content')

  @push('styles')
    <style>
      body {
        background-size: cover;
        background-image: url(../images/bg-history.png);
        background-color: rgba(242, 237, 237, 0.7);
      }

      .faq__title-link {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        min-height: 32px;
      }

      .faq__cat-container {
        padding: 15px 10px 0 15px;
      }

      .faq__accordion-img {
        width: 24px;
        height: 24px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        margin-right: 10px;
      }

      .faq__accordion-img img{
        max-width: 100%;
        max-height: 100%;
      }

      .active-category {
        border: solid #f57656;
      }
    </style>
  @endpush

    <main style="padding-top: 100px; min-height: 81.4vh;">
        <section class="faq">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="faq__header">
                            <h2 class="faq__title">{{__('FAQ')}}</h2>
                            <div class="faq__search">
                                <form action="{{route('faq')}}" method="GET" class="faq__search-form">
                                    <input type="text" class="faq__search-input" id="faq-search" placeholder="Поиск" name="search" value="{{$search}}">
                                    <input type="submit" id="faq-search-submit" class="faq__search-submit">
                                    <label for="faq-search-submit" class="faq__search-submit-label">
                                        <img src="svg/search.svg" alt="">
                                    </label>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-3 col-lg-2">
                        <div class="panel-group faq__accordion">
                            @foreach ($categories as $key => $category)
                                <div class="faq__panel-container faq__cat-container{{(empty($search) && empty($searchCategory) && $key == 0) || ($category['name_en'] == $searchCategory) ? ' active-category' : ''}}">
                                    <div class="faq__panel-heading">
                                        <h4 class="faq__panel-title">
                                            <a class="faq__title-link" href="{{route('faq', ['category' => $category['name_en']])}}&#{{ $category['name'] }} ">
                                                @if (!empty($category['icon']))
                                                    <div class="faq__accordion-img">
                                                        <img src="{{ asset('/' . $category['icon']) }}" alt="">
                                                    </div>
                                                @endif
                                                <span class="faq__accordion-link-name">{{ $category['name'] }}</span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="{{ $category['name'] }}" class="mobile-answers"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12 col-md-9 col-lg-10">
                        <div class="panel-group faq__accordion" data-answer="{{$data[0]["name"]}}" id="faq__accordion">
                            @foreach($data as $cat)
                                @foreach($cat['faqs'] as $faq)
                                    <div class="faq__panel-container">
                                        <div class="faq__panel-heading">
                                            <h4 class="faq__panel-title">
                                                <a class="accordion-toggle faq__accordion-link collapsed"
                                                   data-toggle="collapse" data-parent="#faq__accordion"
                                                   href="#faq-{{$faq['id']}}">
                                                    <span class="faq__accordion-link-name">{{ $faq['question'] }}</span>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="faq-{{$faq['id']}}" class="panel-collapse collapse faq__panel">
                                            <div class="panel-body faq__panel-body">
                                                <p>{{$faq['answer']}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @push('scripts')
      <script>
        if (window.screen.width < 768) {
          $('#faq__accordion').appendTo('#'+$('#faq__accordion').data('answer'))
        }
      </script>
    @endpush
@endsection
