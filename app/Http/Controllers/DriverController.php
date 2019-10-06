<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Driver;
use App\Models\User;
use App\Models\Car;
use App\Models\Document;
use App\Models\DriversHasDocument;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;



class DriverController extends Controller
{
    
    public function validatorUser(array $data){ 

        return Validator::make($data, [
             'empresa_id' => 'required',
             'name' => 'required',
             'lastname' => 'required',
             'rut' => 'required',
             'email' => 'required|email', 
             'telefono' => 'required',
             'password' => 'required|min:8',
             'habilitado' => 'required',
         ]);
    }

    public function validatorDriver(array $data){ 

        return Validator::make($data, [
            'user_id' => 'required',
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

    public function validatorCar(array $data){ 

        return Validator::make($data, [
            'tipo' => 'required',
            'marca' => 'required',
            'modelo' => 'required',
            'ano' => 'required|numeric',
            'motor' => 'required', 
            'patente' => 'required',
            'habilitado' => 'required',
            'color' => 'required',
            'asientos' => 'required|numeric',
            'driver_id' => 'required',
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
        $driver = Driver::with('cars')->get();
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
               
        //User
        $dataUser = $request->all()['user'];
        $dataDriver = $resultado = array_merge($dataUser, $request->all()['driver']);
        $dataCar = $request->all()['car'];

        //$dataUser['habilitado'] = 1;
        $dataUser['empresa_id'] = 1;
        $rut = substr($dataUser['rut'], 0, -1);
        $dataUser['password'] = ((strlen($rut) > 9) ? Hash::make($rut) : Hash::make('0'+$rut));

        $existe_email = User::where('email', $dataUser['email'])->first();
        if($existe_email != null)
        {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'El correo se encuentra registrado',
                ], 300);
        }
        $validation = $this->validatorUser($dataUser);

        if ($validation->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validation->errors(),
                ], 300);
           
        }
        $existe_usuario = User::where('rut', $dataUser['rut'])->first();
        if($existe_usuario != null)
        {            
            $returnUser = $existe_usuario;            
        }
        else
        {
            $returnUser = User::create($dataUser);
        }

        $idUser = $returnUser->id;
        if ($idUser < 1) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Problemas al ingresar el usuario',
                ], 300);
        }

        $returnUser->assignRole(1);

        //Driver
        $dataDriver['habilitado'] = $dataUser['habilitado'];//1;
        $dataDriver['user_id'] = $idUser;
        $validationDriver = $this->validatorDriver($dataDriver);

        

        if ($validationDriver->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validationDriver->errors(),
                ], 300);
           
        }
        $existe_driver = Driver::where('rut', $dataDriver['rut'])->first();
        if ($existe_driver != null) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Conductor ya existe',
                ], 300);
           
        }
        $returnDriver = Driver::create($dataDriver);

        $idDriver = $returnDriver->id;

        if ($idDriver < 1) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Problemas al ingresar el Conductor',
                ], 300);
        }

        //Car 
        $dataCar['driver_id'] = $idDriver;
        //$dataCar['habilitado'] = 1;
        $dataCar['habilitado'] = $dataUser['habilitado'];

        $validationCar = $this->validatorCar($dataCar);

        if ($validationCar->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validationCar->errors(),
                ], 300);
           
        }

        $returnCar = Car::create($dataCar);

        $idCar = $returnCar->id;

        if ($idCar < 1) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Problemas al ingresar el Movil',
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
        //
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
        $dataUser = $request->all()['user'];
        $dataDriver = $resultado = array_merge($dataUser, $request->all()['driver']);
        $dataCar = $request->all()['car'];

        $getDriver = Driver::where('id', $dataUser['id'])->first();

        $dataDriver['user_id'] = $getDriver->user_id;
        $dataDriver['habilitado'] = $dataUser['habilitado'];
        $validationDriver = $this->validatorDriver($dataDriver);
        
        if ($validationDriver->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validationDriver->errors(),
                ], 300);
           
        }
        Driver::where('id', $dataDriver['id'])->update($dataDriver);
        
        $dataCar['driver_id'] = $dataDriver['id'];
        $dataCar['habilitado'] = $dataUser['habilitado'];
        $validationCar = $this->validatorCar($dataCar);

        if ($validationCar->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validationCar->errors(),
                ], 300);
           
        }
        
        Car::where('id', $dataCar['id'])->update($dataCar);

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
        $fileNameSinExtencion = $request->rut."-".$request->tipo_documento;
        $fileName = $request->rut."-".$request->tipo_documento.'.'.$extension;//$file->getClientOriginalName();
        $exists = Document::where('name', $fileNameSinExtencion)->first();
        $uploadFile = Storage::disk('local')->put($fileName, file_get_contents($file));

        if($uploadFile == true)
        {

            $url = '/documents/'.$fileName;
            
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
                    $dataHas = DriversHasDocument::create(
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
            ], 200);     }

    public function documents ($id)
    {
        
        
        $driver = DriversHasDocument::with('documents')->where('driver_id', $id)->get();
        
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

}
