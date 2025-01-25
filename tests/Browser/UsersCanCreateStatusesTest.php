<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersCanCreateStatusesTest extends DuskTestCase
{
    //Método para migrar la BBDD antes de iniciar la prueba de navegabilidad
    use DatabaseMigrations;

    //Primera Prueba de Navegador

    /**
     * A Dusk test example
     *
     * @test
     * @throws \Throwable
     */
    public function users_can_create_statuses() //Prueba para validar si los usuarios pueden crear estados
    {
        //Creación de usuario autenticado
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {     //dentro del navegador
            $browser->loginAs($user)
                    ->visit('/')                //al visitar la URL "/" (URL principal)
                    ->type('body', 'Mi primer estado publicado') //evaluará si existe un INPUT de nomobre "BODY" -> <input name="body"> y que su contenido sea:
                    ->press('#create-status')   //para validar la existencia de un botón con el nombre create_status
                    ->screenshot('create-status') //capturar salida
                    ->assertSee('Mi primer estado publicado');  //para evaluar que se ve en la pantalla el estado creado
        });
    }
}
