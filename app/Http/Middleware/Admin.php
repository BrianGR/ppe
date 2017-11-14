<?php

namespace sisventas\Http\Middleware;
use Illuminate\Contracts\Auth\Guard;
use Closure;
use Session;

class Admin
{
    protected $auth;

    public function __construct(Guard $auth){
        $this->auth = $auth;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //if($this->auth->user()->tipoUsuario != 1){
        if(($this->auth->user()->tipo_usuario != 'Administrador') ){

            Session::flash('message-error','Debe ser Usuario Adminitrador para poder acceder a  esta sección');
            return redirect()->to('home');
        }
        return $next($request);
    }
}
