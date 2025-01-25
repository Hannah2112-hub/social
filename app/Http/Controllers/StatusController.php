<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    //método STORE
    public function store()
    {
        //Reglas de validación para indicar qué campos deben ser requeridos
        //Lo que implica -> ¿Qué campos sí o sí NO deben ser registrados vacíos?
        request()->validate(['body'=>'required|min:10']);

        $status = Status::create([
            'user_id' => auth()->id(),
            'body' => request('body')
        ]);
        
        //Redireccionar a la raíz
        return response()->json(['body' => $status->body]);
    }   
}

