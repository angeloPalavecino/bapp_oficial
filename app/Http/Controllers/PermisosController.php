<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Spatie\Permission\Models\Permission;


class PermisosController extends Controller
{
     
    public function index()
    {
        $permisos = Permission::all();
        $x = -1;
        $aux = array();
        $auxNom = [];
        $Nom = "";
        

        foreach ($permisos->toArray() as $items) {
            $auxNom = explode('.', $items['name'], 2);  
            
            if($Nom != $auxNom[0]){
                $Nom = $auxNom[0];
                $x++;
                $aux[$x] = [ "modulos" => PermisosController::nombres($Nom) , "roles" => [], ];
                
            }            
           
           array_push( $aux[$x]['roles'] , [ "name" => PermisosController::nombres($Nom)." - ".PermisosController::nombres($auxNom[1]), "id" => $items['id']]);
            
        }

        /*foreach ($permisos->toArray() as $items) {
            $auxNom = explode('.', $items['name'], 2);  
            
            if($Nom != $auxNom[0]){
                $Nom = $auxNom[0];
                $x++;
                $aux[$x] = [ "title" => $Nom , "expanded" => true, "children" => [], ];
                
            }            
           
           array_push( $aux[$x]['children'] , [ "title" => $auxNom[1], "id" => $items['id'], "expanded" => true]);
            
        }*/
   
   
        return response()->json(
            [
                'status' => 'success',
                'permisos' => $permisos->toArray(),
                'permisos_aux' => $aux,
            ], 200);
    }


    public function nombres($nombre){
        switch ($nombre) {
            case "users":
                return "Usuario";
                break;
            case "roles":
                return "Roles";
                break;
            case "index":
                return "Ver";
                break;
            case "edit":
                return "Editar";
                break;
            case "show":
                return "Detalle";
            case "destroy":
                return "Eliminar";
                break;
            case "create":
                return "Crear";
                break;
        }

    } 


}
