<?php

namespace App\Console\Commands;

use App\Models\Note;
use Illuminate\Console\Command;

class UpdateNote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'note:update {id} {titulo?} {descripcion?} {categoria?} {vencimiento?} {imagen?}';
    //sail/php artisan note:update 1 ""

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar una nota';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $note = Note::find($this->argument('id'));

        if(!$note) {
            $this->error('Nota no encontrada');
            return;
        }

        $note->update([
            //Si no hay un nuevo valor en argunment, mantener el valor de la nota en la BD
            'titulo' => $this->argument('titulo') ?? $note->titulo,
            'descripcion' => $this->argument('descripcion') ?? $note->descripcion,
            'categoria' => $this->argument('categoria') ?? $note->categoria,
            'vencimiento' => $this->argument('vencimiento') ?? $note->vencimiento,
            'imagen' => $this->argument('imagen') ?? $note->imagen
        ]);

        $this->info("Nota actualizada exitosamente, Id: {$note->id}");
    }
}
