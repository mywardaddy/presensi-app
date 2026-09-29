<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(){
        if (request()->ajax()) {
            $query = User::with(['role'])->latest();
            
            return Datatables::of($query)
                ->addColumn('action', function ($item) {
                    return '
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle btn-sm" id="dropdownFadeInUp" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user-cog"></i></button>
                            <div class="dropdown-menu animated--fade-in-up" aria-labelledby="dropdownFadeInUp">
                                <a class="dropdown-item" href="' . route('user.edit', $item->id) . '">Edit</a>
                                <form action="' . route('user.destroy', $item->id) . '" method="POST" id="deleteForm'.$item->id.'">
                                    ' . method_field('delete') . csrf_field() . '
                                    <button class="dropdown-item btn-delete" data-form-id="deleteForm'.$item->id.'">
                                    Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    ';
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make();
        }

        return view('pages.admin.user.index');
    }

    public function create(){
        return view('pages.admin.user.create');
    }

    public function store(UserRequest $request)
    {
        $validatedData = $request->all();

        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);

        return redirect()
                    ->route('user.index')
                    ->with('success', 'Sukses! 1 Data User berhasil disimpan.');
    }

    public function edit($id)
    {
        $item = User::findOrFail($id);

        return view('pages.admin.user.edit',[
            'item' => $item,
        ]);
    }

    public function update(UserRequest $request, $id)
    {
        $validatedData = $request->all();

        $item = User::findOrFail($id);

        if ($request->password != null) {
            $validatedData['password'] = Hash::make($request->password);
        }else{
            unset($validatedData['password']);
        }
        
        $item->update($validatedData);

        return redirect()
                    ->route('user.index')
                    ->with('success', 'Sukses! 1 Data User berhasil disimpan.');
    }

    public function destroy($id)
    {
        $item = User::findorFail($id);

        !is_null($item->profile) && Storage::delete($item->profile);

        $item->delete();

        return redirect()
                ->route('user.index')
                ->with('success', 'Sukses! Data Pengguna telah dihapus');
    }
}
