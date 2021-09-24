<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HargaJasa;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\HargaJasaImport;

class HargaJasaController extends Controller
{
    //get all data, will be used in HargaJasaPage
    public function get()
    {
        $data = HargaJasa::paginate(5)->toArray();

        return response()->json([
            "success" => true,
            "message" => "Data Harga Jasa",
            "data" => $data,
        ]);
    }

    //get nama_pekerjaan, will be used in dropdown NamaPekerjaan on TambahPekerjaanPage
    public function getNama()
    {
        $dataNama = HargaJasa::pluck("nama_pekerjaan")->toArray();

        return response()->json([
            "success" => true,
            "message" => "Data Nama Pekerjaan",
            "data" => $dataNama,
        ]);
    }

    //get harga_pekerjaan, will be used to autogenerated form after user choose the nama_pekerjaan
    public function getHarga($kode_pekerjaan)
    {
        $harga = HargaJasa::where("kode_pekerjaan", $kode_pekerjaan)->pluck(
            "biaya_pekerjaan"
        );

        return response()->json([
            "success" => true,
            "message" => "Data Harga Pekerjaan by Name",
            "data" => $harga,
        ]);
    }

    //store the data to database
    public function add(Request $request)
    {
        $hargaJasa = new HargaJasa([
            "kode_pekerjaan" => $request->kode_pekerjaan,
            "nama_pekerjaan" => $request->nama_pekerjaan,
            "deskripsi_pekerjaan" => $request->deskripsi_pekerjaan,
            "biaya_pekerjaan" => $request->biaya_pekerjaan,
            "estimasi_waktu_pengerjaan" => $request->estimasi_waktu_pengerjaan,
        ]);
        $hargaJasa->save();

        return response()->json("Harga Berhasil Ditambahkan");
    }

    //to generate the value of harga_jasa by id
    public function show($id)
    {
        $harga = HargaJasa::find($id);

        return response()->json([
            "success" => true,
            "message" => "Detail Data",
            "data" => $harga,
        ]);
    }

    //to update new data to database
    public function update($id, Request $request)
    {
        $harga = HargaJasa::find($id);
        $harga->update($request->all());

        return response()->json("Data Berhasil Diupdate");
    }

    //to delete data
    public function delete($id)
    {
        $harga = HargaJasa::find($id);
        $harga->delete();

        return response()->json("Data Berhasil Dihapus");
    }

    //import excel
    public function import(Request $request)
    {
        Excel::import(
            // prettier-ignore
            new HargaJasaImport,
            $request->file("file")
        );
        return response()->json("Data Berhasil diimport");
    }

    //search
    public function search(Request $request)
    {
        $data = HargaJasa::where(
            "nama_pekerjaan",
            "LIKE",
            "%" . $request->keyword . "%"
        )->get();
        return response()->json($data);
    }
}
