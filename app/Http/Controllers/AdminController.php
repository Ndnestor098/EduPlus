<?php

namespace App\Http\Controllers;

use App\Models\Administer;
use App\Services\AdministerServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{
    // Vizualizar la Pagina Principal
    public function index(Request $request)
    {
        if($request->orden){
            $search =  explode("/", $request->orden);

            $admin = Administer::orderBy($search[0], $search[1])->paginate(25);
        }else{
            $admin = Administer::orderBy('name', 'ASC')->paginate(25);
        }

        $admin->appends([
            'orden' => $request->orden
        ]);

        return view('administrator.index', ['admin'=>$admin]);
    }

    // Vizualizar la Pagina Agregar Administrador
    public function showAdd()
    {
        return view('administrator.create');
    }

    // Vizualizar la Pagina de Editar Administrador
    public function showEdit(Request $request)
    {
        $admin = Administer::where("name", $request->name)->where("id", $request->id)->first();

        return view('administrator.edit', ['user'=>$admin]);
    }

    // Cracion de Administrador con metodo PUT
    public function create(Request $request, AdministerServices $requestAdmin)
    {
        //=========Validar las Entradas=========
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'cellphone' => 'required',
            'salary' => 'required',
            'started' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        //Ver si las validaciones se cumplen
        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        //=========Ver si el email ingresado pertenece a otro usuario=========
        $requestAdmin->checkEmailNew($request);
        
        //=========Guardar datos de los nuevos cambios=========
        $requestAdmin->createAdminister($request);

        return redirect(route("administrador"));
    }

    // Actualizacion de Administrador con metodo POST
    public function update(Request $request, AdministerServices $requestAdmin)
    {
        //=========Validar las entradas=========
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'cellphone' => 'required', 
            'salary' => 'required',
            'started' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        //=========Ver si el email ingresado pertenece a otro usuario=========
        $requestAdmin->checkEmailNew($request);

        //=========Guardar datos de los nuevos cambios=========
        $requestAdmin->updateAdminister($request);

        return redirect(route("administrador"));
    }

    // Eliminacion de Administrador con metodo DELETE
    public function destroy(Request $request, AdministerServices $requestAdmin)
    {
        //=========Buscar el id del administrador en la tabla user=========
        $requestAdmin->deleteAdminister($request);

        return redirect(route("administrador"));
    }
}