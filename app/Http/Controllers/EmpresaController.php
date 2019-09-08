<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Empresa;
use App\Models\Tiposervicio;
use App\Models\Responsable;
use App\Models\Sucursal;
use App\Models\Ciclofacturacion;
use App\Models\Cicloproduccion;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmpresaController extends Controller
{

    public function validator(array $data){ 

        return Validator::make($data, [
             'rut' => 'required',
             'dv' => 'required',
             'razon_social' => 'required',               
         ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresas = Empresa::all();
         
          return response()->json(
              [
                  'status' => 'success',
                  'items' => $empresas->toArray(),
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
        //dd($request->all());
        $datos = $request->all();
        $empresa = $datos[0];
        $servicio= $datos[1];
        $responsables = $datos[2];
        $sucursales = $datos[3];
        $cicfac = $datos[4];
        $cicprod = $datos[5];
        
        //Valida datos empresa
        $validation = $this->validator( $empresa);

        if ($validation->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validation->errors(),
                ], 300);
           
        }

        //Agrega Ciclo Produccion
        $cicpro = Cicloproduccion::create($cicprod);
        //Agrega Ciclo Facturacion
        $cicfac = Ciclofacturacion::create($cicfac);
      
        //Agrega Empresa
        $rut = $empresa['rut'];
        $dv = $empresa['dv'];
        $razon_social = $empresa['razon_social'];
        $giro = $empresa['giro'];
        $produccion_id = $cicpro->id;
        $facturacion_id = $cicfac->id;
     
        $emp = Empresa::create(
         array(
                 'rut'                  => $rut,
                 'dv'                   => $dv,
                 'razon_social'         => $razon_social,
                 'giro'                 => $giro,
                 'produccion_id'        => $produccion_id,
                 'facturacion_id'       => $facturacion_id,    
              )
         );


        //Agrega Tipo Servicio
        $servicio["empresa_id"] =  $emp->id;
        $tservicio = Tiposervicio::create($servicio);

        //Agrega Sucursales
         $auxSuc = array();
         
         foreach ($sucursales as $key => $itemsuc) {
            try {
                $direccion = $itemsuc['direccion'].','.$itemsuc['comuna'].','.$itemsuc['ciudad'].','.$itemsuc['pais'];  
                //$geocode = Geocoder::geocode($direccion)->get()->first();
                $geocode = app('geocoder')->geocode($direccion)->get()->first();
                $itemsuc['lat'] = $geocode->getCoordinates()->getLatitude();
                $itemsuc['lng'] = $geocode->getCoordinates()->getLongitude();
              } catch (Exception $e) {
                $itemsuc['lat'] = null;
                $itemsuc['lng'] = null;
            }         
            $itemsuc["empresa_id"] = $emp->id;
            $sucur = Sucursal::create($itemsuc);
            array_push($auxSuc,$sucur); 
         }
         

         //Agrega Responsables
         $auxRes = array();

         foreach ($responsables as $key => $itemres) {
            $itemres["empresa_id"] = $emp->id;
            $respon = Responsable::create($itemres);
            array_push($auxRes,$respon); 
         }

        
        
         return response()->json(
            [
                'status' => 'success',
                'item' => $emp->toArray()
            ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function show(Empresa $empresa, $id)
    {
        $empresa = Empresa::find($id);
        return response()->json(
            [
                'status' => 'success',
                'item' => $empresa->toArray()
            ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function edit(Empresa $empresa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $datos = $request->all();
        $empresa = $datos[0];
        $servicio = $datos[1];
        $responsables = $datos[2];
        $sucursales = $datos[3];
        $cicfac = $datos[4];
        $cicprod = $datos[5];

        $validation = $this->validator($empresa);

        if ($validation->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validation->errors(),
                ], 300);
           
        }
        $emp = Empresa::where('id',$id)->first(); 

        if(!is_null($emp)){

        //Actualizar Ciclo Produccion
        Cicloproduccion::where('id', $cicprod['id'])->update($cicprod);
      
        //Actualizar Ciclo Facturacion
        Ciclofacturacion::where('id', $cicfac['id'])->update($cicfac);

        //Actualizar Tipo Servicio
        Tiposervicio::where('id', $servicio['id'])->update($servicio);
        
        //Actualiza Sucursal   
        $auxsuc = array_column($sucursales, 'id');
        Sucursal::whereNotIn('id', $auxsuc)->delete();
        foreach ($sucursales as $keysuc => $itemsuc) {    
           try {
              $direccion = $itemsuc['direccion'].','.$itemsuc['comuna'].','.$itemsuc['ciudad'].','.$itemsuc['pais'];  
              //$geocode = Geocoder::geocode($direccion)->get()->first();
              $geocode = app('geocoder')->geocode($direccion)->get()->first();
              $itemsuc['lat'] = $geocode->getCoordinates()->getLatitude();
              $itemsuc['lng'] = $geocode->getCoordinates()->getLongitude();
            } catch (Exception $e) {
              $itemsuc['lat'] = null;
              $itemsuc['lng'] = null;
           }
           if (isset($itemsuc['id'])) {
                Sucursal::where('id', $itemsuc['id'])->update($itemsuc);
            }else{
                $itemsuc['empresa_id'] = $id;
                Sucursal::create($itemsuc);
            }
        }

        //Actualiza Responsable   
        $auxres = array_column($responsables, 'id');
        Responsable::whereNotIn('id', $auxres)->delete();
        foreach ($responsables as $keyres => $itemres) {
            if (isset($itemres['id'])) {
                Responsable::where('id', $itemres['id'])->update($itemres);
            }else{
                $itemres['empresa_id'] = $id;
                Responsable::create($itemres);
            }
            
        }

        //Actualizar Empresa   
        Empresa::where('id', $id)->update(
         array(
                'rut'                  => $empresa['rut'],
                'dv'                   => $empresa['dv'],
                'razon_social'         => $empresa['razon_social'],
                'giro'                 => $empresa['giro'],
              )
         );
            
       
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'La empresa ha sido actualizado!!'
                ], 200);


        }else{

            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'El empresa no fue encontrado',
                ], 300);

           
        }
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
          
            $empresa = Empresa::findOrFail($id);
             
            if(!is_null($empresa)){
                 
                 $empresa->delete();
 
                 return response()->json(
                     [
                         'status' => 'success',
                         'message' => 'La empresa ha sido eliminada!!'
                     ], 200);
             
             }else{
         
                 return response()->json(
                     [
                         'status' => 'error',
                         'message' => 'La empresa no existe!!'
                     ], 300);
             }
 
 
         }catch(ModelNotFoundException $e){
             
             return response()->json(
                 [
                     'status' => 'error',
                     'message' => 'La empresa no existe!!'
                 ], 300);
   
         }
 
    }

    public function borrar(Request $request )
    {
      
        
        $ids = array_column($request->all(), 'id');
        
        try{

            if(count($ids) > 0 ){          
                
                Empresa::destroy($ids);
                
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

    public function otros($id)
    {

        $empresa = Empresa::findOrFail($id);
        $tiposervicio = Tiposervicio::where('empresa_id', $id)->get();
        $responsables = Responsable::where('empresa_id', $id)->get();
        $sucursales = Sucursal::where('empresa_id', $id)->get();
        $cicfac = Ciclofacturacion::findOrFail($empresa->facturacion_id);
        $cicpro = Cicloproduccion::findOrFail($empresa->produccion_id);

        return response()->json(
            [
                'status' => 'success',
                'tiposervicio' => $tiposervicio->toArray(),
                'responsables' => $responsables->toArray(),
                'sucursales' => $sucursales->toArray(),
                'cicfac' => $cicfac->toArray(),
                'cicpro' => $cicpro->toArray(),
            ], 200);
    }
}
