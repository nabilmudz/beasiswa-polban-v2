<?php

namespace App\Http\Controllers;

use App\Models\PengajuanDokumen;
use Illuminate\Http\Request;

class PengajuanDokumenController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

         // Validate the input data
        $validatedData = $request->validate([
            'pengajuan_beasiswa_id' => 'required|integer',
            'nama_dokumen' => 'required|string',
            'link_dokumen' => 'required|string',
        ]);

        // Insert data into the database
        PengajuanDokumen::create([
            'pengajuan_beasiswa_id' => $validatedData['pengajuan_beasiswa_id'],
            'nama_dokumen' => $validatedData['nama_dokumen'],
            'link_dokumen' => $validatedData['link_dokumen'],
        ]);

        return response()->json(['message' => 'succes'], 200);
    }

}
