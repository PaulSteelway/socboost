@extends('layouts.customer')

@section('title', __('API Documentation') . ' - ' . __('site.site'))

@section('content')

  @push('styles')
    <style>
      body {
        background-size: cover;
        background-image: url(../images/bg-history.png);
        background-color: rgba(242, 237, 237, 0.7);
      }

      pre {
        margin-bottom: 0;
      }
    </style>
  @endpush

    <main style="padding-top: 100px;">
        <section class="api_docs">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="api__header">
                            <h2 class="api__title">{{__('API Documentation')}}</h2>
                        </div>

                        <div class="api_docs__main_condition_block">
                            <div class="api_docs__main_condition">
                                <div class="api_docs__main_condition_title">{{__('HTTP Method')}}:</div>
                                <div class="api_docs__main_condition_desc">POST</div>
                            </div>
                            <div class="api_docs__main_condition">
                                <div class="api_docs__main_condition_title">{{__('API URL')}}:</div>
                                <div class="api_docs__main_condition_desc">{{ config('app.url') . '/api/v1' }}</div>
                            </div>
                            <div class="api_docs__main_condition">
                                <div class="api_docs__main_condition_title">{{__('API Key')}}:</div>
                                <div class="api_docs__main_condition_desc">{{ \Auth::check() ? \Auth::user()->api_key : __('Register to get an API key') }}</div>
                            </div>
                            <div class="api_docs__main_condition">
                                <div class="api_docs__main_condition_title">{{__('Response format')}}:</div>
                                <div class="api_docs__main_condition_desc">JSON</div>
                            </div>
                        </div>

                        <div class="api__container">
                            <h6>{{__('Balance')}}</h6>
                            <div class="api_docs__params_title">
                                <div class="col-md-4 col-sm-4">{{__('Parameters')}}</div>
                                <div class="col-md-8 col-sm-8">{{__('Description')}}</div>
                            </div>
                            <div class="api_docs__params_desc">
                                <div class="col-md-4 col-sm-4">key</div>
                                <div class="col-md-8 col-sm-8">{{__('Your API key')}}</div>
                            </div>
                            <div class="api_docs__params_desc">
                                <div class="col-md-4 col-sm-4">action</div>
                                <div class="col-md-8 col-sm-8">balance</div>
                            </div>
                            <div class="api_response__container">
                                <div class="api_docs__params_title">{{__('Example response')}}:</div>
                                <code><pre>{
    "data": {
        "balance": 1.29,
        "currency": "USD"
    },
    "error": null
}</pre>
                                </code>
                            </div>
                        </div>


                        <div class="api__container">
                            <h6>{{__('Services List')}}</h6>
                            <div class="api_docs__params_title">
                                <div class="col-md-4 col-sm-4">{{__('Parameters')}}</div>
                                <div class="col-md-8 col-sm-8">{{__('Description')}}</div>
                            </div>
                            <div class="api_docs__params_desc">
                                <div class="col-md-4 col-sm-4">key</div>
                                <div class="col-md-8 col-sm-8">{{__('Your API key')}}</div>
                            </div>
                            <div class="api_docs__params_desc">
                                <div class="col-md-4 col-sm-4">action</div>
                                <div class="col-md-8 col-sm-8">services</div>
                            </div>
                            <div class="api_response__container">
                                <div class="api_docs__params_title">{{__('Example response')}}:</div>
                                <code><pre>{
    "data": [
        {
            "service": 1,
            "name": "First packet",
            "type": "Default",
            "category": "Instagram / Followers",
            "rate": 0.005828,
            "min": 100,
            "max": 10000
        },
        {
            "service": 2,
            "name": "New packet",
            "type": "Default",
            "category": "Instagram / Followers",
            "rate": 0.000767,
            "min": 501,
            "max": 10000
        }
    ],
    "error": null
}</pre>
                                </code>
                            </div>
                        </div>

                        <div class="api__container">
                            <h6>{{__('Add Order')}}</h6>
                            <div class="api_docs__params_title">
                                <div class="col-md-4 col-sm-4">{{__('Parameters')}}</div>
                                <div class="col-md-8 col-sm-8">{{__('Description')}}</div>
                            </div>
                            <div class="api_docs__params_desc">
                                <div class="col-md-4 col-sm-4">key</div>
                                <div class="col-md-8 col-sm-8">{{__('Your API key')}}</div>
                            </div>
                            <div class="api_docs__params_desc">
                                <div class="col-md-4 col-sm-4">action</div>
                                <div class="col-md-8 col-sm-8">add</div>
                            </div>
                            <div class="api_docs__params_desc">
                                <div class="col-md-4 col-sm-4">service</div>
                                <div class="col-md-8 col-sm-8">{{__('Service ID')}}</div>
                            </div>
                            <div class="api_docs__params_desc">
                                <div class="col-md-4 col-sm-4">link</div>
                                <div class="col-md-8 col-sm-8">{{__('Link to page')}}</div>
                            </div>
                            <div class="api_docs__params_desc">
                                <div class="col-md-4 col-sm-4">quantity</div>
                                <div class="col-md-8 col-sm-8">{{__('Needed quantity')}}</div>
                            </div>
                            <div class="api_response__container">
                                <div class="api_docs__params_title">{{__('Example response')}}:</div>
                                <code><pre>{
    "data": {
        "order": "VtyUgOJ"
    },
    "error": null
}</pre>
                                </code>
                            </div>
                        </div>

                        <div class="api__container">
                            <h6>{{__('Order Status')}}</h6>
                            <div class="api_docs__params_title">
                                <div class="col-md-4 col-sm-4">{{__('Parameters')}}</div>
                                <div class="col-md-8 col-sm-8">{{__('Description')}}</div>
                            </div>
                            <div class="api_docs__params_desc">
                                <div class="col-md-4 col-sm-4">key</div>
                                <div class="col-md-8 col-sm-8">{{__('Your API key')}}</div>
                            </div>
                            <div class="api_docs__params_desc">
                                <div class="col-md-4 col-sm-4">action</div>
                                <div class="col-md-8 col-sm-8">status</div>
                            </div>
                            <div class="api_docs__params_desc">
                                <div class="col-md-4 col-sm-4">order</div>
                                <div class="col-md-8 col-sm-8">{{__('Order ID')}}</div>
                            </div>
                            <div class="api_response__container">
                                <div class="api_docs__params_title">{{__('Example response')}}:</div>
                                <code><pre>{
    "data": {
        "status": "In progress"
    },
    "error": null
}</pre>
                                </code>
                            </div>
                        </div>

                        <div class="api__container">
                            <h6>{{__('Multiple orders status')}}</h6>

                            <div class="api_docs__params_title">
                                <div class="col-md-4 col-sm-4">{{__('Parameters')}}</div>
                                <div class="col-md-8 col-sm-8">{{__('Description')}}</div>
                            </div>
                            <div class="api_docs__params_desc">
                                <div class="col-md-4 col-sm-4">key</div>
                                <div class="col-md-8 col-sm-8">{{__('Your API key')}}</div>
                            </div>
                            <div class="api_docs__params_desc">
                                <div class="col-md-4 col-sm-4">action</div>
                                <div class="col-md-8 col-sm-8">status</div>
                            </div>
                            <div class="api_docs__params_desc">
                                <div class="col-md-4 col-sm-4">orders</div>
                                <div class="col-md-8 col-sm-8">{{__('Order IDs separated by comma')}}</div>
                            </div>
                            <div class="api_response__container">
                                <div class="api_docs__params_title">{{__('Example response')}}:</div>
                                <code><pre>{
    "data": {
        "VtyUgOJ": {
            "status": "In progress"
        },
        "VtyUgO7": {
            "error": "Incorrect order ID"
        }
    },
    "error": null
}</pre>
                                </code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
