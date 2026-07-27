@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ $title }}</h1>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-md-5">
            <form action="{{ $route }}" method="POST">
                @csrf
                @if($method !== 'POST')
                    @method($method)
                @endif

                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control" required>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="form-control" placeholder="Kosongkan agar otomatis dibuat dari nama">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">Deskripsi</label>
                    <textarea name="description" rows="4" class="form-control">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">Icon <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" class="form-control" placeholder="Contoh: fas fa-cake">
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-medium text-dark" for="is_active">
                        Aktif
                    </label>
                </div>

                <div class="pt-2 border-top mt-4 pt-4">
                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-semibold">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-link text-decoration-none text-muted ms-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
