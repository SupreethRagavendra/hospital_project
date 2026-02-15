@props(['icon', 'count', 'label', 'color' => 'primary'])
<div class="card border-0 shadow-sm transition-hover overflow-hidden"
     style="border-radius: 20px; background: #fff;">
    <div class="card-body p-4 position-relative" style="z-index: 2;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-1 text-dark" style="letter-spacing: -1px; font-size: 1.75rem;">{{ $count }}</h3>
                <div class="text-secondary small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px; opacity: 0.8;">
                    {{ $label }}
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-center rounded-4 shadow-sm"
                 style="width: 52px; height: 52px; background: rgba(var(--{{ $color }}-rgb), 0.1);">
                 <i class="fas fa-{{ $icon }} text-{{ $color }} fs-4"></i>
            </div>
        </div>
    </div>

    <div class="position-absolute" style="bottom: -20px; right: -20px; width: 100px; height: 100px; background: rgba(var(--{{ $color }}-rgb), 0.05); border-radius: 50%; z-index: 1;"></div>
</div>