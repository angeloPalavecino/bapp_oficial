<?php
namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function validator(array $data){ 

        return Validator::make($data, [
             'empresa_id' => 'required',
             'name' => 'required',
             'lastname' => 'required',
             'rut' => 'required',
             'email' => 'required|email', 
             'telefono' => 'required',
             'password' => 'required|min:8|confirmed',
             'habilitado' => 'required',
             'roles' => 'required'             
         ]);
    }

    public function index()
    {

        $users = User::with('roles')->get(); 
      
        //$user = User::findOrFail('1');
        //$user->syncRoles('1');

        return response()->json(
            [
                'status' => 'success',
                'items' => $users->toArray(),
            ], 200);
    }
    public function show(Request $request, $id)
    {
        $user = User::find($id);
        return response()->json(
            [
                'status' => 'success',
                'item' => $user->toArray()
            ], 200);
    }

    public function store(Request $request)
    {

        $validation = $this->validator($request->all());

        if ($validation->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validation->errors(),
                ], 300);
           
        }

        $input = $request->all();
        $empresa_id = $input['empresa_id'];
        $name = $input['name'];
        $lastname = $input['lastname'];
        $rut = $input['rut'];
        $telefono = $input['telefono'];
        $email = $input['email'];
        $habilitado = $input['habilitado'];
        $password = Hash::make($input['password']);
        //$imagen = $input['imagen'];

        $user = User::create(
         array(
                 'name'         => $name,
                 'lastname'     => $lastname,
                 'rut'          => $rut,
                 'telefono'     => $telefono,
                 'email'        => $email,
                 'habilitado'   => $habilitado,    
                 'password'     => $password,
                 'empresa_id'   => $empresa_id,
                 //'imagen'       => $imagen
              )
         );
        
         //Actualiza Roles
         $user->assignRole($request->get('roles'));

         return response()->json(
            [
                'status' => 'success',
                'item' => $user->toArray()
            ], 200);

    }

    public function destroy($id)
    {
        
        try{
          
           $user = User::findOrFail($id);
            
           if(!is_null($user)){
                
                $user->delete();

                return response()->json(
                    [
                        'status' => 'success',
                        'message' => 'El Usuario ha sido eliminado!!'
                    ], 200);
            
            }else{
        
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'El Usuario no existe!!'
                    ], 300);
            }


        }catch(ModelNotFoundException $e){
            
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'El Usuario no existe!!'
                ], 300);
  
        }

        
    }

    public function borrar(Request $request )
    {
      
        
        $ids = array_column($request->all(), 'id');
        
        try{

            if(count($ids) > 0 ){          
                
                User::destroy($ids);
                
                return response()->json(
                    [
                        'status' => 'success',
                        'message' => 'Los registros ha sido eliminados!'
                    ], 200);

            }else{
        
                    return response()->json(
                        [
                            'status' => 'error',
                            'message' => 'Error al intentar eliminar los registros!'
                        ], 300);
            }
        

        }catch(ModelNotFoundException $e){
            
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Error al intentar eliminar los registros!'
                ], 300);
  
        }

        
    }


    public function update(Request $request, $id)
    {
        $datos = array();
        
        $validar = array(
            'empresa_id' => 'required',
            'name' => 'required',
            'lastname' => 'required',
            'rut' => 'required',
            'telefono' => 'required',
            'habilitado' => 'required',
            'email' => 'required|email', 
            'roles' => 'required'  
        );
        
        if(!is_null($request->get('password'))){
            
            $validar = array_add($validar, 'password' , 'required|min:8|confirmed');
            $password = Hash::make($request->get('password'));
            
            $datos = Arr::add($datos, 'password', $password);

        }
        
        $validation = Validator::make($request->all(), $validar);

        if ($validation->fails()) {

             return response()->json(
                [
                    'status' => 'error',
                    'message' => $validation->errors(),
                ], 300);
        }
        
        $usuario = User::where('id',$id)->first(); 

        if(!is_null($usuario)){
    
            $input = $request->all();
            $empresa_id = $input['empresa_id'];
            $name = $input['name'];
            $lastname = $input['lastname'];
            $rut = $input['rut'];
            $telefono = $input['telefono'];
            $email = $input['email'];
            $habilitado = $input['habilitado'];
            //$imagen = $input['imagen'];

            $datos = Arr::add($datos,'empresa_id', $empresa_id);
            $datos = Arr::add($datos,'name', $name);
            $datos = Arr::add($datos,'lastname', $lastname);
            $datos = Arr::add($datos,'rut', $rut);
            $datos = Arr::add($datos,'telefono', $telefono);
            $datos = Arr::add($datos,'email', $email);
            $datos = Arr::add($datos,'habilitado', $habilitado);
            //$datos = Arr::add($datos,'imagen', $imagen);
            
            //Actualiza Usuario
            User::where('id', $id)->update($datos);
             
            //Actualiza Roles
            //$user->roles()->sync($request->get('roles'));
            $usuario->syncRoles($request->get('roles'));

            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'El Usuario ha sido actualizado!!'
                ], 200);


        }else{

            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'El usuario no fue encontrado',
                ], 300);

           
        }
       
        
    }

    
}

