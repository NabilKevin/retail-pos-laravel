@extends('admin.layouts.app')

@section('title', 'Edit Obat - Toko Obat')
@section('page-title', 'Edit Obat')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-background via-secondary to-muted">
        <div class="container mx-auto px-4 py-8 max-w-4xl">
            {{-- Enhanced header with better visual hierarchy --}}
            <div class="mb-8">
                <div class="flex items-center space-x-4 mb-6">
                    <a href="{{ route('admin.obat') }}"
                        class="group inline-flex items-center justify-center w-12 h-12 rounded-2xl shadow-soft hover:shadow-medium text-muted-foreground hover:text-primary transition-all duration-300 hover:scale-105 active:scale-95">
                        <i data-lucide="arrow-left"
                            class="h-5 w-5 transition-transform duration-200 group-hover:-translate-x-0.5"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl lg:text-4xl font-bold text-foreground mb-2">Edit Obat</h1>
                        <p class="text-muted-foreground text-lg">Edit obat {{ $obat->nama }}</p>
                    </div>
                </div>
            </div>

            {{-- Enhanced form card with sophisticated design --}}
            <div class="rounded-3xl shadow-large border border-border overflow-hidden">
                {{-- Form header --}}
                <div class="bg-gradient-to-r from-primary/5 to-accent/5 px-8 py-6 border-b border-border">
                    <div class="flex items-center space-x-3">
                        <div>
                            <h2 class="text-xl font-semibold text-foreground">Informasi Obat</h2>
                            <p class="text-sm text-muted-foreground">Lengkapi data untuk membuat obat baru</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <form action="{{ route('admin.obat.update', $obat->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        {{-- Enhanced personal information section --}}
                        <div class="space-y-6">
                            <div class="flex items-center space-x-3 pb-4 border-b border-border">
                                <h3 class="text-lg font-semibold text-foreground">Informasi Obat</h3>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="lg:col-span-2">
                                    <div class="relative group">
                                        <input type="text" id="kode_barcode" name="kode_barcode" required
                                            value="{{ old('kode_barcode', $obat->kode_barcode) }}"
                                            class="{{ $errors->has('kode_barcode') ? 'border-red-500' : 'border-gray-500' }} peer w-full px-4 pt-6 pb-2 bg-muted border-2 border-border rounded-2xl text-foreground placeholder-transparent focus:outline-none focus:ring-0 focus:border-primary transition-all duration-300 text-base min-h-[64px] hover:border-primary/50 hover:shadow-lg hover:shadow-primary/5 focus:shadow-xl focus:shadow-primary/10"
                                            placeholder="Masukkan Kode Barcode">
                                        <label for="kode_barcode"
                                            class="absolute left-4 top-2 text-xs font-medium text-muted-foreground transition-all duration-200 peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:top-2 peer-focus:text-xs peer-focus:text-primary">
                                            Kode Barcode
                                        </label>
                                        @error('kode_barcode')
                                            <p class="text-red-500 text-xs font-medium italic mt-2 mb-0">{{ $message }}</p>
                                        @enderror
                                        <div
                                            class="absolute inset-0 rounded-2xl bg-gradient-to-r from-primary/5 to-accent/5 opacity-0 peer-focus:opacity-100 transition-opacity duration-300 pointer-events-none">
                                        </div>
                                    </div>
                                </div>
                                <div class="lg:col-span-2">
                                    <div class="relative group">
                                        <input type="text" id="nama" name="nama" required
                                            value="{{ old('nama', $obat->nama) }}"
                                            class="{{ $errors->has('nama') ? 'border-red-500' : 'border-gray-500' }} peer w-full px-4 pt-6 pb-2 bg-muted border-2 border-border rounded-2xl text-foreground placeholder-transparent focus:outline-none focus:ring-0 focus:border-primary transition-all duration-300 text-base min-h-[64px] hover:border-primary/50 hover:shadow-lg hover:shadow-primary/5 focus:shadow-xl focus:shadow-primary/10"
                                            placeholder="Masukkan nama">
                                        <label for="nama"
                                            class="absolute left-4 top-2 text-xs font-medium text-muted-foreground transition-all duration-200 peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:top-2 peer-focus:text-xs peer-focus:text-primary">
                                            Nama
                                        </label>
                                        @error('nama')
                                            <p class="text-red-500 text-xs font-medium italic mt-2 mb-0">{{ $message }}</p>
                                        @enderror
                                        <div
                                            class="absolute inset-0 rounded-2xl bg-gradient-to-r from-primary/5 to-accent/5 opacity-0 peer-focus:opacity-100 transition-opacity duration-300 pointer-events-none">
                                        </div>
                                    </div>
                                </div>

                                <div class="lg:col-span-2">
                                    <div class="relative group">
                                        <input type="number" id="stok" name="stok" required
                                            value="{{ old('stok', $obat->stok) }}"
                                            class="{{ $errors->has('stok') ? 'border-red-500' : 'border-gray-500' }} appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none [moz-appearance:textfield] peer w-full px-4 pt-6 pb-2 bg-muted border-2 border-border rounded-2xl text-foreground placeholder-transparent focus:outline-none focus:ring-0 focus:border-primary transition-all duration-300 text-base min-h-[64px] hover:border-primary/50 hover:shadow-lg hover:shadow-primary/5 focus:shadow-xl focus:shadow-primary/10"
                                            placeholder="stok">
                                        <label for="stok"
                                            class="absolute left-4 top-2 text-xs font-medium text-muted-foreground transition-all duration-200 peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:top-2 peer-focus:text-xs peer-focus:text-primary">
                                            Stok
                                        </label>
                                        @error('stok')
                                            <p class="text-red-500 text-xs font-medium italic mt-2 mb-0">{{ $message }}
                                            </p>
                                        @enderror
                                        <div
                                            class="absolute inset-0 rounded-2xl bg-gradient-to-r from-primary/5 to-accent/5 opacity-0 peer-focus:opacity-100 transition-opacity duration-300 pointer-events-none">
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-6 lg:col-span-2	">

                                    <div class="grid grid-cols-1 gap-6">
                                        <div>
                                            {{-- Enhanced select field with floating label effect --}}
                                            <div class="relative group">
                                                <select id="tipe_id" name="tipe_id" required
                                                    class="{{ $errors->has('tipe_id') ? 'border-red-500' : 'border-gray-500' }} peer w-full px-4 pt-6 pb-2 bg-muted border-2 border-border rounded-2xl text-foreground focus:outline-none focus:ring-0 focus:border-primary transition-all duration-300 text-base min-h-[64px] hover:border-primary/50 hover:shadow-lg hover:shadow-primary/5 focus:shadow-xl focus:shadow-primary/10 appearance-none cursor-pointer">
                                                    <option value="" class="text-muted-foreground">Pilih Tipe Obat
                                                    </option>
                                                    @foreach ($tipeobat as $tipe)
                                                        <option value="{{ $tipe->id }}" class="text-foreground"
                                                            {{ old('tipe_id', $obat->tipe->nama) == $tipe->nama ? 'selected' : '' }}>
                                                            {{ $tipe->nama }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="tipe_id"
                                                    class="absolute left-4 top-2 text-xs font-medium text-muted-foreground transition-all duration-200 peer-focus:text-primary">
                                                    Tipe Obat
                                                </label>
                                                <div
                                                    class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                                    <i data-lucide="chevron-down"
                                                        class="h-5 w-5 text-muted-foreground transition-transform duration-200 peer-focus:rotate-180"></i>
                                                </div>
                                                @error('tipe_id')
                                                    <p class="text-red-500 text-xs font-medium italic mt-2 mb-0">
                                                        {{ $message }}</p>
                                                @enderror
                                                <div
                                                    class="absolute inset-0 rounded-2xl bg-gradient-to-r from-primary/5 to-accent/5 opacity-0 peer-focus:opacity-100 transition-opacity duration-300 pointer-events-none">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="lg:col-span-1">
                                    <div class="relative group">
                                        <input type="text" id="harga_modal" placeholder="Masukkan harga modal"
                                            value="{{ old('harga_modal', $obat->harga_modal) }}"
                                            class="appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none [moz-appearance:textfield] peer w-full px-4 pt-6 pb-2 pr-16 bg-muted border-2 border-border rounded-2xl text-foreground placeholder-transparent focus:outline-none focus:ring-0 focus:border-primary transition-all duration-300 text-base min-h-[64px]">

                                        <!-- Hidden input yg dikirim ke server -->
                                        <input type="hidden" id="harga_modal_hidden" name="harga_modal"
                                            value="{{ old('harga_modal', $obat->harga_modal) }}">

                                        <label for="harga_modal"
                                            class="absolute left-4 top-2 text-xs font-medium text-muted-foreground transition-all">
                                            Harga Modal
                                        </label>

                                        @error('harga_modal')
                                            <p class="text-red-500 text-xs font-medium italic mt-2 mb-0">{{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>


                                <div class="lg:col-span-1">
                                    <div class="relative group">
                                        <input type="text" id="harga_jual" placeholder="Masukkan harga jual"
                                            value="{{ old('harga_jual', $obat->harga_jual) }}"
                                            class="appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none [moz-appearance:textfield] peer w-full px-4 pt-6 pb-2 pr-16 bg-muted border-2 border-border rounded-2xl text-foreground placeholder-transparent focus:outline-none focus:ring-0 focus:border-primary transition-all duration-300 text-base min-h-[64px]">

                                        <!-- Hidden input yg dikirim ke server -->
                                        <input type="hidden" id="harga_jual_hidden" name="harga_jual"
                                            value="{{ old('harga_jual', $obat->harga_jual) }}">

                                        <label for="harga_jual"
                                            class="absolute left-4 top-2 text-xs font-medium text-muted-foreground transition-all">
                                            Harga Jual
                                        </label>

                                        @error('harga_jual')
                                            <p class="text-red-500 text-xs font-medium italic mt-2 mb-0">{{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>


                                <div class="lg:col-span-2">
                                    <div class="relative group">
                                        <input type="date" id="expired_at" name="expired_at" required
                                            value="{{ old('expired_at', optional($obat->expired_at)->format('Y-m-d')) }}"
                                            class="{{ $errors->has('expired_at') ? 'border-red-500' : 'border-gray-500' }} peer w-full px-4 pt-6 pb-2 bg-muted border-2 border-border rounded-2xl text-foreground placeholder-transparent focus:outline-none focus:ring-0 focus:border-primary transition-all duration-300 text-base min-h-[64px] hover:border-primary/50 hover:shadow-lg hover:shadow-primary/5 focus:shadow-xl focus:shadow-primary/10"
                                            placeholder="expired_at">
                                        <label for="expired_at"
                                            class="absolute left-4 top-2 text-xs font-medium text-muted-foreground transition-all duration-200 peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:top-2 peer-focus:text-xs peer-focus:text-primary">
                                            Expired
                                        </label>
                                        @error('expired_at')
                                            <p class="text-red-500 text-xs font-medium italic mt-2 mb-0">{{ $message }}
                                            </p>
                                        @enderror
                                        <div
                                            class="absolute inset-0 rounded-2xl bg-gradient-to-r from-primary/5 to-accent/5 opacity-0 peer-focus:opacity-100 transition-opacity duration-300 pointer-events-none">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Enhanced action buttons --}}
                        <div
                            class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 pt-8 border-t border-border">
                            <button type="submit"
                                class="group flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-4 rounded-2xl shadow-medium hover:shadow-large ring-1 ring-primary/20 transition-all duration-300 flex items-center justify-center font-semibold text-base transform hover:scale-[1.02] hover:-translate-y-0.5 min-h-[56px] active:scale-[0.98]">
                                <span class="relative z-10">Edit Obat</span>
                            </button>

                            <a href="{{ route('admin.obat') }}"
                                class="group flex-1 bg-muted hover:bg-muted/80 border-2 border-border text-foreground px-8 py-4 rounded-2xl transition-all duration-300 flex items-center justify-center font-semibold text-base transform hover:scale-[1.02] hover:-translate-y-0.5 min-h-[56px] active:scale-[0.98] shadow-soft hover:shadow-medium">
                                <span>Batal</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for password toggle functionality --}}
    <script src="{{ asset('js/admin/obat/form.js') }}"></script>
@endsection
