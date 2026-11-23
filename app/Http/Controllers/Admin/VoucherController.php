<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(10);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'             => 'required|string|max:50|unique:vouchers,code',
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'type'             => 'required|in:percentage,fixed,free_shipping',
            'discount_value'   => 'required|numeric|min:0',
            'max_discount'     => 'nullable|integer|min:0',
            'min_subtotal'     => 'nullable|integer|min:0',
            'max_distance_km'  => 'nullable|numeric|min:0',
            'quota'            => 'nullable|integer|min:1',
            'first_order_only' => 'nullable|boolean',
            'is_active'        => 'nullable|boolean',
        ], [
            'code.unique' => 'Kode voucher sudah digunakan. Silakan gunakan kode lain.',
        ]);

        $voucher = Voucher::create([
            'code'             => strtoupper(trim($request->code)),
            'name'             => $request->name,
            'description'      => $request->description,
            'type'             => $request->type,
            'discount_value'   => $request->discount_value,
            'max_discount'     => $request->filled('max_discount') ? $request->max_discount : null,
            'min_subtotal'     => $request->filled('min_subtotal') ? $request->min_subtotal : 0,
            'max_distance_km'  => $request->filled('max_distance_km') ? $request->max_distance_km : null,
            'quota'            => $request->filled('quota') ? $request->quota : null,
            'first_order_only' => $request->boolean('first_order_only'),
            'is_active'        => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.vouchers.index')->with('success', "Voucher {$voucher->code} berhasil ditambahkan.");
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code'             => 'required|string|max:50|unique:vouchers,code,' . $voucher->id,
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'type'             => 'required|in:percentage,fixed,free_shipping',
            'discount_value'   => 'required|numeric|min:0',
            'max_discount'     => 'nullable|integer|min:0',
            'min_subtotal'     => 'nullable|integer|min:0',
            'max_distance_km'  => 'nullable|numeric|min:0',
            'quota'            => 'nullable|integer|min:1',
            'first_order_only' => 'nullable|boolean',
            'is_active'        => 'nullable|boolean',
        ], [
            'code.unique' => 'Kode voucher sudah digunakan oleh voucher lain.',
        ]);

        $voucher->update([
            'code'             => strtoupper(trim($request->code)),
            'name'             => $request->name,
            'description'      => $request->description,
            'type'             => $request->type,
            'discount_value'   => $request->discount_value,
            'max_discount'     => $request->filled('max_discount') ? $request->max_discount : null,
            'min_subtotal'     => $request->filled('min_subtotal') ? $request->min_subtotal : 0,
            'max_distance_km'  => $request->filled('max_distance_km') ? $request->max_distance_km : null,
            'quota'            => $request->filled('quota') ? $request->quota : null,
            'first_order_only' => $request->boolean('first_order_only'),
            'is_active'        => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.vouchers.index')->with('success', "Voucher {$voucher->code} berhasil diperbarui.");
    }

    public function toggle(Voucher $voucher)
    {
        $voucher->update(['is_active' => !$voucher->is_active]);
        $statusText = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.vouchers.index')->with('success', "Status voucher {$voucher->code} berhasil {$statusText}.");
    }

    public function destroy(Voucher $voucher)
    {
        $code = $voucher->code;
        $voucher->delete();

        return redirect()->route('admin.vouchers.index')->with('success', "Voucher {$code} berhasil dihapus.");
    }
}

