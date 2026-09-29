<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Application;
use App\Rules\MatchOldPassword;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;  // <- pastikan import di sini

class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('pages.admin.setting.index', [
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required',
        ]);

        $item = User::findOrFail($id);

        $item->update($validatedData);

        return redirect()
                ->route('setting.index')
                ->with('success', 'Sukses! Data telah diperbarui');
    }

    public function upload_profile(Request $request)
    {
        $validatedData = $request->validate([
            'profile' => 'required|image|mimes:jpg,jpeg,png|max:5024',
        ]);

        $id = $request->id;
        $item = User::findOrFail($id);

        if ($request->file('profile')) {
            // Hapus file lama jika ada
            if ($item->profile != NULL && file_exists(public_path('gambarprofile/' . $item->profile))) {
                unlink(public_path('gambarprofile/' . $item->profile));
            }

            // Simpan file baru ke public/gambarprofile
            $file = $request->file('profile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('gambarprofile'), $filename);

            $item->profile = $filename;
        }

        $item->save();

        return redirect()
                ->route('setting.index')
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
                ->route('setting.index')
                ->with('success', 'Sukses! Photo Profile telah dihapus');
    }

    public function change_password()
    {
        return view('pages.admin.user.change-password');
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
                ->route('change-password')
                ->with('success', 'Sukses! Password telah diperbarui');
    }

    public function change_application()
    {
        $app = Application::findOrFail(1);

        return view('pages.admin.application.index', [
            'app' => $app
        ]);
    }

    public function update_application(Request $request, $id)
    {
        $validatedData = $request->validate([
            'app_name' => 'required|max:255',
            'copyright' => 'required',
            'is_active' => 'required',
            'is_photo' => 'required',
        ]);

        $item = Application::findOrFail($id);

        $item->update($validatedData);

        return redirect()
                ->route('change-application')
                ->with('success', 'Sukses! Data Aplikasi telah diperbarui');
    }

    // UPLOAD LOGO LOGIN
    public function upload_logo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ]);

        $file = $request->file('logo');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('logo'), $filename);

        $app = Application::find($request->id);
        $app->logo = $filename;
        $app->save();

        return back()->with('success', 'Logo berhasil diperbarui.');
    }

    // DELETE LOGO LOGIN
    public function destroy_logo($id)
    {
        $app = Application::find($id);

        if ($app && $app->logo && file_exists(public_path('logo/' . $app->logo))) {
            unlink(public_path('logo/' . $app->logo));
        }

        $app->logo = null;
        $app->save();

        return back()->with('success', 'Logo berhasil dihapus.');
    }

    // UPLOAD FAVICON
    public function upload_favicon(Request $request)
    {
        $validatedData = $request->validate([
            'favicon' => 'required|image|file|max:1024',
            'id' => 'required|exists:applications,id',
        ]);

        $item = Application::findOrFail($request->id);

        if ($request->hasFile('favicon')) {
            // Hapus file favicon lama jika ada
            if ($item->favicon && File::exists(public_path('favicon/' . $item->favicon))) {
                File::delete(public_path('favicon/' . $item->favicon));
            }

            // Simpan file baru ke public/favicon dengan nama unik
            $file = $request->file('favicon');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('favicon'), $filename);

            // Update nama file favicon di database
            $item->favicon = $filename;
        }

        $item->save();

        return redirect()
            ->route('change-application')
            ->with('success', 'Sukses! Favicon telah diperbarui');
    }

    // HAPUS FAVICON
    public function destroy_favicon(Request $request, $id)
    {
        $item = Application::findOrFail($id);

        if ($item->favicon && File::exists(public_path('favicon/' . $item->favicon))) {
            File::delete(public_path('favicon/' . $item->favicon));
        }

        $item->favicon = null;
        $item->save();

        return redirect()
            ->route('change-application')
            ->with('success', 'Sukses! Favicon telah dihapus');
    }

    // UPLOAD WALLPAPER LOGIN
    public function upload_wallpaper(Request $request)
    {
        $validatedData = $request->validate([
            'wallpaper' => 'required|image|mimes:jpg,jpeg,png,webp|max:5024',
            'id' => 'required|exists:applications,id',
        ]);

        $item = Application::findOrFail($request->id);

        if ($request->hasFile('wallpaper')) {
            // Hapus wallpaper lama jika ada
            if ($item->wallpaper && File::exists(public_path('wallpapers/' . $item->wallpaper))) {
                File::delete(public_path('wallpapers/' . $item->wallpaper));
            }

            // Simpan wallpaper baru
            $file = $request->file('wallpaper');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('wallpapers'), $filename);

            $item->wallpaper = $filename;
        }

        $item->save();

        return redirect()
            ->route('change-application')
            ->with('success', 'Sukses! Wallpaper telah diperbarui');
    }

    // HAPUS BACKGROUND LOGIN
    public function destroy_wallpaper($id)
    {
        $item = Application::findOrFail($id);

        if ($item->wallpaper && File::exists(public_path('wallpapers/' . $item->wallpaper))) {
            File::delete(public_path('wallpapers/' . $item->wallpaper));
        }

        $item->wallpaper = null;
        $item->save();

        return redirect()
            ->route('change-application')
            ->with('success', 'Sukses! Wallpaper login berhasil dihapus');
    }
}
