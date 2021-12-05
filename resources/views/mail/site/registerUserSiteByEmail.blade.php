@component('mail::message')
    <h1>Em parceria com: {{$store->name}}</h1>
    <h1>Olá {{$userSite->name}}!</h1>
    <h1>Você efetuou seu cadastro pelo site: <a target="_blank" href="{{$store->url_site}}">{{$store->url_site}}</a></h1>
    <h1>Vamos confirmar o seu endereço de email.</h1>
    <p>Clique no endereço abaixo.</p>
    @component('mail::button', ['url' => $appUrl.'/usuario/verificar-conta/'.$store->id.'/'.$userSite->email.'/'.$userSite->verification_code])
        Confirme seu endereço de email
    @endcomponent
@endcomponent
