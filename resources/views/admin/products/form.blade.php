@extends('layouts.admin')

@section('title', $title)

@section('content')
@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<style>
    .img-container {
        max-height: 400px;
        width: 100%;
        text-align: center;
    }
    .img-container img {
        max-width: 100%;
    }
</style>
@endpush

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
                        <input type="number" name="stock" class="form-control" value="{{ old('stock',$product->stock) }}" required {{ $method === 'PUT' ? 'readonly' : '' }}>
                        @if($method === 'PUT')
                            <small class="form-text text-muted">Stok hanya dapat diubah melalui menu <a href="{{ route('admin.stock.create', ['product_id' => $product->id]) }}">Riwayat Penambahan Stok</a></small>
                        @endif
                        @error('stock')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold text-dark">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="description" rows="4" class="form-control" required>{{ old('description',$product->description) }}</textarea>
                        @error('description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold text-dark">Foto Produk {!! $method === 'POST' ? '<span class="text-danger">*</span>' : '' !!}</label>
                        <input type="file" id="imageInput" name="image" accept="image/*,.heic,.heif" class="form-control" {{ $method === 'POST' ? 'required' : '' }}>
                        <input type="hidden" name="cropped_image" id="croppedImage">
                        <div class="form-text text-muted">Format: JPG, PNG, WEBP, HEIC/HEIF. Maksimal 5MB.</div>
                        @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        @error('cropped_image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                        <div id="croppedPreviewContainer" class="mt-3 d-none">
                            <p class="small text-success mb-1 fw-bold"><i class="fas fa-check-circle me-1"></i>Foto berhasil di-crop:</p>
                            <img id="croppedPreview" src="" alt="Preview Crop" class="rounded object-fit-cover shadow-sm border" style="width: 150px; height: 150px;">
                            <button type="button" class="btn btn-sm btn-outline-danger d-block mt-2" onclick="resetCrop()">Batal Crop & Reset</button>
                        </div>

                        <!-- Fitur AI Enhance -->
                        <div class="mt-3 p-3 rounded-3" style="background-color: #fff5f5; border: 1px solid #ffe0e8;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="ai_enhance" value="1" id="ai_enhance" {{ old('ai_enhance') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-dark" for="ai_enhance">
                                    <i class="fas fa-magic text-warning me-1"></i> Tingkatkan foto jadi High Definition (HD) dengan AI
                                </label>
                            </div>
                            <div class="form-text mt-1 text-muted">AI akan memproses dan melakukan upscaling (meningkatkan resolusi) pada foto produk agar terlihat jauh lebih tajam dan berkualitas tinggi secara otomatis.</div>
                        </div>

                        @if($product->image)
                            <div class="mt-3">
                                <p class="small text-muted mb-1">Preview Foto Saat Ini:</p>
                                <img src="{{ $product->image_default }}" alt="Preview" class="rounded object-fit-cover shadow-sm border" style="width: 100px; height: 100px;" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'">
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

<!-- Modal untuk Cropper -->
<div class="modal fade" id="cropModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropModalLabel">Crop Foto Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetCrop()"></button>
            </div>
            <div class="modal-body p-0">
                <div class="img-container p-3 bg-light">
                    <img id="imageToCrop" src="" alt="Picture">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="resetCrop()">Batal</button>
                <button type="button" class="btn btn-primary" id="btnCropSave"><i class="fas fa-crop-alt me-2"></i>Simpan Crop</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    let cropper = null;
    const imageInput = document.getElementById('imageInput');
    const imageToCrop = document.getElementById('imageToCrop');
    const croppedImageInput = document.getElementById('croppedImage');
    const croppedPreviewContainer = document.getElementById('croppedPreviewContainer');
    const croppedPreview = document.getElementById('croppedPreview');
    const cropModalEl = document.getElementById('cropModal');
    const cropModal = new bootstrap.Modal(cropModalEl);

    imageInput.addEventListener('change', function(e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const file = files[0];
            
            // Tampilkan modal hanya jika file adalah gambar statis umum (jpg, png, webp)
            if (file.type.match(/^image\/(jpeg|png|webp|gif)$/)) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imageToCrop.src = event.target.result;
                    cropModal.show();
                };
                reader.readAsDataURL(file);
            } else {
                // Untuk HEIC atau file tidak dikenali, lewati crop
                resetCrop(false);
            }
        }
    });

    cropModalEl.addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1, // 1:1 ratio direkomendasikan untuk produk (bisa diubah)
            viewMode: 2,
            autoCropArea: 1,
            responsive: true,
        });
    });

    cropModalEl.addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });

    document.getElementById('btnCropSave').addEventListener('click', function() {
        if (!cropper) return;
        
        const canvas = cropper.getCroppedCanvas({
            width: 800,
            height: 800,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        // Convert ke base64 string
        const base64Image = canvas.toDataURL('image/jpeg', 0.9);
        
        // Simpan ke hidden input
        croppedImageInput.value = base64Image;
        
        // Tampilkan preview
        croppedPreview.src = base64Image;
        croppedPreviewContainer.classList.remove('d-none');
        
        // Hapus require attribute dari original input jika method POST
        imageInput.removeAttribute('required');
        
        cropModal.hide();
    });

    window.resetCrop = function(clearInput = true) {
        if (clearInput) {
            imageInput.value = '';
        }
        croppedImageInput.value = '';
        croppedPreview.src = '';
        croppedPreviewContainer.classList.add('d-none');
        
        @if($method === 'POST')
        imageInput.setAttribute('required', 'required');
        @endif
    }
</script>
@endpush
@endsection
