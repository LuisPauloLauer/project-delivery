@extends('site.layout_simple.site_design')

@section('content')
    <div class="lockscreen-wrapper">
        <div class="lockscreen-logo">
            <p>{{$appName}}</p>
        </div>
        <!-- User name -->
        @if(!$userVerified)
            <div class="lockscreen-name">Olá {{$userSite->name}}</div>
            <br>
            <div class="help-block text-center">
                Sua conta foi ativada
            </div>
            <div class="text-center">
                <a href="{{$store->url_site}}">Voltar para o site: {{$store->url_site}}</a>
            </div>
            <div class="lockscreen-footer text-center">
                Copyright © 2020 <b><a href="#" class="text-black">Lietoo</a></b><br>
                All rights reserved
            </div>
        @else
            <div class="lockscreen-name">Olá {{$userSite->name}}</div>
            <br>
            <div class="help-block text-center">
                Sua conta já está ativada
            </div>
            <div class="text-center">
                <a href="{{$store->url_site}}">Voltar para o site: {{$store->url_site}}</a>
            </div>
            <div class="lockscreen-footer text-center">
                Copyright © 2020 <b><a href="#" class="text-black">Lietoo</a></b><br>
                All rights reserved
            </div>
        @endif
    </div>
@endsection
