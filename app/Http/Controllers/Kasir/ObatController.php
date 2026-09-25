<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ObatController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $obats = Obat::query()
            ->when($request->stok === 'habis', fn($q) => $q->outOfStock())
            ->when($search, fn($q) => $q->where(function ($sub) use ($search) {
                $sub->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode_barcode', 'like', "%{$search}%");
            }))
            ->paginate(10)
            ->withQueryString();

        return view('kasir.obat.index', compact('obats', 'search'));
    }

    public function search(Request $request): JsonResponse
    {
        $q = $request->string('q')->toString();

        $obats = Obat::where('nama', 'like', "%{$q}%")
            ->orWhere('kode_barcode', 'like', "%{$q}%")
            ->get(['id', 'kode_barcode', 'nama', 'harga_jual as harga', 'stok']);

        return response()->json($obats);
    }
}
