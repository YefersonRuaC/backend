<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
        //Aqui podemos controlar la respuesta de la API
        // return [
        //     'id' => $this->id,
        //     'titulo' => $this->titulo,
        //     'descripcion' => $this->descripcion,
        //     'categoria' => $this->categoria
        // ];
    }
}
