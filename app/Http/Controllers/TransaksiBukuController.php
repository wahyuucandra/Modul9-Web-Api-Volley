<?php

namespace App\Http\Controllers;

use App\TransaksiBuku;
use App\DTBuku;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class TransaksiBukuController extends Controller
{
    public function index()
    {
        $TransaksiBuku = TransaksiBuku::leftjoin('toko','transaksibuku.idToko','=','toko.idToko')
        ->select('transaksibuku.*', 'toko.*')->get();
        
        $temp = [];
        
        if(isset($TransaksiBuku))
        {
            foreach($TransaksiBuku as $tb)
            {
                $dtbuku = DTBuku::join('buku', 'dtbuku.idBuku', '=', 'buku.idBuku')
                ->select('dtbuku.*', 'buku.namaBuku', 'buku.pengarang', 'buku.harga',
                'buku.gambar')
                ->where('dtbuku.noTransaksi',$tb->noTransaksi)
                ->get();
                
                if(count($dtbuku)>0)
                {
                    $tb->dtbuku = $dtbuku;
                    $temp[]=$tb;
                }
                    
            }
             $response = [
                'status'        => 'Success',
                'transaksibuku' => $temp,
                'message'       => 'Data transaksi tertampil'
            ];
        }
        else
        {
            $response = [
                'status'        => 'Success',
                'transaksibuku' => $temp,
                'message'       => 'Data transaksi belum ada'
            ];
        }
        
       
        return response()->json($response,200);
    }

    public function tambah(Request $request)
    {
        $TransaksiBuku = new TransaksiBuku;
        $TransaksiBuku->noTransaksi = $this->generateNoTransaksi();
        $TransaksiBuku->tglTransaksi = date('Y-m-d');
        $TransaksiBuku->idToko = $request['idToko'];
        $TransaksiBuku->created_at = Carbon::now();
        
        try{
                $success = $TransaksiBuku->save();
                $status = 200;
                $response = [
                    'status' => 'Success',
                    'transaksibuku' => $TransaksiBuku,
                ];   
            }
            catch(\Illuminate\Database\QueryException $e){
                $status = 500;
                $response = [
                    'status' => 'Error',
                    'transaksibuku' => [],
                    'message' => $e
                ];
            }

        return response()->json($response,$status);
    }

    public function edit(Request $request, $id)
    {
        $TransaksiBuku = TransaksiBuku::find($id);

        if($TransaksiBuku==NULL){
            $status=404;
            $response = [
                'status' => 'Error',
                'transaksibuku' => [],
                'message' => 'Transaksi Buku Tidak Ditemukan'
            ];
        }
        else{
            $TransaksiBuku->npm = $request['npm'];
            $TransaksiBuku->totalBiaya = $request['totalBiaya'];
            
            try{
                $success = $TransaksiBuku->save();
                $status = 200;
                $response = [
                    'status' => 'Success',
                    'transaksibuku' =>$TransaksiBuku,
                ];  
            }
            catch(\Illuminate\Database\QueryException $e){
                $status = 500;
                $response = [
                    'status' => 'Error',
                    'transaksibuku' => [],
                    'message' => $e
                ];
            }
        }

        return response()->json($response,$status); 
    }
    
    public function hapus($id)
    {
        $TransaksiBuku = TransaksiBuku::find($id);

        if($TransaksiBuku==NULL ){
            $status=404;
            $response = [
                'status' => 'Error',
                'transaksibuku' => [],
                'message' => 'Transaksi Buku Tidak Ditemukan'
            ];
        }
        else
        {
            $dtbuku = DTBuku::join('buku', 'dtbuku.idBuku', '=', 'buku.idBuku')
            ->select('dtbuku.*')
            ->where('dtbuku.noTransaksi',$id)
            ->get();
            
            if($dtbuku != NULL)
            {
                foreach($dtbuku as $d)
                {
                    $noTransaksi = $d->noTransaksi;;
                    $idBuku      = $d->idBuku;
            
                    $dtbuku = DTBuku::where('noTransaksi',$noTransaksi)
                    ->where('idBuku',$idBuku)->first();
                    
                    try{
                    $delete = DTBuku::where('noTransaksi',$noTransaksi)
                    ->where('idBuku',$idBuku);
                    
                    $success = $delete->delete();
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
            }
            
            $TransaksiBuku->delete();
            $status=200;
            $response = [
                'status' => 'Success',
                'transaksibuku' => $TransaksiBuku
            ];
        }
        return response()->json($response,$status); 
    }

    public function generateNoTransaksi()
    {
        $TransaksiBuku = TransaksiBuku::whereDate('created_at', date('Y-m-d'))
        ->orderBy('created_at', 'desc')->first();
        
        if (isset($TransaksiBuku)) 
        {
            $noTerakhir=substr($TransaksiBuku->noTransaksi,10);
            if($noTerakhir<9)
                return 'TR-' . date('dmy') . '-0' . ($noTerakhir + 1);
            else
                return 'TR-' . date('dmy') . '-' . ($noTerakhir + 1);
        } 
        else 
        {
            return 'TR-' . date('dmy') . '-01';
        }
    }
    
    public function cekNPM($npm)
    {
        $TransaksiBuku = TransaksiBuku::where('npm', $npm)->first();
        if(isset($TransaksiBuku))
            return true;
        else
            return false;
    }
}