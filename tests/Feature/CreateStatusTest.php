<?php

namespace Tests\Feature;

use App\Models\User; // Corregido el namespace
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateStatusTest extends TestCase
{
    use RefreshDatabase;

    //Prueba para Invalidar la creación de un estado desde un invitado
    /** @test */
    public function guests_users_can_not_create_statuses()
    {
        //1. GIVEN -> TENIENDO un usuario no autentificado
        //como es uno no autenticado entonces no es necesario colocar algo aquí

        //2. WHEN -> CUANDO hace un post request a status
        $response = $this->post(route('statuses.store'), ['body'=>'Mi primer estado publicado']);
        
        //3. THEN -> ENTONCES redirecciona a la interfaz de login
        $response->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_can_create_status(): void
    {
        // Quitar el manejo de errores
        $this->withoutExceptionHandling();

        // 1. GIVEN -> TENIENDO un usuario autenticado
    
        // 1.1. Crear un nuevo usuario desde FACTORY
        $user = User::factory()->create();

        // 1.2. Autentificando un usuario
        $this->actingAs($user);

        // 2. WHEN -> CUANDO hace un post request a status
        $this->post(route('statuses.store'), ['body' => 'Mi primer estado publicado']);

        //2.1. Hacer el post deseado dentro de la ruta asignada
        $response = $this->post(route('statuses.store'),['body' => 'Mi primer estado publicado']);

        //2.2. Redireccionar la interfaz una vez registrado el estado || debe redireccionar al INDEX principal
        $response->assertJson([
            'body' => 'Mi primer estado publicado'
        ]);

        // 3. THEN -> ENTONCES veo un nuevo estado en la base de datos
        $this->assertDatabaseHas('statuses', [
            'user_id'=> $user->id,
            'body' => 'Mi primer estado publicado',
        ]);
    }

    //Prueba para validar el BODY al crear un Estado
    /** @test */
    public function a_status_requires_a_body()
    {
        //1. GIVEN -> TENIENDO un usuario autentificado
        $user = User::factory()->create();
        $this->actingAs($user);

        //2. WHEN -> CUANDO hace un post (create) request a status
        $response = $this->postJson(route('statuses.store'),['body'=>'']);
        $response->assertStatus(422);

        //3. THEN -> ENTONCES veo un nuevo estado en la base de datos
        $response->assertJsonStructure([
            'message', 'errors' => ['body']
        ]);
    }

    //Prueba para validar el mínimo de contenido del BODY al crear un Estado
    /** @test */
    public function a_status_body_requires_a_minimum_length()
    {
        //1. GIVEN -> TENIENDO un usuario autentificado
        $user = User::factory()->create();
        $this->actingAs($user);

        //2. WHEN -> CUANDO hace un post (create) request a status con 10 caracteres
        $response = $this->postJson(route('statuses.store'),['body'=>'asfa ssfa']);
        $response->assertStatus(422);

        //3. THEN -> ENTONCES veo un nuevo estado en la base de datos
        $response->assertJsonStructure([
            'message', 'errors' => ['body']
        ]);

    }
}