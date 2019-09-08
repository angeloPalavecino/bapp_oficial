<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;


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
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $driver = Driver::all();
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

        $dataUser['habilitado'] = 1;
        $dataUser['empresa_id'] = 1;
        $rut = substr($dataUser['rut'], 0, -1);
        $dataUser['password'] = ((strlen($rut) > 9) ? Hash::make($rut) : Hash::make('0'+$rut));

        $validation = $this->validatorUser($dataUser);

        if ($validation->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validation->errors(),
                ], 300);
           
        }
        
        $returnUser = User::create($dataUser);
        
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
        $dataDriver['habilitado'] = 1;
        $dataDriver['user_id'] = $idUser;
        $validationDriver = $this->validatorDriver($dataDriver);

        if ($validationDriver->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validationDriver->errors(),
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
        //Documento Driver

        //Car
        
        //Dcoumento Car
       
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
        //
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
}
