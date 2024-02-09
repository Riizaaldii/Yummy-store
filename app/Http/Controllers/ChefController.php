<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

use App\Models\Chef;

class ChefController extends Controller
{
    public function index(): View
    {
        $data =  Chef::all();

        return view('admin.chef', compact('data'));
    }

    public function create(): View
    {
        return view('admin.tambah_chef');
    }

    function store(Request $request){
        
        $request->validate([
            'nama' => 'required',
            'posisi' => 'required',
            'quote' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp'
        ]);

        $fileName = time() . '.' . $request->gambar->extension();
        $request->gambar->storeAs('public/images', $fileName);

        $chef = new Chef;
        $chef->nama = $request->nama;
        $chef->gambar = $fileName;
        $chef->posisi = $request->posisi;
        $chef->quote = $request->quote;
        $chef->save();

        $data =  Chef::all();

        return view('admin.chef', compact('data'));
    }

    public function edit(string $id): View
    {
        $data = Chef::findOrFail($id);

        return view('admin.edit_chef', compact('data'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
            'posisi' => 'required',
            'quote' => 'required',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg,webp'
        ]);

        $chef = Chef::findOrFail($id);

        if ($request->hasFile('gambar')) {

            $fileName = time() . '.' . $request->gambar->extension();
            $request->gambar->storeAs('public/images', $fileName);

            Storage::delete('public/images/'.$chef->image);

            $chef->update([
                'nama' => $request->nama,
                'posisi' => $request->posisi,
                'quote' => $request->quote,
                'gambar' => $fileName
            ]);

        } else {

            $chef->update([
                'nama' => $request->nama,
                'posisi' => $request->posisi,
                'quote' => $request->quote,
            ]);
        }

        $data =  Chef::all();

        return redirect()->route('chef.index');
    }

    public function destroy($id): RedirectResponse
    {
        $chef = Chef::findOrFail($id);
        $chef->delete();

        return redirect()->route('chef.index');
    }
}
