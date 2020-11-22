<?php

namespace App\Http\Controllers;

use App\Buku;
use Illuminate\Http\Request;
use Carbon\Carbon;
use finfo;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::select('idBuku','namaBuku','pengarang','harga','gambar')->get();
        $response = [
            'status' => 'Success',
            'dataBuku' => $buku,
            'message' => 'Data Buku berhasil ditampilkan'
        ];
        return response()->json($response,200);
    }

    public function tambah(Request $request)
    {
        $buku = new Buku;
        $buku->namaBuku     = $request['namaBuku'];
        $buku->pengarang    = $request['pengarang'];
        $buku->harga        = $request['harga'];
        
        if($request['gambar'] == '')
            $buku->gambar = 'default.jpg';
        else
            $buku->gambar = $this->saveBase64ToImage($request['gambar']);
        
        try{
            $success = $buku->save();
            $status = 200;
            $response = [
                'status' => 'Success',
                'dataBuku' => $buku,
                'message' => 'Tambah data Buku berhasil.'
            ];   
        }
        catch(\Illuminate\Database\QueryException $e){
            $status = 500;
            $response = [
                'status' => 'Error',
                'dataBuku' => [],
                'message' => $e
            ];
        }
        return response()->json($response,$status);
    }

    public function edit(Request $request, $id)
    {
        $buku = Buku::find($id);

        if($buku==NULL){
            $status=404;
            $response = [
                'status' => 'Error',
                'dataBuku' => [],
                'message' => 'Buku Tidak Ditemukan'
            ];
        }
        else
        {
            $buku->namaBuku     = $request['namaBuku'];
            $buku->pengarang    = $request['pengarang'];
            $buku->harga        = $request['harga'];
            
            if($buku->idBuku  <=4 )
            {
                $status=200;
                $response = [
                    'status' => 'Warning',
                    'dataBuku' => [],
                    'message' => 'Cie mau diedit ya, ndak boleh ya.'
                ];
            }
            else
            {
                if($request['gambar'] != ''){
                    $path = '/home/u7929630/public_html/pbp/images/';
                    $target = $path . $buku->gambar;
                    
                    if(file_exists($target) && $buku->gambar != "default.jpg")
                        unlink($target);
                    $buku->gambar= $this->saveBase64ToImage($request['gambar']);
                }
    
                try{
                    $success = $buku->save();
                    $status = 200;
                    $response = [
                        'status' => 'Success',
                        'dataBuku' => $buku,
                        'message' => 'Ubah data Buku berhasil.'
                    ];   
                }
                catch(\Illuminate\Database\QueryException $e){
                    $status = 500;
                    $response = [
                        'status' => 'Error',
                        'dataBuku' => [],
                        'message' => $e
                    ];
                }
            }
        }
        return response()->json($response,$status); 
    }

    public function hapus($id)
    {
        $buku = Buku::find($id);

        if($buku==NULL){
            $status=404;
            $response = [
                'status' => 'Error',
                'dataBuku' => [],
                'message' => 'Buku Tidak Ditemukan'
            ];
        }
        else
        {
            if($buku->idBuku  <=4 )
            {
                $status=200;
                $response = [
                    'status' => 'Warning',
                    'dataBuku' => [],
                    'message' => 'Cie mau dihapus ya, ndak boleh ya.'
                ];
            }
            else
            {
                if($buku->gambar != "default.jpg")
                {
                    $path = '/home/u7929630/public_html/pbp/images/';
                    $target = $path . $buku->gambar;
                    
                    if(file_exists($target))
                        unlink($target);
                }
                    
                $buku->delete();
                $status=200;
                $response = [
                    'status' => 'Success',
                    'dataBuku' => $buku,
                    'message' => 'Hapus data Buku berhasil.'
                ]; 
            }
        }
        return response()->json($response,$status); 
    }

    function saveBase64ToImage($image) {
        $path = '/home/u7929630/public_html/pbp/images/';
        $base = $image;
        $binary = base64_decode($base);
        header('Content-Type: bitmap; charset=utf-8');

        $f = finfo_open();
        $mime_type = finfo_buffer($f, $binary, FILEINFO_MIME_TYPE);
        $mime_type = str_ireplace('image/', '', $mime_type);

        $filename = md5(Carbon::now()) . '.' . $mime_type;
        $file = fopen($path . $filename, 'wb');
        if (fwrite($file, $binary)) {
            return $filename;
        } else {
            return FALSE;
        }
        fclose($file);
    }
}
