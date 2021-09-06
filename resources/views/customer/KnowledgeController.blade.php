@extends('layouts.customer')

@section('title', 'База знаний' . ' - ' . __('site.site'))

@section('content')
    <div class="row">
        <div class="main col-md-9">
            <h1>База знаний</h1>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('.spoiler_links').click(function () {
                        $(this).parent().children('div.spoiler_body').slideToggle('slow');
                        return false;
                    });
                });
            </script>
            <div id="gl_obl">
                <div>
                    <a href="" class="knopk spoiler_links">
                        <strong>1.</strong>
                        <span>{{ __("Who is an offer?") }}</span>
                    </a>
                    <div class="spoiler_body">
                        <strong>
                            {{ __("By this word they mean a person who commits various actions on social networks for a reward;") }}
                        </strong>
                    </div>
                </div>

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
