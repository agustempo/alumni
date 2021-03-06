<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield("title")</title>
    <link rel="shortcut icon" type="image/ico" href="/images/fav.png" />
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/activador.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-5VTN99P6CZ"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-5VTN99P6CZ');
    </script>
  </head>
  <body style="display: flex; flex-direction: column; min-height: 100vh;">

    @section("navbar")
    <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
      <div class="container">
        <div class="navbar-brand">

          <a class="navbar-item" href="/">
            <div class="image is-48x48"  >
              <img style="min-height: 48px" src="/images/logo_nav.png">
            </div>
            <span class="is-size-5 has-text-weight-semibold" style="padding-left: .75em" ></span>
          </a>

          <a role="button" class="navbar-burger burger" style="height: initial" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
          </a>
        </div>

        <div class="navbar-menu">
          <div class="navbar-start">

            @auth
            @section('navbar_menu')

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                  {{ __('admin.usuarios') }}
                </a>
                <div class="navbar-dropdown">
                  <a class="navbar-item" href="/admin/usuarios">
                    {{ __('admin.administrar_usuarios') }}
                  </a>
                  <a class="navbar-item" href="/admin/usuarios/create">
                    {{ __('admin.nuevo') }}
                  </a>
                </div>
            </div>
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                  {{ __('admin.actividades') }}
                </a>
                <div class="navbar-dropdown">
                  <a class="navbar-item" href="/admin/actividades">
                    {{ __('admin.administrar_actividad') }}
                  </a>
                  <a class="navbar-item" href="/admin/actividades/create">
                    {{ __('admin.nueva') }}
                  </a>
                </div>
            </div>

            
            @show
            @endauth

          </div>

          <div class="navbar-end">
         <!--    <div class="navbar-item has-dropdown is-hoverable" dusk="selector-idioma" >
              <a class="navbar-link is-arrowless" href="/idioma/es_AR">{{ config('app.locale') }}</a>
              <div class="navbar-dropdown">
              @foreach (config('app.locales') as $locale)
                  <a class="navbar-item" href="/idioma/{{$locale}}">{{$locale}}</a>
              @endforeach 
              </div>
            </div>
       -->      @auth
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                  <div style="display: flex; align-items: center">
                    <div style="padding-right: .75em">
                    <!--   <div class="image is-48x48">
                        <img class="is-rounded" style="min-height: 48px" src="https://bulma.io/images/placeholders/128x128.png">
                      </div> -->
                    </div>
                    <div class="">
                      <div class="activador_media-content-usuario has-text-weight-semibold">
                        {{ auth()->user()->nombreCompleto }}
                      </div>
                    </div>
                  </div>

                </a>
                <div class="navbar-dropdown">
                  @guest
                  <a class="navbar-item" href="/login" >
                      {{ __(('frontend.login')) }}
                  </a>
                  @endguest
                  @auth
                 <!--  <a class="navbar-item" href="/notificaciones" >
                      {{ __(('frontend.mis_notificaciones')) }}
                  </a>-->
                  <a class="navbar-item" href="/inscripciones" >
                      {{ __(('frontend.mis_inscripciones')) }}
                  </a> 
                  <a class="navbar-item" href="/admin/usuarios/{{ auth()->user()->id }}" >
                      {{ __(('frontend.perfil')) }}
                  </a>
                  <hr class="navbar-divider">
                  <a class="navbar-item" href="/logout" 
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();" >
                      <form id="logout-form" method="POST" action="/logout" style="display:none">{{ csrf_field() }}</form>
                      {{ __(('frontend.logout')) }}
                  </a>
                  @endauth
                </div>
            </div>
            @endauth
            @guest
            <div class="navbar-item">
              <a href="/login" class="button is-inverted has-text-weight-semibold has-text-primary" >{{ __('frontend.ingresar') }}</a>
            </div>
            @endguest
          </div>
        </div>
      </div>
    </nav>
    @show

    <div class="container" style="flex-grow: 1;">
    @yield("content")

    <script src="{{ asset('js/app.js') }}"></script>

    </div>

    @yield('extra_js')
    
    <footer class="footer has-background-grey-lighter">
      <div class="has-text-centered">
        <p><strong>Alumni</strong> by <a href="http://www.ensenaporargentina.org"> Enseña por Argentina</a></p>
        <p>
          <span class="icon is-medium"><i class="fab fa-lg fa-twitter-square"></i></span>
          <span class="icon is-medium"><i class="fab fa-lg fa-facebook"></i></span>
          <span class="icon is-medium"><i class="fab fa-lg fa-instagram"></i></span>
        </p>
        <p>Accedé al código en <a href="https://github.com/agustempo/activador"><i class="fab fa-github-alt"></i> Github</a></p>
      </div>
    </footer>

  </body>
</html>