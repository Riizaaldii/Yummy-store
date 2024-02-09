<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    function booking(Request $request){
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'nohp' => 'required',
            'tanggal' => 'required',
            'waktu' => 'required',
            'jumlah' => 'required',
            'pesan' => 'required'
        ]);

        $booking = new Booking;
        $booking->nama = $request->nama;
        $booking->email = $request->email;
        $booking->nohp = $request->nohp;
        $booking->tanggal = $request->tanggal;
        $booking->waktu = $request->waktu;
        $booking->jumlah = $request->jumlah;
        $booking->pesan = $request->pesan;
        $booking->save();
        
        
        return redirect('/')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    function booking_dashboard() {
        $data =  Booking::all();

        return view('admin.booking', compact('data'));
    }

    function booking_proses(Request $request, $id) {
        $datas =  Booking::findOrFail($id);

        $datas->konfirmasi = 1;
        $datas->save();
        
        return redirect('/booking_dashboard');
    }
}
