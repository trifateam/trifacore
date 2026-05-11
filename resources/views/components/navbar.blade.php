<div class="top-navbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-sm btn-outline-secondary sidebar-toggle" id="sidebarToggle">
            <i>☰</i>
        </button>
        <span class="fw-semibold text-muted" style="font-size: 0.875rem;">
            {{ $title ?? 'Dashboard' }}
        </span>
    </div>
    <div class="d-flex align-items-center gap-3">
        <span class="text-muted" style="font-size: 0.813rem;">👤 Admin</span>
        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-danger">Logout</a>
    </div>
</div>
