<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstansiRequest;

use Illuminate\Http\Request;

use App\Models\Instansi;

class InstansiController extends Controller
{
    public function index()
    {
        $instansi = Instansi::first();

        return view('pages.admin.instansi.index',[
            'instansi' => $instansi
        ]);
    }

    public function update(InstansiRequest $request, $id)
    {
        $validatedData = $request->all();

        $model = Instansi::findOrFail($id);
        $model->update($validatedData);

        return redirect()
                    ->route('instansi.index')
                    ->with('success', 'Sukses! Data Instansi Berhasil Diubah');
    }
}
