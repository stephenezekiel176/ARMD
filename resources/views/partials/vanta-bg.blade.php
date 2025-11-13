{{-- resources/views/partials/vanta-bg.blade.php --}}
<div id="vanta-bg" class="fixed inset-0 -z-10"></div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanta/0.5.24/vanta.waves.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    VANTA.WAVES({
        el: "#vanta-bg",
        mouseControls: true,
        touchControls: true,
        gyroControls: false,
        minHeight: 200,
        minWidth: 200,
        scale: 1.0,
        scaleMobile: 1.0,
        color: 0x8b5cf6,
        shininess: 80,
        waveHeight: 25,
        waveSpeed: 0.75,
        zoom: 0.85
    });
});
</script>
@endpush
