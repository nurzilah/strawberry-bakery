@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ $title }}</h1>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-md-5">
            <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($method === 'PUT')
                    @method('PUT')
                @endif

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name',$product->name) }}" required>
                        @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id',$product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug',$product->slug) }}" placeholder="Otomatis dari nama jika kosong">
                        @error('slug')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-dark">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" value="{{ old('price',$product->price) }}" min="0" required>
                        @error('price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-dark">Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control" value="{{ old('stock',$product->stock) }}" min="0" required>
                        @error('stock')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold text-dark">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="description" rows="4" class="form-control" required>{{ old('description',$product->description) }}</textarea>
                        @error('description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold text-dark">Foto Produk {!! $method === 'POST' ? '<span class="text-danger">*</span>' : '' !!}</label>
                        <input type="file" name="image" accept="image/*,.heic,.heif" class="form-control" {{ $method === 'POST' ? 'required' : '' }}>
                        <div class="form-text text-muted">Format: JPG, PNG, WEBP, HEIC/HEIF. Maksimal 5MB.</div>
                        @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                        <!-- Fitur AI Enhance -->
                        <div class="mt-3 p-3 rounded-3" style="background-color: #fff5f5; border: 1px solid #ffe0e8;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="ai_enhance" value="1" id="ai_enhance" {{ old('ai_enhance') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-dark" for="ai_enhance">
                                    <i class="fas fa-magic text-warning me-1"></i> Tingkatkan foto dengan AI
                                </label>
                            </div>
                            <div class="form-text mt-1 text-muted">AI akan membuat foto produk lebih rapi, terang, dan menarik untuk katalog.</div>
                            
                            <textarea name="ai_prompt" rows="2" class="form-control form-control-sm mt-2" placeholder="Opsional prompt tambahan. Cth: latar putih premium, nuansa strawberry, lighting natural">{{ old('ai_prompt') }}</textarea>
                        </div>

                        @if($product->image)
                            <div class="mt-3">
                                <p class="small text-muted mb-1">Preview Foto Saat Ini:</p>
                                <img src="{{ asset('storage/'.$product->image) }}" alt="Preview" class="rounded object-fit-cover shadow-sm border" style="width: 100px; height: 100px;">
                            </div>
                        @endif
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold text-dark mb-3">Status Produk</label>
                        <div class="d-flex flex-wrap gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_available" value="1" id="is_available" {{ old('is_available',$product->is_available ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_available">Tersedia</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured',$product->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Produk Unggulan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_new" value="1" id="is_new" {{ old('is_new',$product->is_new) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_new">Produk Baru</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_promo" value="1" id="is_promo" {{ old('is_promo',$product->is_promo) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_promo">Promo</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 pt-3 border-top mt-4">
                        <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-semibold">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-link text-decoration-none text-muted ms-2">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
