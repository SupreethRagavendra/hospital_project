@props(['status'])
@php
    $colors = [
      'active' => 'success',
      'inactive' => 'secondary',
      'pending' => 'warning',
      'completed' => 'primary',
      'cancelled' => 'danger',
      'reviewed' => 'info',
      'follow_up' => 'info'
    ];
    $color = $colors[strtolower($status)] ?? 'secondary';
    $icon = match(strtolower($status)) {
        'active', 'completed', 'reviewed' => 'check-circle',
        'pending', 'follow_up' => 'clock',
        'cancelled', 'inactive' => 'times-circle',
        default => 'circle'
    };
@endphp
<span class="badge bg-{{ $color }} px-2 py-1 align-middle">
    <i class="fas fa-{{ $icon }} me-1 optional-icon"></i>{{ ucfirst(str_replace('_',' ',$status)) }}
</span>