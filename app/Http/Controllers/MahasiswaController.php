<?php

namespace App\Http\Controllers;

use App\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    public function index(){
        $mhs = Mahasiswa::get();
 
        if(Mahasiswa::count() == 0)
        {
            $response = [
                'status' => 'Error',
                'mahasiswa' => [],
                'message' => 'Belum ada mahasiswa mahasiswa yang diinputkan !'
            ];
        }
        else
        {
            $response = [
                'status' => 'Success',
                'mahasiswa' => $mhs,
                'message' => 'mahasiswa mahasiswa berhasil ditampilkan !'
            ];
        }
        
        return response()->json($response, 200);
    }

    public function cari($cari){
        
        $mhs = Mahasiswa::where('npm','like','%'.$cari.'%')
        ->orWhere('nama','like','%'.$cari.'%')
        ->orWhere('prodi','like','%'.$cari.'%')
        ->orWhere('jenis_kelamin','like','%'.$cari.'%')
        ->get();

        if(sizeof($mhs)==0)
        {
            $status=404;
            $response = [
                'status' => 'Error',
                'mahasiswa' => [],
                'message' => 'Mahasiswa tidak ditemukan !'
            ];
        }
        else{
                
            $status=200;
            $response = [
                'status' => 'Success',
                'mahasiswa' => $mhs,
                'message' => 'Mahasiswa ditemukan !'
            ];
        }
        return response()->json($response,$status); 
    }

    public function tambah(Request $request){
        $rules = [
            'npm' => 'required',
            'nama' => 'required',
            'prodi' => 'required',
            'jenis_kelamin' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            $status = 400;
            $response = [
                'status' => 'Error',
                'mahasiswa' => [],
                'message' => $validator->errors(),
            ]; 
        }
        else
        {
            try{
                $mhs = new Mahasiswa;
                $mhs->npm           = $request['npm'];
                $mhs->nama          = $request['nama'];
                $mhs->jenis_kelamin = $request['jenis_kelamin'];
                $mhs->prodi         = $request['prodi'];
                if($mhs->jenis_kelamin == 'Laki-Laki')
                    $mhs->gambar = 'https://icons-for-free.com/iconfiles/png/512/business+costume+male+man+office+user+icon-1320196264882354682.png';
                else
                    $mhs->gambar = 'https://icons-for-free.com/iconfiles/png/512/female+person+user+woman+young+icon-1320196266256009072.png';
                    
                if($this->cekUnikNpm($mhs->npm))
                {
                    $mhs->save(); 
                    
                    $status = 200;
                    $response = [
                        'status' => 'Success',
                        'mahasiswa' => $mhs,
                        'message' => 'Data mahasiswa berhasil ditambahkan',
                    ]; 
                }
                else
                {
                    $status = 200;
                    $response = [
                        'status' => 'Error',
                        'mahasiswa' => [],
                        'message' => 'Npm ini sudah digunakan.',
                    ]; 
                }
            }
            catch(\Illuminate\mahasiswabase\QueryException $e){
                $status = 400;
                $response = [
                    'status' => 'Error',
                    'mahasiswa' => [],
                    'message' => $e,
                ];
            }
        }
        
        return response()->json($response, $status);
    }

    public function ubah(Request $request, $id){
        $mhs = Mahasiswa::find($id);

        if($mhs==NULL)
        {
            $status=404;
            $response = [
                'status' => 'Error',
                'mahasiswa' => [],
                'message' => 'Mahasiswa tidak ditemukan !'
            ];
        }
        else{
            try{
                $mhs->nama          = $request['nama'];
                $mhs->jenis_kelamin = $request['jenis_kelamin'];
                $mhs->prodi         = $request['prodi'];
                if($mhs->jenis_kelamin == 'Laki-Laki')
                    $mhs->gambar = 'https://icons-for-free.com/iconfiles/png/512/business+costume+male+man+office+user+icon-1320196264882354682.png';
                else
                    $mhs->gambar = 'https://icons-for-free.com/iconfiles/png/512/female+person+user+woman+young+icon-1320196266256009072.png';
                $mhs->save();
                $status = 200;
                $response = [
                    'status' => 'Success',
                    'mahasiswa' => $mhs,
                    'message' => 'Data mahasiswa berhasil diubah',
                ];  
            }
            catch(\Illuminate\mahasiswabase\QueryException $e){
                $status = 200;
                $response = [
                    'status' => 'Error',
                    'mahasiswa' => [],
                    'message' => $e
                ];
            }
        }
        return response()->json($response, $status); 
    }

    public function hapus($id){
       $mhs = Mahasiswa::find($id);

        if($mhs==NULL)
        {
            $status=404;
            $response = [
                'status' => 'Error',
                'mahasiswa' => [],
                'message' => 'Mahasiswa tidak ditemukan !'
            ];
        }
        else
        {
            $mhs->delete();
            $status=200;
            $response = [
                'status' => 'Success',
                'mahasiswa' => $mhs,
                'message' => 'Data mahasiswa berhasil dihapus',
            ];
        }
        return response()->json($response, $status);
    }
    
    public function cekUnikNpm($npm)
    {
        $mhs = Mahasiswa::get();
        
        foreach($mhs as $m)
        {
            if($m->npm == $npm)
                return false;
        }
        
        return true;
    }
}
