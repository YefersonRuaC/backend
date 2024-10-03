<?php

namespace App\Console\Commands;

use App\Models\Note;
use App\Models\User;
use Illuminate\Console\Command;

class CreateNote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //Estructura del comando, y ejemplo
    protected $signature = 'note:create {user_id} {titulo} {descripcion} {categoria} {vencimiento?} {imagen?}';
    //sail/php artisan note:create 1 "tarea pendiente" "esta es la descripcion de la tarea" "trabajo"

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear una nueva nota';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user_id = $this->argument('user_id');
        $titulo = $this->argument('titulo');
        $descripcion = $this->argument('descripcion');
        $categoria = $this->argument('categoria');
        $vencimiento = $this->argument('vencimiento');
        $imagen_path = $this->argument('imagen');

        $user = User::find($user_id);

        //Validacion de si existe un usuario con ese id
        if(!$user) {
            $this->error("No se pudo encontrar el usuario con el Id {$user_id}");
            return;
        }

        $note = Note::create([
            'user_id' => $user_id,
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'categoria' => $categoria,
            'vencimiento' => $vencimiento ?? null,
            'imagen' => $imagen_path ?? null
        ]);

        $this->info("Nota creada exitosamente, Id: {$note->id}");
    }
}
