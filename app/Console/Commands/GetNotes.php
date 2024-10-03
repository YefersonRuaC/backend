<?php

namespace App\Console\Commands;

use App\Models\Note;
use Illuminate\Console\Command;

class GetNotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'note:get {user_id}';
    //sail/php artisan note:get 1

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtener todas las notas de un usuario';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user_id = $this->argument('user_id');
        $notes = Note::where('user_id', $user_id)->get();

        //Validacion
        if($notes->isEmpty()) {
            $this->error("No se encontraron notas del usuario con el Id: {$user_id}");
            return;
        }

        foreach($notes as $note) {
            $this->info("ID: {$note->id} | TITULO: {$note->titulo} | DESCRIPCION: {$note->descripcion} | CATEGORIA: {$note->categoria} | CREACION: {$note->created_at} | VENCIMIENTO: {$note->vencimiento} | IMAGEN_PATH: {$note->imagen}");
        }
    }
}
