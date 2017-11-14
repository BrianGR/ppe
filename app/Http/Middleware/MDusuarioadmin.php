<?php

namespace sisventas\Http\Middleware;

use Session;
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
            Session::flash('message-error','Debe ser Usuario Adminitrador para poder acceder a  esta secci�n, redirigiendo a vista principal');
            return redirect()->to('ventas/venta');
        }
        return $next($request);
    }
}
