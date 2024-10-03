<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteRequest;
use App\Http\Resources\NoteCollection;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Obtenemos las notas tomando referencia el id del usuario autenticado y el user_id de la tabla notes
        $notes = Note::with('user')//Cargamos la relacion de user()
                    ->where('user_id', Auth::user()->id)
                    ->orderByDesc('created_at')//Ordenando por fecha de creacion
                    ->get();

        //Respuesta usando NoteCollection para manejar la collection del conjunto de resources
        return new NoteCollection($notes);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    //Usando custom request, con mensajes personalizado segun el caso
    public function store(NoteRequest $request)
    {
        $data = $request->validated();

        //Manejamos la imagen que se guardara en el storage de laravel
        if($request->hasFile('imagen')) {
            $image_path = $request->file('imagen')->store('images', 'public');
            $data['imagen'] = $image_path;//Guardamos el nombre del archivo en 'imagen'
        }
        
        $note = Note::create([
            'titulo' => $data['titulo'],
            'descripcion' => $data['descripcion'],
            'categoria' => $data['categoria'],
            'vencimiento' => $data['vencimiento'] ?? null,//Si no hay fecha de vencimiento o imagen en el
            'imagen' => $data['imagen'] ?? null,//req, dejamos como null
            'user_id' => Auth::user()->id
        ]);
        
        return response()->json([
            'message' => 'Nota creada exitosamente',
            //Usando NoteResource para controlar las respuestas de la API
            'note' => new NoteResource($note)
        ], Response::HTTP_CREATED);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(NoteRequest $request, $id)
    {
        $data = $request->validated();

        $note = Note::findOrFail($id);//Encontramos la nota a ser editada por su id
        // dd($note);

        //Validacion si el id del usuario autenticado y el user_id de la nota no coinciden
        if ($note->user_id !== Auth::user()->id) {
            return response()->json([
                'message' => 'No puedes actualizar esta nota'
            ], Response::HTTP_FORBIDDEN);
        }

        if($request->hasFile('imagen')) {
            //Si hay una nueva imagen
            if($note->imagen) {
                //Y eliminamos la imagen que habia antes
                Storage::disk('public')->delete($note->imagen);
            }

            //Guardamos la imagen nueva
            $image_path = $request->file('imagen')->store('images', 'public');
            $data['imagen']= $image_path;
        }

        $note->update([
            'titulo' => $data['titulo'],
            'descripcion' => $data['descripcion'],
            'categoria' => $data['categoria'],
            'vencimiento' => $data['vencimiento'] ?? null,
            'imagen' => $data['imagen'] ?? $note->imagen//Si no hay nueva imagen, mantenemos la que habia
        ]);

        return response()->json([
            'message' => 'Nota actualizada exitosamente',
            'note' => new NoteResource($note)
        ], Response::HTTP_OK);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $note = Note::findOrFail($id);//Encontramos la nota a ser eliminada por su id

        //Validacion si el usuario autenticado y el user_id de la nota no coinciden
        if ($note->user_id !== Auth::user()->id) {
            return response()->json([
                'message' => 'No puedes eliminar esta nota'
            ], Response::HTTP_FORBIDDEN);
        }

        //Eliminar la imagen de storage
        if($note->imagen) {
            Storage::disk('public')->delete($note->imagen);
        }

        $note->delete();

        return response()->json([
            'message' => 'Nota eliminada exitosamente'
        ], Response::HTTP_OK);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }
}
