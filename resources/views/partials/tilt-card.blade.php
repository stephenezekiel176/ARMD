{{-- resources/views/partials/tilt-card.blade.php --}}
<div class="max-w-sm mx-auto my-12">
    <div class="tilt-card bg-white rounded-xl shadow-xl overflow-hidden">
        <img src="https://picsum.photos/400/300" alt="demo">
        <div class="p-6">
            <h3 class="text-xl font-semibold">Hover me!</h3>
            <p class="text-gray-600">Tilt.js + vanilla JS</p>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanilla-tilt@1.8.1/dist/vanilla-tilt.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/vanilla-tilt@1.8.1/dist/vanilla-tilt.min.js"></script>
<script>
VanillaTilt.init(document.querySelectorAll(".tilt-card"), {
    max: 15,
    speed: 400,
    glare: true,
    "max-glare": 0.3,
});
</script>
@endpush
