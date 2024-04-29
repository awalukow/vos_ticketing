<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\Category;
use App\Models\Pemesanan;
use App\Models\Transportasi;
use App\Models\Pemesanan_Detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ruteAwal = Rute::orderBy('start')->get()->groupBy('start');
        if (count($ruteAwal) > 0) {
            foreach ($ruteAwal as $key => $value) {
                $data['start'][] = $key;
            }
        } else {
            $data['start'] = [];
        }
        $ruteAkhir = Rute::orderBy('end')->get()->groupBy('end');
        if (count($ruteAkhir) > 0) {
            foreach ($ruteAkhir as $key => $value) {
                $data['end'][] = $key;
            }
        } else {
            $data['end'] = [];
        }
        $category = Category::orderBy('name')->get();
        return view('client.index', compact('data', 'category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->category) {
            $category = Category::find($request->category);
            $data = [
                'start' => $request->start,
                'end' => $request->end,
                'category' => $category->id,
                'waktu' => $request->waktu,
            ];
            $data = Crypt::encrypt($data);
            return redirect()->route('show', ['id' => $category->slug, 'data' => $data]);
        } else {
            $this->validate($request, [
                'rute_id' => 'required',
                'waktu' => 'required',
            ]);

            $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
            $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

            $rute = Rute::with('transportasi.category')->get();
            // $jumlah_kursi = $rute->transportasi->jumlah + 2;
            // $kursi = (int) floor($jumlah_kursi / 5);
            // $kode = "ABCDE";
            // $kodeKursi = strtoupper(substr(str_shuffle($kode), 0, 1) . rand(1, $kursi));

            $waktu = $request->waktu . " " . $rute->jam;

            Pemesanan::Create([
                'kode' => $kodePemesanan,
                // 'kursi' => $request,
                'waktu' => $waktu,
                'total' => $rute->harga,
                'rute_id' => $rute->id,
                'penumpang_id' => Auth::user()->id
            ]);

            return redirect()->back()->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function _show($id, $data)
    {
        $data = Crypt::decrypt($data);
        $category = Category::find($data['category']);
        
        $rute = Rute::with('transportasi')->get();
        if ($rute->count() > 0) {
            foreach ($rute as $val) {
                $pemesanan = Pemesanan::where('rute_id', $val->id)->count();
                if ($val->transportasi) {
                    $kursi = Transportasi::find($val->transportasi_id)->jumlah - $pemesanan;
                    if ($val->transportasi->category_id == $category->id) {
                        $dataRute[] = [
                            'harga' => $val->harga,
                            'start' => $val->start,
                            'end' => $val->end,
                            'tujuan' => $val->tujuan,
                            'transportasi' => $val->transportasi->name,
                            'kode' => $val->transportasi->kode,
                            'kursi' => $kursi, // Ensure $kursi is included in the data
                            'waktu' => date("h:i A", strtotime($val->jam)),
                            'id' => $val->id,
                            'kategori' => $category->name
                        ];
                    }
                }
            }
            sort($dataRute);
        } else {
            $dataRute = [];
        }
        
        $id = $category->name;
        return view('client.show', compact('id', 'dataRute', 'kursi')); // Pass $kursi to the view
    }

     
    public function show($id, $data)
{
    // Decrypt the data
    $data = Crypt::decrypt($data);

    // Find the category based on the decrypted data
    $category = Category::find($data['category']);
    
    // Fetch relevant route data based on the category
    $rute = Rute::with('transportasi')->get();

    // Initialize an empty array to store route data
    $dataRute = [];

    // Iterate over route data and filter based on category
    foreach ($rute as $val) {
        $pemesanan = Pemesanan::where('rute_id', $val->id)->count();
        if ($val->transportasi && $val->transportasi->category_id == $category->id) {
            $kursi = Transportasi::find($val->transportasi_id)->jumlah - $pemesanan;
            $dataRute[] = [
                'harga' => $val->harga,
                'start' => $val->start,
                'end' => $val->end,
                'tujuan' => $val->tujuan,
                'transportasi' => $val->transportasi->name,
                'kode' => $val->transportasi->kode,
                'kursi' => $kursi,
                'waktu' => date("h:i A", strtotime($val->jam)),
                'id' => $val->id,
                'kategori' => $category->name
            ];
        }
    }
    
    // Sort the data if needed
    sort($dataRute);
    
    // Pass the necessary variables to the view
    return view('client.show', compact('id', 'dataRute', 'data'));
}
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Crypt::decrypt($id);
        $rute = Rute::find($data['id']);
        $transportasi = Transportasi::find($rute->transportasi_id);
        //$pesananDetail = Pemesanan_Detail::where('pemesananCode', 'LIKE', '%' . $data['kode'] . '%')->get();
        $dataString = json_encode($data);
        return view('client.kursi', compact('data', 'transportasi', 'dataString'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function __pesan($kursi, $data)
    {
        $d = Crypt::decrypt($data);
        $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

        $rute = Rute::with('transportasi.category')->find($d['id']);

        $waktu = Carbon::parse($d['waktu'])->format('Y-m-d') . ' ' . $rute->jam;

        Pemesanan::Create([
            'kode' => $kodePemesanan,
            'kursi' => $kursi,
            'waktu' => $waktu,
            'total' => $rute->harga,
            'rute_id' => $rute->id,
            'penumpang_id' => Auth::user()->id
        ]);

        //return redirect('/')->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
        return redirect('/transaksi/'.$kodePemesanan)->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
    }

    public function pesan_BACKUP270424($kursi, $data)
    {
        $d = Crypt::decrypt($data);
        $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

        $rute = Rute::with('transportasi.category')->find($d['id']);

        $waktu = Carbon::parse($d['waktu'])->format('Y-m-d') . ' ' . $rute->jam;

        Pemesanan::Create([
            'kode' => $kodePemesanan,
            'kursi' => $kursi,
            'waktu' => $waktu,
            'total' => $rute->harga,
            'rute_id' => $rute->id,
            'penumpang_id' => Auth::user()->id
        ]);

        //return redirect('/')->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
        return redirect('/transaksi/'.$kodePemesanan)->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
    }

    public function pesan_NOUSE($data)
    {
        $d = Crypt::decrypt($data);
        $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

        $rute = Rute::with('transportasi.category')->find($d['id']);

        // Selecting a default seat (e.g., the first available seat)
        $firstAvailableSeat = $rute->pemesanans()->count() + 1; // Assuming seats are numbered sequentially

        $waktu = Carbon::parse($d['waktu'])->format('Y-m-d') . ' ' . $rute->jam;

        Pemesanan::create([
            'kode' => $kodePemesanan,
            'kursi' => 'K' . $firstAvailableSeat, // Setting default seat
            'waktu' => $waktu,
            'total' => $rute->harga,
            'rute_id' => $rute->id,
            'penumpang_id' => Auth::user()->id
        ]);

        return redirect('/transaksi/'.$kodePemesanan)->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
    }

    public function pesan($kursi, $data)
{
    //$d = Crypt::decrypt($data);
    $dataKursi = json_decode($kursi, true); 
    $dataArray = json_decode($data, true); 
    //Log::info($kursi); // Log the contents of $dataArray
    //Log::info($data); // Log the contents of $dataArray
    $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    
    // Update this line to use $dataArray instead of $data
    $rute = Rute::with('transportasi.category')->find($dataArray['id']);
    // Update this line as well
    $waktu = Carbon::parse($dataArray['waktu'])->format('Y-m-d') . ' ' . $rute->jam;

    $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));
    //$checkSeat = Pemesanan::with('kode')->find($kodePemesanan);
    $checkSeat = Pemesanan::with('kode')->where('kode', 'LIKE', $kodePemesanan)->get();
    
    Log::info($checkSeat); // Log the contents of $dataArray
    // Check if $checkSeat is not null
    if ($checkSeat != null || $checkSeat != "") {
        $count = 0;
        foreach ($dataKursi as $a){
            $count++;
        }
        $harga = $rute->harga * $count;
        Pemesanan::create([
            'kode' => $kodePemesanan,
            //'kursi' => $k,
            'waktu' => $waktu,
            //'total' => $rute->harga,
            'total' => $harga,
            'rute_id' => $rute->id,
            'penumpang_id' => Auth::user()->id
        ]);

        foreach ($dataKursi as $k) {
            Pemesanan_Detail::create([
                'pemesananCode' => $kodePemesanan,
                'seatNumber' => $k
            ]);
        }
        // Assuming you want to redirect after processing all seats
        return redirect('/')->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
    } else {
        // Handle if $checkSeat is null, maybe redirect or show an error message
        // For now, let's just log a message
        Log::info('Pemesanan dengan kode ' . $kodePemesanan . ' sudah ada.');
        return redirect('/')->with('error', 'Pemesanan dengan kode ' . $kodePemesanan . ' sudah ada.');
    }
}


}
