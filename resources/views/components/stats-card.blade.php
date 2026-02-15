@props(['icon', 'count', 'label', 'color' => 'primary'])
<div class="card border-start border-{{ $color }} border-4 shadow-sm h-100 transition-hover">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-0 text-dark">{{ $count }}</h3>
                <small class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">{{ $label }}</small>
            </div>
            <div class="fs-1 text-{{ $color }} opacity-25">
                <i class="fas fa-{{ $icon }}"></i>
            </div>
        </div>
    </div>
</div>
