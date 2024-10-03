<?php

namespace App\Console\Commands;

use App\Models\Note;
use Illuminate\Console\Command;

class DeleteNote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'note:delete {id}';
    //sail/php artisan note:delete 1

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eliminar nota';

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

        $note->delete();

        $this->info("Nota eliminada exitosamente");
    }
}
