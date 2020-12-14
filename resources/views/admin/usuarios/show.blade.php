@extends ('layouts.home')


@section('title')
{{ __('admin.detalle_de') }} {{ __('admin.usuarios') }}
@endsection('title')

@section('content')




<div class="section">
	<div class="content">
		<div class="columns is-centered">
	        <figure class="image is-128x128">
				<img class="is-rounded" src="/storage/foto_perfil/{{ $usuario->foto_perfil }}">
			</figure>
	        <h3>{{ $usuario->nombre }} {{ $usuario->apellido }} </h3>
      	</div>
    </div>
		
		
		@if (auth()->user()->esAdmin() or (auth()->user()->id == $usuario->id)) 
			@include("admin.usuarios.menu")
		@endif
		<form method="POST" action="" >
			@include("admin.usuarios.form", [ 'deshabilitado' => true ])
		</form>

		<br/>

		<div class="field is-grouped" id="app">
			<p class="control">
				<a class="button" href="javascript:history.back()" > {{ __(('admin.atras')) }}</a>
			</p>
			@if (auth()->user()->esAdmin())
				<p class="control">
					<a class="button" href="/admin/usuarios/{{ $usuario->id }}/edit" > {{ __(('admin.editar')) }}</a>
				</p>
				<form id="form-eliminar" method="POST" action="/admin/usuarios/{{ $usuario->id }}" style="display:none" >
					{{ method_field('DELETE') }}
					{{ csrf_field() }}
				</form>
				<p class="control" >
					<a class="button is-danger" onclick="document.getElementById('form-eliminar').submit()" > {{ __(('admin.eliminar')) }}</a>
				</p>
			@elseif (auth()->user()->id == $usuario->id)
				<p class="control">
					<a class="button" href="/admin/usuarios/{{ $usuario->id }}/edit" > {{ __(('admin.editar')) }}</a>
				</p>
			@endif
		</div>
	</div>
</div>

@endsection("contenido-content")