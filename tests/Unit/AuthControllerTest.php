<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;//Refresh a la test database cuando se haga un inserccion
    /**
     * A basic unit test example.
     */
    public function test_register_user()//Test del registro de usuarios
    {
        $data = [
            'name' => 'testing user name',
            'email' => 'test@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ];

        //Requeat a register endpoint
        $response = $this->postJson('/api/register', $data);

        //Verificamos status, database y respuesta
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'test@gmail.com'
        ]);
        $response->assertJsonStructure([
            'message',
            'user' => ['id', 'name', 'email']
        ]);
    }

    public function test_login_user() 
    {
        //Como la base de datos esta limpia, creamos un usuario
        User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => Hash::make('12345678')
        ]);

        //Request a login endpoint
        $response = $this->postJson('/api/login', [
            'email' => 'test@gmail.com',
            'password' => '12345678'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
            'user' => ['id', 'name', 'email']
        ]);
    }

    public function test_logout_user()
    {
        //Creamos usuario
        $user = User::factory()->create();
        $token = $user->createToken('token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/logout');

        //Verificacion de status y que el token se haya eliminado
        $response->assertStatus(200);
        $this->assertNull($user->fresh()->tokens()->first());
    }
}
