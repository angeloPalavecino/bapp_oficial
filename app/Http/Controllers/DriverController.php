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



class DriverController extends Controller
{
    
    public function validatorDriver(array $data){ 

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
        //$driver = Driver::all();
        $driver = Driver::withCount(['cars','conductores'])->where('dueno', '=', 1)->get();

        return response()->json(
            [
                'status' => 'success',
                'items' => $driver->toArray(),
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
        
        //Driver
        $validationDriver = $this->validatorDriver($request->all());
        if ($validationDriver->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validationDriver->errors(),
                ], 300);
           
        }
        $existe_driver = Driver::where('rut', $request['rut'])->first();
        if ($existe_driver != null) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'El rut ya se encuentra registrado',
                ], 300);           
        }

        $returnDriver = Driver::create($request->all());

        $idDriver = $returnDriver->id;

        if ($idDriver < 1) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Problemas al ingresar el Conductor',
                ], 300);
        } 

        $idAsociado = $request->get('driver_id');

        if($idAsociado){
            
            $dataDriversHasDrivers =  array(
                  'driver_id'       => $idDriver,
                  'asociado_id'     => $idAsociado,
                  'habilitado'      => true,
            );
        } else {
            $dataDriversHasDrivers =  array(
                'driver_id'       => $idDriver,
                'asociado_id'     => $idDriver,
                'habilitado'      => true,
          );
        }
          
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
         
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //0 -- Asociados
        //1 -- Conductores
        
        if($id == 0){
           //$driver = Driver::all();
           $driver = Driver::withCount(['cars','conductores'])->where('dueno', '=', 1)->get();
           
        }else{
           $driver = Driver::with('asociados')->where('conductor', '=', 1)->get();
        }
        //dd($driver->toArray());
       
        return response()->json(
            [
                'status' => 'success',
                'items' => $driver->toArray(),
            ], 200); 
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

       
        $validationDriver = $this->validatorDriver($request->all());
        
        if ($validationDriver->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validationDriver->errors(),
                ], 300);
           
        }
        Driver::where('id', $id)->update($request->except(['driver_id']));

        $dataDriversHasDrivers =  array(
            'asociado_id'  => $request['driver_id'],
        );
    
        DriversHasDrivers::where('driver_id', $id)->update($dataDriversHasDrivers);

        // $dataUser = $request->all()['user'];
        // $dataDriver = $resultado = array_merge($dataUser, $request->all()['driver']);
        // $dataCar = $request->all()['car'];

        // $getDriver = Driver::where('id', $dataUser['id'])->first();

        // $dataDriver['user_id'] = $getDriver->user_id;
        // $dataDriver['habilitado'] = $dataUser['habilitado'];
        // $validationDriver = $this->validatorDriver($dataDriver);
        
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
                        'message' => 'El Conductor ha sido eliminado!!'
                    ], 200);
            
            }else{
        
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'El Conductor no existe!!'
                    ], 300);
            }


        }catch(ModelNotFoundException $e){
            
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'El Conductor no existe!!'
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

    public function upload(Request $request)
    {

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();  
        $fileNameSinExtencion = $request->id."-".$request->tipo_documento;//rut
        $fileName = $request->id."-".$request->tipo_documento.'.'.$extension;//$file->getClientOriginalName(); rut
        $exists = Document::where('name', $fileNameSinExtencion)->first();
        $uploadFile = Storage::disk('local')->put('drivers/'.$fileName, file_get_contents($file));

        if($uploadFile == true)
        {

            $url = '/documents/drivers/'.$fileName;
            
            $dataDocument = array(
                'type_document_id'  => $request->tipo_documento_id,
                'name'              => $fileNameSinExtencion,
                'url'               => $url,
                'fecha_vencimiento' => date($request->fecha_vencimiento),
                'informacion'       => "",
                'habilitado'        => 1
            );
            if($exists != null)
            {
                Storage::disk('delete')->delete($exists->url);
                Document::where('name', $fileNameSinExtencion)->update($dataDocument);
            }
            else
            {
                $documentResult = Document::create($dataDocument);
                if($documentResult->id > 0)
                {   
                    $dataHas = DriversHasDocuments::create(
                        array(
                            'driver_id'     => $request->driver_id,
                            'document_id'   => $documentResult->id,
                            'habilitado'    => 1
                        )
                    );
    
                    if($dataHas->id > 0)
                    {
                        dd($dataHas);
                    }
                    else
                    {
                        dd("Fallo");
                    }
                }
                else
                {
                    dd("Error");
                } 
            }       
        }
        else
        {
            dd("Fallo");
        }
              
        return response()->json(
            [
                'status' => 'success',
            ], 200);     
    }

    public function documents ($id)
    {
        
        
        $driver = DriversHasDocuments::with('documents')->where('driver_id', $id)->get();
        
        return response()->json(
            [
                'status' => 'success',
                'items' => $driver->toArray(),
            ], 200); 
    }

    public function document ($id)
    {
        $document = Document::where('id', $id)->first();
        return response()->download(storage_path($document->url), $document->name);
    }

    //Moviles para usuarios
    public function cars()
    {
       
        $cars = Car::select('id', 'numero_movil','patente')->get();

        return response()->json(
            [
                'status' => 'success',
                'items' => $cars->toArray(),
            ], 200);    
    }
}
