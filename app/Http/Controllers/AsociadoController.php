<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Driver;
use App\Models\User;
use App\Models\Car;
use App\Models\Document;
use App\Models\DriversHasDocuments;
use App\Models\DriversHasDrivers;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;

class AsociadoController extends Controller
{
    public function validatorAsociado(array $data){ 

        return Validator::make($data, [
            'name' => 'required',
            'lastname' => 'required',
            'rut' => 'required',
            'email' => 'required|email', 
            'telefono' => 'required',
            'habilitado' => 'required',
            'ciudad' => 'required',
            'comuna' => 'required',
            'direccion' => 'required',
            'numeracion' => 'required',
         ]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $asociado = Driver::withCount(['cars','conductores'])->where('dueno', '=', 1)->get();

        return response()->json(
            [
                'status' => 'success',
                'items' => $asociado->toArray(),
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
        
        //Asociado
        $validationAsociado = $this->validatorAsociado($request->all());

        if ($validationAsociado->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validationAsociado->errors(),
                ], 300);
           
        }
        $existe_asociado = Driver::where('rut', $request['rut'])->first();
        if ($existe_asociado != null) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'El asociado ya se encuentra registrado',
                ], 300);           
        }

        $returnAsociado = Driver::create($request->all());

        $idDriver = $returnAsociado->id;

        if ($idDriver < 1) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Problemas al ingresar el Asociado',
                ], 300);
        } 

            if($request->get('conductor') == 1) {

                $dataDriversHasDrivers =  array(
                    'driver_id'       => $idDriver,
                    'asociado_id'     => $idDriver,
                    'habilitado'      => true,
                );

               $returnDriverHasDrivers = DriversHasDrivers::create($dataDriversHasDrivers); 
               
               $returnIdDriversHasDrivers = $returnDriverHasDrivers->id;
            
               if ($returnIdDriversHasDrivers < 1) {
                       return response()->json(
                           [
                               'status' => 'error',
                               'message' => 'Problemas con la relacion',
                           ], 300);
               } 
            }
        
              
    }
         
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

       
        $validationAsociado = $this->validatorAsociado($request->all());
        
        if ($validationAsociado->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validationAsociado->errors(),
                ], 300);
           
        }

        Driver::where('id', $id)->update($request->all());
        
        $conductor = $request->get('conductor');

        if($conductor == 1){

                $dataDriversHasDrivers =  array(
                        'driver_id'       => $id,
                        'asociado_id'     => $id,
                        'habilitado'      => true,
                );

                $returnDriverHasDrivers = DriversHasDrivers::create($dataDriversHasDrivers);       
                $returnIdDriversHasDrivers = $returnDriverHasDrivers->id;
                
                if ($returnIdDriversHasDrivers < 1) {
                        return response()->json(
                            [
                                'status' => 'error',
                                'message' => 'Problemas con la relacion',
                            ], 300);
                } 

            }else{

                DriversHasDrivers::where('driver_id', $id)->delete();
                
                //Al dejar de ser conductor, eliminar documentos?
                $document = DriversHasDocuments::where('driver_id', $id)->get();
                $idsDocument = array_column($document->toArray(), 'document_id'); 

                foreach ($idsDocument as $key => $doc) {
                    $documento = Document::findOrFail($doc);
                    Storage::disk('delete')->delete($documento->url);
                    $documento->delete();
                }
          
            }

        
        // $dataUser = $request->all()['user'];
        // $dataDriver = $resultado = array_merge($dataUser, $request->all()['driver']);
        // $dataCar = $request->all()['car'];

        // $getDriver = Driver::where('id', $dataUser['id'])->first();

        // $dataDriver['user_id'] = $getDriver->user_id;
        // $dataDriver['habilitado'] = $dataUser['habilitado'];
        // $validationDriver = $this->validatorAsociado($dataDriver);
        
        // if ($validationDriver->fails()) {

        //     return response()->json(
        //         [
        //             'status' => 'error',
        //             'message' => $validationDriver->errors(),
        //         ], 300);
           
        // }
        // Driver::where('id', $dataDriver['id'])->update($dataDriver);
        
        // $dataCar['driver_id'] = $dataDriver['id'];
        // $dataCar['habilitado'] = $dataUser['habilitado'];
        // $validationCar = $this->validatorCar($dataCar);

        // if ($validationCar->fails()) {

        //     return response()->json(
        //         [
        //             'status' => 'error',
        //             'message' => $validationCar->errors(),
        //         ], 300);
           
        // }
        
        // Car::where('id', $dataCar['id'])->update($dataCar);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        try{
          
           $driver = Driver::findOrFail($id);
            
           if(!is_null($driver)){
                
                $driver->delete();

                return response()->json(
                    [
                        'status' => 'success',
                        'message' => 'El Asociado ha sido eliminado!!'
                    ], 200);
            
            }else{
        
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'El Asociado no existe!!'
                    ], 300);
            }


        }catch(ModelNotFoundException $e){
            
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'El Asociado no existe!!'
                ], 300);
  
        }

        
    }

    public function borrar(Request $request)
    {
      
        
        $ids = array_column($request->all(), 'id');
        
        try{

            if(count($ids) > 0 ){          
                
                Driver::destroy($ids);
                
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
