<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Menampilkan 
    public function index()
    {
        $data = Product::all();
        return response()->json([
            'status'    => true,
            'pesan'     => 'Berhasil Mendapatkan Data Product',
            'data'      => $data
        ], 200);
    }

    public function show($id)
    {
        try {
            $data = Product::find($id);

            if(!$data) {
                return response()->json([
                    'status'    => false,
                    'pesan'     => 'Maaf Data Product Tidak Ada.'
                ]);
            }

            return response()->json([
                'status'    => true,
                'pesan'     => 'Berhasil Mendapatkan Data Product',
                'data'      => $data
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'status'    => false,
                'pesan'     => $th->getMessage()
            ]);
        }

        
    }

    public function store(Request $request)
   {    
        // Dhamar
        // $request->validate([
        //     'name'      => 'required',
        //     'desc'      => 'required',
        //     'price'     => 'required',
        //     'qty'       => 'required'
        // ]);

        // $data = new Product;
        // $data->name     = $request->name;
        // $data->desc     = $request->desc;
        // $data->price    = $request->price;
        // $data->qty      = $request->qty;
        // $data->save();

        // return response()->json([
        //     'status' => true,
        //     'pesan'=> 'Berhasil Tambah Data'], 200);

        // Coba dulu perintahnya
        try {
            // Validasi data produk
            $validatedData = Validator::make($request->all(),
            [
                'name'      => 'required',
                'desc'      => 'required',
                'price'     => 'required',
                'qty'       => 'required'
            ],[
                'name.required'     => 'Nama Wajib Diisi',
                'desc.required'     => 'Deskripsi Wajib Diisi',
                'price.required'    => 'Harap Tentukan Harga',
                'qty.requried'      => 'Harap Isi Jumlah Produk'
            ]);

            // Jika validasi gagal
            if($validatedData->fails()){
            return response()->json([
                'status'    => false,
                'pesan'     => 'Validasi Gagal',
                'errors'    => $validatedData->errors()
            ], 200);   
            }

            // Jika validasi Benar
            $data = Product::create([
                'name'     => $request->name,
                'desc'     => $request->desc,
                'price'    => $request->price,
                'qty'      => $request->qty,
            ]);

            //Jika Berhasil
            return response()->json([
                'status'    => true,
                'pesan'     => 'Berhasil Tambah Data',
                'data'      => $data
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status'    => false,
                'pesan'     => $th->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validasi data produk
            $validatedData = Validator::make($request->all(),
            [
                'name'      => 'required',
                'desc'      => 'required',
                'price'     => 'required',
                'qty'       => 'required'
            ],[
                'name.required'     => 'Nama Wajib Diisi',
                'desc.required'     => 'Deskripsi Wajib Diisi',
                'price.required'    => 'Harap Tentukan Harga',
                'qty.requried'      => 'Harap Isi Jumlah Produk'
            ]);

            // Jika validasi gagal
            if($validatedData->fails()){
            return response()->json([
                'status'    => false,
                'pesan'     => 'Validasi Gagal',
                'errors'    => $validatedData->errors()
            ], 200);   
            }

            // Dhamar use Find
            $data = Product::find($id)
            ->update([
                'name'     => $request->name,
                'desc'     => $request->desc,
                'price'    => $request->price,
                'qty'      => $request->qty,
            ]);

            // Jika validasi Benar
            // $product->update([
            //     'name' => $request->name,
            //     'desc' => $request->desc,
            //     'price' => $request->price,
            //     'qty' => $request->qty,
            // ]);

            //Jika Berhasil
            return response()->json([
                'status' => true,
                'pesan'=> 'Berhasil Update Data',
                'data' => $data
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'pesan' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $data = Product::find($id);
            if($data) {
                $data->delete();
                return response()->json([
                    'status'    => true,
                    'pesan'     => 'Data Berhasil Dihapus',
                ]);
            } else {
                return response()->json([
                    'status'    => false,
                    'pesan'     => 'ID Tidak Ditemukan',
                ]);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status'    => false,
                'pesan'     => $th->getMessage()
            ]);
        }

        // $data = Product::find($id);
        // if ($data) {
        //     $data->delete();
        //     return response()->json([
        //         'status' => true,
        //         'pesan'=> 'Berhasil Delete Data',
        //     ], 200);
        // } else {
        //     return response()->json([
        //         'status' => false,
        //         'pesan'=> 'ID Tidak Ditemukan',
        //     ], 200);
        // }
        
    }

}
