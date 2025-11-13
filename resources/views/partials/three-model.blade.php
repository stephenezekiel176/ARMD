{{-- resources/views/partials/three-model.blade.php --}}
<div class="three-canvas h-96 bg-gray-100 rounded-lg overflow-hidden"></div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/three@0.166.1/build/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.166.1/examples/js/controls/OrbitControls.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.166.1/examples/js/loaders/GLTFLoader.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.three-canvas');
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0xf3f4f6);

    const camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 100);
    camera.position.set(0, 1, 4);

    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    container.appendChild(renderer.domElement);

    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;

    const light = new THREE.HemisphereLight(0xffffff, 0x444444, 1);
    scene.add(light);

    const loader = new THREE.GLTFLoader();
    loader.load('https://raw.githubusercontent.com/KhronosGroup/glTF-Sample-Models/master/2.0/Duck/glTF/Duck.gltf',
        gltf => scene.add(gltf.scene),
        undefined,
        err => console.error(err)
    );

    const animate = () => {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    };
    animate();

    window.addEventListener('resize', () => {
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
    });
});
</script>
@endpush
