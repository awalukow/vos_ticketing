<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->level !== 'SuperAdmin') {
                return redirect()->route('home')->with('error', 'Anda tidak memiliki akses.');
            }
            return $next($request);
        })->except(['show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promotions = Promotion::get();
        return view('server.promotions.index', compact('promotions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('server.promotions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:12|unique:promotions,code',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'max_uses' => 'required|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'nullable|in:1,true,on', // Accept "on", "1", "true"
            'penumpang_id' => 'nullable|exists:users,id',
            'rute_id' => 'nullable|exists:rute,id',
        ]);

        Promotion::create([
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'max_uses' => $request->max_uses,
            'used_count' => 0,
            'expires_at' => $request->expires_at,
            'is_active' => $request->has('is_active') ? 1 : 0, // Force boolean
            'penumpang_id' => $request->penumpang_id,
            'rute_id' => $request->rute_id,
            'rowstatus' => 0,
        ]);

        return redirect()->route('promotions.index')->with('success', 'Promo berhasil dibuat.');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promotion $promotion)
    {
        return view('server.promotions.edit', compact('promotion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'code' => 'required|string|max:12|unique:promotions,code,' . $promotion->id,
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'max_uses' => 'required|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'nullable|in:1,true,on',
            'penumpang_id' => 'nullable|exists:users,id',
            'rute_id' => 'nullable|exists:rute,id',
        ]);

        $promotion->update([
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'max_uses' => $request->max_uses,
            'expires_at' => $request->expires_at,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'penumpang_id' => $request->penumpang_id,
            'rute_id' => $request->rute_id,
        ]);

        return redirect()->route('promotions.index')->with('success', 'Promo berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('promotions.index')->with('success', 'Promo berhasil dihapus.');
    }
}