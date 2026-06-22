@extends('layouts.app')

@section('title', 'Owner Workspace')

@push('styles')
<style>
    /* Secondary Horizontal Navigation for Owner */
    .owner-nav-wrapper {
        background-color: #ffffff;
        border-bottom: 1px solid var(--sb-border);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
        margin-bottom: 2rem;
        border-radius: 1rem;
        padding: 0.5rem 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .owner-nav-link {
        color: #475569;
        font-weight: 600;
        padding: 0.75rem 1.25rem;
        border-radius: 0.75rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .owner-nav-link:hover {
        background-color: var(--sb-primary-soft);
        color: var(--sb-primary);
    }
    
    .owner-nav-link.active {
        background-color: var(--sb-primary);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(233, 79, 112, 0.2);
    }
    
    .owner-workspace-container {
        min-height: 60vh;
    }
    
    .owner-avatar-badge {
        width: 42px;
        height: 42px;
        background-color: var(--sb-primary-soft);
        color: var(--sb-primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
    }
</style>
@endpush

@section('content')
<div class="container owner-workspace-container">
    
    <!-- Owner Role Identification -->
    <div class="d-flex align-items-center mb-4 gap-3">
        <div class="owner-avatar-badge">
            {{ strtoupper(substr(auth()->user()->name ?? 'O', 0, 1)) }}
        </div>
        <div>
            <h4 class="mb-0 fw-bold text-dark">Owner Workspace</h4>
            <span class="badge bg-dark text-white rounded-pill px-3 py-1 mt-1">Administrator Access</span>
        </div>
    </div>

    <!-- Secondary Horizontal Navbar -->
    <nav class="owner-nav-wrapper">
        <a href="{{ route('owner.dashboard') }}" class="owner-nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Dashboard Overview
        </a>
        <a href="#" class="owner-nav-link">
            <i class="fas fa-file-invoice-dollar"></i> View Laporan Omzet
        </a>
        <a href="#" class="owner-nav-link">
            <i class="fas fa-list"></i> View Log Pesanan / Transaksi
        </a>
    </nav>

    <!-- Global Alerts for Owner Section -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Render Dashboard Content inside this layout -->
    @yield('owner_content')

</div>
@endsection
