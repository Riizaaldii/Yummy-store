<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    function message_proc(Request $request){
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'subjek' => 'required',
            'pesan' => 'required',
        ]);

        $pesan = new Message;
        $pesan->nama = $request->nama;
        $pesan->email = $request->email;
        $pesan->subjek = $request->subjek;
        $pesan->pesan = $request->pesan;
        $pesan->save();
        
        
        return redirect('/')->with(['success_m' => 'Data Berhasil Disimpan!']);
    }

    function message() {
        $data =  Message::all();

        return view('admin.message', compact('data'));
    }
}
