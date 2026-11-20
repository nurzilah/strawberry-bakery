<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoRule;
use Illuminate\Http\Request;

class PromoRuleController extends Controller
{
    public function index()
    {
        $promos = PromoRule::latest()->paginate(10);
        return view('admin.promos.index', compact('promos'));
    }

    public function create()
    {
        return view('admin.promos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'scope' => 'required|in:order,package',
            'min_qty' => 'required|integer|min:1',
            'free_qty' => 'nullable|integer|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'free_ongkir_max_km' => 'nullable|numeric|min:0',
            'bonus_item_name' => 'nullable|string|max:255|required_with:bonus_item_qty',
            'bonus_item_qty' => 'nullable|integer|min:1|required_with:bonus_item_name',
            'is_active' => 'boolean',
        ]);

        PromoRule::create([
            'name' => $request->name,
            'scope' => $request->scope,
            'min_qty' => $request->min_qty,
            'free_qty' => $request->free_qty,
            'discount_percent' => $request->discount_percent,
            'free_ongkir_max_km' => $request->free_ongkir_max_km,
            'bonus_item_name' => $request->filled('bonus_item_name') ? $request->bonus_item_name : null,
            'bonus_item_qty' => $request->filled('bonus_item_name') ? $request->bonus_item_qty : null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil ditambahkan');
    }

    public function edit(PromoRule $promo)
    {
        return view('admin.promos.edit', compact('promo'));
    }

    public function update(Request $request, PromoRule $promo)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'scope' => 'required|in:order,package',
            'min_qty' => 'required|integer|min:1',
            'free_qty' => 'nullable|integer|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'free_ongkir_max_km' => 'nullable|numeric|min:0',
            'bonus_item_name' => 'nullable|string|max:255|required_with:bonus_item_qty',
            'bonus_item_qty' => 'nullable|integer|min:1|required_with:bonus_item_name',
            'is_active' => 'boolean',
        ]);

        $promo->update([
            'name' => $request->name,
            'scope' => $request->scope,
            'min_qty' => $request->min_qty,
            'free_qty' => $request->free_qty,
            'discount_percent' => $request->discount_percent,
            'free_ongkir_max_km' => $request->free_ongkir_max_km,
            'bonus_item_name' => $request->filled('bonus_item_name') ? $request->bonus_item_name : null,
            'bonus_item_qty' => $request->filled('bonus_item_name') ? $request->bonus_item_qty : null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil diperbarui');
    }

    public function destroy(PromoRule $promo)
    {
        $promo->delete();
        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil dihapus');
    }
}
