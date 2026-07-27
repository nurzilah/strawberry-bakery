@extends('layouts.admin')

@section('title', 'Visualisasi Rekomendasi (KNN)')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800"><i class="fas fa-project-diagram text-primary me-2"></i> Visualisasi Rekomendasi (KNN)</h1>
    </div>

    <div class="card shadow mb-4 border-0">
        <div class="card-body">
            <p class="text-muted">Halaman ini menunjukkan bagaimana algoritma K-Nearest Neighbors dan Collaborative Filtering bekerja dalam menemukan produk yang direkomendasikan untuk user berdasarkan kemiripan histori transaksi.</p>
            
            <div class="accordion" id="accordionRecommendations">
                @forelse($visualizations as $index => $vis)
                    <div class="accordion-item mb-3 border rounded">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }} fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                <i class="fas fa-user-circle fa-lg me-2 text-primary"></i> Target User: {{ $vis['target_user']->name }} 
                                <span class="badge bg-secondary ms-2">{{ count($vis['purchased_products']) }} Produk Dibeli</span>
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionRecommendations">
                            <div class="accordion-body bg-light">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100 border-info">
                                            <div class="card-header bg-info text-white fw-bold">
                                                <i class="fas fa-shopping-basket me-1"></i> Histori Pembelian
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                @forelse($vis['purchased_products'] as $prodName)
                                                    <li class="list-group-item"><i class="fas fa-check text-success me-2"></i>{{ $prodName }}</li>
                                                @empty
                                                    <li class="list-group-item text-muted">Belum ada pembelian.</li>
                                                @endforelse
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100 border-warning">
                                            <div class="card-header bg-warning text-dark fw-bold">
                                                <i class="fas fa-users me-1"></i> Tetangga Terdekat (KNN)
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                @forelse($vis['neighbors'] as $neighbor)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span><i class="fas fa-user text-muted me-2"></i>{{ $neighbor['user']->name }}</span>
                                                        <span class="badge bg-warning text-dark rounded-pill" title="Cosine Similarity Score">
                                                            {{ number_format($neighbor['similarity'], 3) }}
                                                        </span>
                                                    </li>
                                                @empty
                                                    <li class="list-group-item text-muted">Tidak ada user yang mirip (Cold Start).</li>
                                                @endforelse
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100 border-success">
                                            <div class="card-header bg-success text-white fw-bold">
                                                <i class="fas fa-star me-1"></i> Hasil Rekomendasi
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                @forelse($vis['recommendations'] as $rec)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span>
                                                            @if($rec['product']->image)
                                                                <img src="{{ asset('storage/' . $rec['product']->image) }}" alt="{{ $rec['product']->name }}" class="rounded me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                                            @else
                                                                <div class="bg-secondary rounded d-inline-block me-2" style="width: 30px; height: 30px;"></div>
                                                            @endif
                                                            {{ $rec['product']->name }}
                                                        </span>
                                                        <span class="badge bg-success rounded-pill" title="Prediction Score">
                                                            {{ number_format($rec['score'], 2) }}
                                                        </span>
                                                    </li>
                                                @empty
                                                    <li class="list-group-item text-muted">Tidak ada rekomendasi dari KNN (Fallback ke produk terlaris).</li>
                                                @endforelse
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">Belum ada data transaksi yang mencukupi untuk membuat visualisasi rekomendasi.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
