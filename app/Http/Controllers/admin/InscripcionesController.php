<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actividad;
use App\Inscripcion;

class InscripcionesController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Actividad $actividad)
    {

        $this->authorize('update', $actividad);

        request()->validate([ 'id_usuario' => 'required']);

        $actividad->inscribir(request('id_usuario'));

        return redirect($actividad->path_admin());
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Inscripcion $inscripcion)
    {
        $this->authorize('update', $inscripcion->actividad); 

        if(request()->has('confirmar'))
            $inscripcion->update(['confirmada' => request('confirmar')==true ]);

        return redirect($inscripcion->actividad->path_admin());
    }

    public function destroy(Inscripcion $inscripcion)
    {

        $this->authorize('update', $inscripcion->actividad); 

        $inscripcion->delete();

        return redirect($inscripcion->actividad->path_admin());
    }
}
