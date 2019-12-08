<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\GrupoPatron;
use App\Models\GrupoPatronHasPatrones;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GruposPatronesController extends Controller
{

    public function validator(array $data){ 

        return Validator::make($data, [
             'sucursal_id' => 'required',
             'codigo' => 'required',   
             'descripcion' => 'required',   
             'fuerazona' => 'required',          
         ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grupopatron = GrupoPatron::all();
      
        return response()->json(
            [
                'status' => 'success',
                'items' => $grupopatron->toArray(),
            ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        $existe_codigo = GrupoPatron::where('codigo', $request['codigo'])->first();
        if ($existe_codigo != null) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'El codigo ya se encuentra registrado',
                ], 300);           
        }

        $input = $request->all();
        $sucursal_id = $input['sucursal_id'];
        $codigo = $input['codigo'];
        $descripcion = $input['descripcion'];
        $fuerazona = $input['fuerazona'];
        $patrones = $input['patrones'];

        $grupopatron = GrupoPatron::create(
         array(
                'sucursal_id'   => $sucursal_id, 
                'codigo'   => $codigo, 
                'descripcion'   => $descripcion, 
                'fuerazona'   => $fuerazona,              
              )
         );

         //AGREGA PATRONES AL GRUPO
         foreach ($patrones as $keyres => $patron) {
            GrupoPatronHasPatrones::create(array(
                'grupopatrones_id'      => $grupopatron->id,
                'patron_id'             => $patron
            ));            
        }  
        
         return response()->json(
            [
                'status' => 'success',
                'item' => $grupopatron->toArray()
            ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GrupoPatron  $grupoPatron
     * @return \Illuminate\Http\Response
     */
    public function show(GrupoPatron $grupoPatron,$id)
    {
        $grupopatron = GrupoPatron::find($id);
        return response()->json(
            [
                'status' => 'success',
                'item' => $grupopatron->toArray()
            ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GrupoPatron  $grupoPatron
     * @return \Illuminate\Http\Response
     */
    public function edit(GrupoPatron $grupoPatron)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GrupoPatron  $grupoPatron
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validation = $this->validator($request->all());

        if ($validation->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validation->errors(),
                ], 300);
           
        }

        $grupopatron = GrupoPatron::where('id',$id)->first(); 


        if(!is_null($grupopatron)){
    
            $input = $request->all();
            $sucursal_id = $input['sucursal_id'];
            $codigo = $input['codigo'];
            $descripcion = $input['descripcion'];
            $fuerazona = $input['fuerazona'];
            $patrones = $input['patrones'];
           

        $grupopatron = GrupoPatron::where('id', $id)->update(
         array(
                'sucursal_id'   => $sucursal_id, 
                'codigo'   => $codigo, 
                'descripcion'   => $descripcion, 
                'fuerazona'   => $fuerazona, 
              )
         );


        //ACTUALIZA PATRONES AL GRUPO  
        
        $auxpat = array_column($patrones, 'id');
        GrupoPatronHasPatrones::whereIn('patron_id', $auxpat)->delete();

        foreach ($patrones as $keyres => $patron) {
            GrupoPatronHasPatrones::create(array(
                'grupopatrones_id'      => $grupopatron->id,
                'patron_id'             => $patron
            ));                 
        }

            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'El grupo de patrones ha sido actualizado!!'
                ], 200);


        }else{

            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'El grupo de patrones no fue encontrado',
                ], 300);

           
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GrupoPatron  $grupoPatron
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
          
            $grupopatron = GrupoPatron::findOrFail($id);
             
            if(!is_null($grupopatron)){
                 
                 $grupopatron->delete();
 
                 return response()->json(
                     [
                         'status' => 'success',
                         'message' => 'El grupo de patrones ha sido eliminada!!'
                     ], 200);
             
             }else{
         
                 return response()->json(
                     [
                         'status' => 'error',
                         'message' => 'El grupo de patrones no existe!!'
                     ], 300);
             }
 
 
         }catch(ModelNotFoundException $e){
             
             return response()->json(
                 [
                     'status' => 'error',
                     'message' => 'El grupo de patrones no existe!!'
                 ], 300);
   
         }
    }

    public function borrar(Request $request )
    {
      
        
        $ids = array_column($request->all(), 'id');
        
        try{

            if(count($ids) > 0 ){          
                
                GrupoPatron::destroy($ids);
                
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
}
