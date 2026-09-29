<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use App\Rules\MatchOldPassword;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('pages.user.setting.index', [
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required',
            'nim' => 'required',
            'gender' => 'required',
        ]);

        $item = User::findOrFail($id);
        
        $item->update($validatedData);

        return redirect()
                ->route('setting-user.index')
                ->with('success', 'Sukses! Data telah diperbarui');
    }

    public function upload_profile(Request $request)
{
    $validatedData = $request->validate([
        'profile' => 'required|image|mimes:jpg,jpeg,png|max:5024',
    ]);

    $id = $request->id;
    $item = User::findOrFail($id);

    if ($request->hasFile('profile')) {
        // Hapus foto lama jika ada
        if ($item->profile != null && file_exists(public_path('gambarprofile/' . $item->profile))) {
            unlink(public_path('gambarprofile/' . $item->profile));
        }

        // Simpan file ke public/gambarprofile
        $file = $request->file('profile');
        $namaFile = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('gambarprofile'), $namaFile);

        // Simpan hanya nama file (bukan path lengkap) ke database
        $item->profile = $namaFile;
    }

    $item->save();

    return redirect()
            ->route('setting-user.index')
            ->with('success', 'Sukses! Photo Pengguna telah diperbarui');
}

    public function destroy_profile(Request $request, $id)
    {
        $id = $request->id;
        $item = User::findOrFail($id);

        if ($item->profile != NULL) {
            Storage::delete($item->profile);

            $item->profile = NULL;
        }

        $item->save();

        return redirect()
                ->route('setting-user.index')
                ->with('success', 'Sukses! Photo Profile telah dihapus');
    }

    public function change_password()
    {
        return view('pages.user.setting.change-password');
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['min:5','max:255'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);

        return redirect()
            ->route('change-password-user')
            ->with('success', 'Sukses! Password telah diperbarui');
    }
}