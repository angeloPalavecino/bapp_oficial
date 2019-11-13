<?php

namespace App\Http\Controllers;
use Validator;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\CarsHasDocument;
use App\Models\Document;
use App\Models\DriversHasCars;
use Illuminate\Support\Facades\Storage;


class CarController extends Controller
{
    
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
         ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $car = Car::all();
       return response()->json(
        [
            'status' => 'success',
            'items' => $car->toArray(),
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

        $validationCar = $this->validatorCar($request->all());
        if ($validationCar->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validationDriver->errors(),
                ], 300);
           
        }
        $returnCar = Car::create($request->all());

        $idCar = $returnCar->id;

        if ($idCar < 1) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Problemas al ingresar el movil',
                ], 300);
        } 
        
        $dataDriversHasCars =  array(
            'driver_id'  => $request["driver_id"],
            'car_id'     => $idCar,
            'habilitado' => true,
        );
        $returnDriverHasCars = DriversHasCars::create($dataDriversHasCars);       
        $returnIdDriversHasCars = $returnDriverHasCars->id;
        if ($returnIdDriversHasCars < 1) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Problemas al datos de movil en la relación',
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
        $getCar = Car::where('id', $id)->first();
        $validationCar = $this->validatorCar($request->all());
        
        if ($validationCar->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validationCar->errors(),
                ], 300);
           
        }
        Car::where('id', $id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function upload(Request $request)
    {

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();  
        $fileNameSinExtencion = $request->numero_movil."-".$request->tipo_documento;
        $fileName = $request->numero_movil."-".$request->tipo_documento.'.'.$extension;//$file->getClientOriginalName();
        $exists = Document::where('name', $fileNameSinExtencion)->first();
        $uploadFile = Storage::disk('local')->put('cars/'.$fileName, file_get_contents($file));

        if($uploadFile == true)
        {

            $url = '/documents/cars/'.$fileName;
            
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
                    $dataHas = CarsHasDocument::create(
                        array(
                            'car_id'     => $request->car_id,
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
        
        
        $driver = CarsHasDocument::with('documents')->where('car_id', $id)->get();
        
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
