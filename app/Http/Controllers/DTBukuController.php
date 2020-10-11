<?php

namespace App\Http\Controllers;

use App\DTBuku;

use Illuminate\Http\Request;
use Carbon\Carbon;

class DTBukuController extends Controller
{
    public function index()
    {
        $dtbuku = DTBuku::join('buku', 'dtbuku.idBuku', '=', 'buku.idBuku')
        ->select('dtbuku.*', 'buku.namaBuku', 'buku.pengarang', 'buku.harga',
        'buku.gambar')->get();

        $response = [
            'status' => 'Success',
            'DTBuku' => $dtbuku
        ];
        return response()->json($response,200);
    }
    
    public function tampilDetailBuku($noTransaksi)
     {
        $dtbuku = DTBuku::join('buku', 'dtbuku.idBuku', '=', 'buku.idBuku')
        ->select('dtbuku.*', 'buku.namaBuku', 'buku.pengarang', 'buku.harga','buku.gambar')
        ->where('dtbuku.noTransaksi',$noTransaksi)
        ->get();

        $response = [
            'status' => 'Success',
            'DTBuku' => $dtbuku
        ];
        return response()->json($response,200);
     }

    public function tambah(Request $request)
    {
        $dtbuku = new DTBuku;
        $dtbuku->noTransaksi = $request['noTransaksi'];
        $dtbuku->idBuku = $request['idBuku'];
        $dtbuku->jumlah = $request['jumlah'];

        try{
            $success = $dtbuku->save();
            $status = 200;
            $response = [
                'status' => 'Success',
                'DTBuku' => [$dtbuku]
            ];   
        }
        catch(\Illuminate\Database\QueryException $e){
            $status = 500;
            $response = [
                'status' => 'Error',
                'DTBuku' => [],
                'message' => $e
            ];
        }
        return response()->json($response,$status);
    }

    public function edit(Request $request)
    {
        $noTransaksi = $request['noTransaksi'];
        $idBuku = $request['idBuku'];

        $dtbuku = DTBuku::where('noTransaksi',$noTransaksi)
        ->where('idBuku',$idBuku)
        ->first();

        if($dtbuku==NULL){
            $status=404;
            $response = [
                'status' => 'Error',
                'DTBuku' => [],
                'message' => 'Detail Transaksi buku Tidak Ditemukan'
            ];
        }
        else
        {
            $dtbuku->jumlah = $request['jumlah'];
            
            
            
            try{
                $success = $dtbuku->save();
                $status = 200;
                $response = [
                    'status' => 'Success',
                    'DTBuku' => [$dtbuku]
                ];  
            }
            catch(\Illuminate\Database\QueryException $e){
                $status = 500;
                $response = [
                    'status' => 'Error',
                    'DTBuku' => [],
                    'message' => $e
                ];
            }
        }
        return response()->json($response,$status); 
    }

    public function hapus(Request $request)
    {
        $noTransaksi = $request['noTransaksi'];
        $idBuku = $request['idBuku'];

        $dtbuku = DTBuku::where('noTransaksi',$noTransaksi)
        ->where('idBuku',$idBuku)->first();
        
        if($dtbuku==NULL){
            $status=404;
            $response = [
                'status' => 'Error',
                'DTBuku' => [],
                'message' => 'Detail Transaksi buku Tidak Ditemukan'
            ];
        }
        else{
            try{
                $delete = DTBuku::where('noTransaksi',$noTransaksi)
                ->where('idBuku',$idBuku);
                
                $success = $delete->delete();
                $status = 200;
                $response = [
                    'status' => 'Success',
                    'DTBuku' => [$dtbuku]
                ];  
            }
            catch(\Illuminate\Database\QueryException $e){
                $status = 500;
                $response = [
                    'status' => 'Error',
                    'DTBuku' => [],
                    'message' => $e
                ];
            }
        }
        return response()->json($response,$status); 
    }
}