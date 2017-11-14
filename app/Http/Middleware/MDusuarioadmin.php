<?php

namespace sisventas\Http\Middleware;

use Closure;

class MDusuarioadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $usuario_actual=\Auth::user();
        if($usuario_actual->tipo_usuario!='Administrador'){
            return view("mensajes.msj_rechazado")->with("msj","No tiene permisos para esta seccion");
        }
        return $next($request);
    }
}
