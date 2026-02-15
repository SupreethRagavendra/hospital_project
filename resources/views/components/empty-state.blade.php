@props(['message' => 'No result found', 'icon' => 'inbox'])
<div class="text-center py-5">
    <div class="mb-3 text-secondary opacity-25">
        <i class="fas fa-{{ $icon }} fa-4x"></i>
    </div>
    <h5 class="text-muted fw-normal">{{ $message }}</h5>
</div>