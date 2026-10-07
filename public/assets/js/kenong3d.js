/* Kenong 3D interaktif — Three.js LatheGeometry, bisa diputar & diketuk. */
(function () {
    var container = document.getElementById('kenong3d');
    if (!container || typeof THREE === 'undefined') return;

    var W = container.clientWidth || 300;
    var H = container.clientHeight || 300;

    var scene = new THREE.Scene();
    scene.background = null;

    var camera = new THREE.PerspectiveCamera(42, W / H, 0.1, 100);
    camera.position.set(0, 1.6, 4.2);
    camera.lookAt(0, 0.55, 0);

    var renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(W, H);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.15;
    container.appendChild(renderer.domElement);

    /* --- Kenong: profil kettle (lathe) --- */
    var pts = [];
    var profile = [
        [0.001, 0.00], [1.70, 0.00], [1.80, 0.06], [1.78, 0.16],
        [1.55, 0.34], [1.20, 0.55], [0.85, 0.72], [0.55, 0.86],
        [0.42, 0.94], [0.38, 1.04], [0.30, 1.14], [0.18, 1.20], [0.001, 1.22]
    ];
    profile.forEach(function (p) { pts.push(new THREE.Vector2(p[0], p[1])); });

    var bronze = new THREE.MeshStandardMaterial({
        color: 0xc9973f, metalness: 0.92, roughness: 0.34,
        envMapIntensity: 1.0
    });
    var kenong = new THREE.Mesh(new THREE.LatheGeometry(pts, 72), bronze);
    kenong.position.y = -0.55;
    scene.add(kenong);

    /* cincin dekoratif */
    var ringMat = new THREE.MeshStandardMaterial({ color: 0x8a6420, metalness: 0.9, roughness: 0.45 });
    [0.32, 0.52].forEach(function (y) {
        var r = y < 0.4 ? 1.62 : 1.28;
        var ring = new THREE.Mesh(new THREE.TorusGeometry(r, 0.022, 12, 72), ringMat);
        ring.rotation.x = Math.PI / 2;
        ring.position.y = y - 0.55;
        scene.add(ring);
    });

    /* --- Lighting sinematik --- */
    scene.add(new THREE.AmbientLight(0x40342a, 0.7));
    var key = new THREE.DirectionalLight(0xffe0b0, 2.2);
    key.position.set(3, 4, 2.5);
    scene.add(key);
    var rim = new THREE.DirectionalLight(0xc9973f, 1.4);
    rim.position.set(-3, 2, -2.5);
    scene.add(rim);
    var fill = new THREE.PointLight(0x6688ff, 0.35, 20);
    fill.position.set(-2, 1, 3);
    scene.add(fill);

    /* --- Environment sederhana untuk refleksi --- */
    var pmrem = new THREE.PMREMGenerator(renderer);
    var envScene = new THREE.Scene();
    envScene.background = new THREE.Color(0x1a1512);
    var envLight = new THREE.PointLight(0xffd9a0, 5, 0);
    envLight.position.set(2, 3, 2);
    envScene.add(envLight);
    scene.environment = pmrem.fromScene(envScene).texture;

    /* --- Interaksi: drag putar + klik ketuk --- */
    var dragging = false, px = 0, py = 0, vx = 0.003;
    var ray = new THREE.Raycaster(), mouse = new THREE.Vector2();

    renderer.domElement.addEventListener('pointerdown', function (e) {
        dragging = true; px = e.clientX; py = e.clientY;
    });
    window.addEventListener('pointerup', function () { dragging = false; });
    window.addEventListener('pointermove', function (e) {
        if (!dragging) return;
        kenong.rotation.y += (e.clientX - px) * 0.008;
        kenong.rotation.x = Math.max(-0.4, Math.min(0.4, kenong.rotation.x + (e.clientY - py) * 0.004));
        px = e.clientX; py = e.clientY; vx = 0;
    });
    renderer.domElement.addEventListener('click', function (e) {
        var r = renderer.domElement.getBoundingClientRect();
        mouse.x = ((e.clientX - r.left) / r.width) * 2 - 1;
        mouse.y = -((e.clientY - r.top) / r.height) * 2 + 1;
        ray.setFromCamera(mouse, camera);
        if (ray.intersectObject(kenong).length) strike3d();
    });

    /* ketuk: animasi + bunyi (pakai TIMBRES kenong dari gamelan.js) */
    var striking = 0;
    function strike3d() {
        striking = 1;
        /* bunyi */
        if (window.kenong3dStrike) window.kenong3dStrike();
        /* juga trigger pad 2D kalau ada */
        var pad = document.querySelector('.pad-kenong');
        if (pad) pad.click();
    }

    /* idle: putar pelan */
    (function animate() {
        requestAnimationFrame(animate);
        if (!dragging) kenong.rotation.y += vx;
        if (striking > 0) {
            striking = Math.max(0, striking - 0.06);
            var s = 1 + Math.sin(striking * Math.PI) * 0.06;
            kenong.scale.set(s, 1 / s, s);
        }
        renderer.render(scene, camera);
    })();

    window.addEventListener('resize', function () {
        var w = container.clientWidth, h = container.clientHeight;
        camera.aspect = w / h; camera.updateProjectionMatrix();
        renderer.setSize(w, h);
    });
})();
