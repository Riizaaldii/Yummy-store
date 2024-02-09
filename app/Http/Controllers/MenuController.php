<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index(): View
    {
        $data =  Menu::all();

        return view('admin.menu', compact('data'));
    }

    public function create(): View
    {
        return view('admin.tambah_menu');
    }

    function store(Request $request){
        
        $request->validate([
            'nama' => 'required',
            'jenis' => 'required',
            'harga' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp'
        ]);

        $fileName = time() . '.' . $request->gambar->extension();
        $request->gambar->storeAs('public/images', $fileName);

        $menu = new Menu;
        $menu->nama = $request->nama;
        $menu->gambar = $fileName;
        $menu->jenis = $request->jenis;
        $menu->harga = $request->harga;
        $menu->save();

        $data =  Menu::all();

        return view('admin.menu', compact('data'));
    }

    public function edit(string $id): View
    {
        $data = Menu::findOrFail($id);

        return view('admin.edit_menu', compact('data'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
            'jenis' => 'required',
            'harga' => 'required',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg,webp'
        ]);

        $menu = Menu::findOrFail($id);

        if ($request->hasFile('gambar')) {

            $fileName = time() . '.' . $request->gambar->extension();
            $request->gambar->storeAs('public/images', $fileName);

            Storage::delete('public/images/'.$menu->image);

            $menu->update([
                'nama' => $request->nama,
                'jenis' => $request->jenis,
                'harga' => $request->harga,
                'gambar' => $fileName
            ]);

        } else {

            $menu->update([
                'nama' => $request->nama,
                'jenis' => $request->jenis,
                'harga' => $request->harga,
            ]);
        }

        $data =  Menu::all();

        return redirect()->route('menu.index');
    }

    public function destroy($id): RedirectResponse
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('menu.index');
    }
    
}
