@extends('layouts.auth.app')

@section('title-page')
    <title> CakeStore | Productos </title>
@endsection

@section('head-scripts-pages')
    @yield('scripts-template')
    {{ Html::script('auth/js/angularjs/models/product/class/directes.js') }}
    {{ Html::script('auth/js/angularjs/models/product/class/services.js') }}
@endsection

@section('content')

    <div class="page-container">

    @include('layouts.auth.navbar')

    @include('layouts.auth.sidebar')

        <div class="page-content" ng-app="productsApp" ng-controller="productsCtrl" >

            <div ng-show="loading" class="loader-forms ng-hide">
                <i class="icon-spinner7 spin block-inner"></i>
            </div>

            @yield('content-template')

            @include('layouts.auth.footer')

        </div>

    </div>

@endsection

