<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        $user = User::where('id', '>', 0)->get();
 
        if(User::count() == 0)
        {
            $response = [
                'message' => 'Data user masih kosong !',
                'data' => [],
            ];
        }
        else
        {
            $response = [
                'message' => 'Data user berhasil diambil',
                'data' => $user,
            ];
        }
        
        return response()->json($response, 200);
    }

    public function show($id){
        $user = User::find($id);
        if(is_null($user)){
            $status = 200;
            $response = [
                'message' => 'Data User Tidak Ditemukan',
                'data' => [],
            ];
        }
            
        else   {
            $status = 200;
            $response = [
                'message' => 'Data User Berhasil Ditemukan',
                'data' => [$user],
            ];
        }

        return response()->json($response, $status);
    }

    public function store(Request $request){
        $rules = [
            'nama' => 'required',
            'nim' => 'required',
            'prodi' => 'required',
            'jenis_kelamin' => 'required',
            'fakultas' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            $status = 200;
            $response = [
                'message' => $validator->errors(),
                'data' => [],
            ]; 
        }else{
            try{
                if(is_null(User::where('nim', $request->nim)->first()))
                {
                    $user = User::create($request->all());
                    $user->password = Hash::make($request->password);
                    $user->save(); 
                    $status = 200;
                    $response = [
                        'message' => 'Tambah data berhasil.',
                        'data' => [$user],
                    ]; 
                }
                else
                {
                    $status = 200;
                    $response = [
                        'message' => 'NIM sudah terdaftar.',
                        'data' => [],
                    ]; 
                }
            }
            catch(\Illuminate\Database\QueryException $e){
                $status = 200;
                echo $e;
                $response = [
                    'message' => $e,
                    'data' => [],
                ];
            }
        }
        
        return response()->json($response, $status);
    }

    public function update(Request $request, $id){
        $user = User::find($id);

        if(is_null($user)){
            $status=200;
            $response = [
                'message' => 'Data Tidak Ditemukan',
                'data' => [],
            ];
        }
        else{
            try{
                $user->update($request->all());
                $user->password = Hash::make($request->password);
                $user->save(); 
                $status = 200;
                $response = [
                    'message' => 'Edit data berhasil.',
                    'data' => [$user],
                ];  
            }
            catch(\Illuminate\Database\QueryException $e){
                $status = 200;
                $response = [
                    'message' => $e,
                    'data' => [],
                ];
            }
        }
        return response()->json($response, $status); 
    }

    public function destroy($id){
        $user = User::find($id);

        if(is_null($user)){
            $status=200;
            $response = [
                'message' => 'Data Tidak Ditemukan',
                'data' => [],
            ];
        }else{
            $user->delete();
            $status=200;
            $response = [
                'message' => 'Hapus data berhasil.',
                'data' => [$user],
            ];
        }
        return response()->json($response, $status);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'nim' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                    'data' => [],
            ], 200);
        }

        $user = User::where('nim', $request->nim)->first();

        if(is_null($user)){
            $status=200;
            $response = [
                'message' => 'NIM belum terdaftar',
                'data' => [],
            ];
        }else{
            if(Hash::check($request->password, $user->password)){
                $status=200;
                $response = [
                    'message' => 'Berhasil login',
                    'data' => [$user],
                ];
            }else{
                $status=200;
                $response = [
                    'message' => 'Password salah',
                    'data' => [],
                ];
            }
        }

        return response()->json($response, $status);
    }
}
