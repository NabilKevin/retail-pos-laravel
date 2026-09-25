<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Obat\StoreRequest;
use App\Http\Requests\Admin\Obat\UpdateRequest;
use App\Models\Obat;
use App\Models\TipeObat;
use App\Services\ExportService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ObatController extends Controller
{
    public function __construct(private readonly ExportService $exportService) {}

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

        return view('admin.obat.index', compact('obats', 'search'));
    }

    public function create(): View
    {
        $tipeobat = TipeObat::all();

        return view('admin.obat.create', compact('tipeobat'));
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        Obat::create($request->validated());

        return redirect()->route('admin.obat')->with('success', 'Obat berhasil ditambahkan!');
    }

    public function edit(int $id): View|RedirectResponse
    {
        $obat = Obat::with('tipe')->find($id);

        if (! $obat) {
            return redirect()->back()->with('error', 'Obat tidak ditemukan!');
        }

        $obat->expired_at = Carbon::parse($obat->expired_at)->format('Y-m-d');
        $tipeobat         = TipeObat::all();

        return view('admin.obat.edit', compact('obat', 'tipeobat'));
    }

    public function update(UpdateRequest $request, int $id): RedirectResponse
    {
        $obat = Obat::find($id);

        if (! $obat) {
            return redirect()->back()->with('error', 'Obat tidak ditemukan!');
        }

        // Unique validation is handled in UpdateRequest (scoped to current id),
        // so no manual duplicate check needed here.
        $obat->update($request->validated());

        return redirect()->route('admin.obat')->with('success', 'Obat berhasil diedit!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $obat = Obat::find($id);

        if (! $obat) {
            return redirect()->back()->with('error', 'Obat tidak ditemukan!');
        }

        $obat->delete();

        return redirect()->back()->with('success', 'Berhasil hapus obat!');
    }

    public function expired(): View
    {
        $obats = Obat::whereNotNull('expired_at')
            ->whereDate('expired_at', '<=', Carbon::today()->addDays(7))
            ->orderBy('expired_at', 'asc')
            ->get();

        return view('admin.obat.expired', compact('obats'));
    }

    public function exportExpired()
    {
        $obats = Obat::expired()->get();

        return $this->exportService->exportExpiredObat($obats);
    }
}
