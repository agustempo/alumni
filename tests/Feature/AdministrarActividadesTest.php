<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class AdministrarActividadesTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/

    public function invitado_no_administra_actividades()
    {
        //$this->withoutExceptionHandling();

        $actividad = factory('App\Actividad')->create();

        $this->get('/admin/actividades',$actividad->toArray())->assertRedirect('/login');
        $this->get($actividad->path_admin(),$actividad->toArray())->assertRedirect('/login');
        $this->patch($actividad->path_admin(),$actividad->toArray())->assertRedirect('/login');
        $this->delete($actividad->path_admin(),$actividad->toArray())->assertRedirect('/login');
        $this->post('/admin/actividades',$actividad->toArray())->assertRedirect('/login');

    }

    /** @test **/

    public function actividad_requiere_un_nombre()
    {
        //$this->withoutExceptionHandling();
        
        $usuario = factory('App\Usuario')->create();

        $actividad = factory('App\Actividad')->raw([
            'nombre' => '',
            'id_creador' => $usuario->id
        ]);

        $this->actingAs($usuario)->post('/admin/actividades',$actividad)
            ->assertSessionHasErrors('nombre');
    }

    /** @test **/

    public function actividad_requiere_una_descripcion()
    {
        //$this->withoutExceptionHandling();
        
        $usuario = factory('App\Usuario')->create();

        $actividad = factory('App\Actividad')->raw([
            'descripcion' => ''
        ]);

        $this->actingAs($usuario)->post('/admin/actividades',$actividad)
            ->assertSessionHasErrors('descripcion');
    }

    /** @test **/

    public function actividad_requiere_fechas()
    {
        //$this->withoutExceptionHandling();
        
        $usuario = factory('App\Usuario')->create();

        $actividad = factory('App\Actividad')->raw([
            'fecha_inicio' => '',
            'fecha_fin' => ''
        ]);

        $this->actingAs($usuario)->post('/admin/actividades',$actividad)
            ->assertSessionHasErrors(['fecha_inicio', 'fecha_fin']);
    }


    /** @test **/

    public function usuario_solo_ve_sus_actividades_creadas_en_el_listado()
    {
        //$this->withoutExceptionHandling();

        $usuario = factory('App\Usuario')->create();

        $actividad_mia = factory('App\Actividad')->create();

        $actividad_de_otro = factory('App\Actividad')->create();

        $this->actingAs($actividad_mia->creador)->get('/admin/actividades')
            ->assertSee($actividad_mia->nombre);

        $this->actingAs($actividad_mia->creador)->get('/admin/actividades')
            ->assertDontSee($actividad_de_otro->nombre);
    }

    /** @test **/

    public function usuario_ve_actividad_creada_por_el_solamente()
    {
        ///$this->withoutExceptionHandling();

        $actividad_mia = factory('App\Actividad')->create();
        $actividad_de_otro = factory('App\Actividad')->create();

        $this->actingAs($actividad_mia->creador)->get($actividad_mia->path_admin())->assertStatus(200);

        $this->actingAs($actividad_mia->creador)->get($actividad_de_otro->path_admin())->assertStatus(403);
    }

    /** @test **/

    public function solo_usuario_puede_editar_actividad()
    {
        //$this->withoutExceptionHandling();
        
        $usuario = factory('App\Usuario')->create();
        $this->actingAs($usuario);

        $actividad_mia = factory('App\Actividad')->create([
            'id_creador' => $usuario->id
        ]);
        $actividad_de_otro = factory('App\Actividad')->create();

        $actividad_mia->descripcion = 'Modificada';
        $actividad_de_otro->descripcion = 'Modificada';

        $this->patch($actividad_mia->path_admin(),$actividad_mia->toArray())
            ->assertRedirect($actividad_mia->path_admin());

        $this->assertDatabaseHas('actividades',$actividad_mia->toArray());

        $this->patch($actividad_de_otro->path_admin(), $actividad_de_otro->toArray())
            ->assertStatus(403);

        $this->assertDatabaseMissing('actividades',$actividad_de_otro->toArray());
    }

    /** @test **/

    public function solo_usuario_puede_crear_actividad()
    {
        //$this->withoutExceptionHandling();

        $actividad = factory('App\Actividad')->raw();

        $this->post('/admin/actividades',$actividad)
            ->assertRedirect('/login');
        $this->assertDatabaseMissing('actividades',$actividad);

        $usuario = factory('App\Usuario')->create();
        $this->actingAs($usuario);

        $actividad_mia = factory('App\Actividad')->raw([
            'id_creador' => $usuario->id
        ]);

        $this->post('/admin/actividades', $actividad_mia)
            ->assertRedirect('/admin/actividades');
        
        $this->assertDatabaseHas('actividades', ['id_creador' => $usuario->id]);

    }

}
