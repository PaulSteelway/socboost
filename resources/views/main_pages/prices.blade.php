@extends('layouts.customer')

@section('title', __('Price list') . ' - ' . __('site.site'))

@section('content')

  @push('styles')
    <style>
      body {
        background-size: cover;
        background-image: url(../images/bg-history.png);
        background-color: rgba(242, 237, 237, 0.7);
      }
    </style>
  @endpush

    <main style="padding-top: 100px; min-height: 86vh">
        <section class="prices">
            <div class="container">
                <div class="row">
                    <div class="col">
                         <div class="prices__header">
                             <h2 class="prices__title">{{__('Price list')}}</h2>
                             <div class="prices__search">
                                 <div class="price__search">
                                     {!! Form::open(['route' => 'customer.prices', 'id' => 'search-filter-form']) !!}
                                         {!! Form::select('category_id', $categories, $category_id, ['id'=> 'price-search-filter_id', 'placeholder' => __('Category filter'), 'class' => 'price-search-filter']) !!}
                                     <span class="price-filter-arrow"></span>
                                     {!! Form::close() !!}
                                 </div>
                                 <form action="{{url('/prices')}}"  method="post" class="prices__search-form">
                                     {{csrf_field()}}
                                     <input type="text" class="prices__search-input" name="name" id="prices-search" value="{{$search ?? ''}}" placeholder="Поиск">
                                     <input type="submit" id="prices-search-submit" class="prices__search-submit">
                                     <label for="prices-search-submit" class="prices__search-submit-label">
                                         <img src="svg/search.svg" alt="">
                                     </label>
                                 </form>
                             </div>
                         </div>
                        <div class="prices__content">
                            <table class="prices__table">
                                <thead class="prices__thead">
                                    <tr class="prices__tr">
                                        <th class="prices__th prices__th--left">
                                            <table class="prices__table prices__table--left">
                                                <tr class="prices__tr">
                                                    <th class="prices__th">{{__('Service ID')}}</th>
                                                    <th class="prices__th">{{__('Title')}}</th>
                                                </tr>
                                            </table>
                                        </th>
                                        <th class="prices__th prices__th--right">
                                            <table class="prices__table prices__table--right">
                                                <tr class="prices__tr">
                                                    <th class="prices__th">{{__('Price for 1000')}}</th>
                                                    <th class="prices__th">{{__('Execution speed')}}</th>
                                                </tr>
                                            </table>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="prices__tbody">
                                @foreach($packages as $package)
                                    <tr class="prices__tr">
                                        <td class="prices__td prices__td--left">
                                            <table class="prices__table prices__table--left">
                                                <tr class="prices__tr">
                                                    <td class="prices__td">{{$package->service_id}}</td>
                                                    @if(isset($package->category) && isset($package->category->parent))
                                                        @if(app()->getLocale() == 'en')
                                                            @php($parentCat = $package->category->parent->name_ru)
                                                        @else
                                                            @php($parentCat = $package->category->parent->name_ru)
                                                        @endif
                                                    @else
                                                        @php($parentCat = '')
                                                    @endif
                                                    <td class="prices__td">{!! app()->getLocale() == 'en'
                                                        ?  $parentCat . '->' .  $package->name_en
                                                        : $parentCat  . '->' . $package->name_ru
                                                         !!}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="prices__td prices__td--right">
                                            <table class="prices__table prices__table--right">
                                                <tr class="prices__tr">
                                                    <td class="prices__td">{{number_format(socialboosterPriceByAmount($package->price  * 1000), 2, '.', '')}}$</td>
                                                    <td class="prices__td">{!! app()->getLocale() == 'en'
                                                        ? $package->icon_subtitle4
                                                        : $package->icon_subtitle4_ru
                                                         !!}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-block" style="    margin-top: 30px;">
                                {{ $packages->onEachSide(1)->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @push('scripts')
      <script>
        $('#price-search-filter_id').on('change', function () {
          $('#search-filter-form').submit()
        })
      </script>
    @endpush

@endsection
