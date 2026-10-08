@extends('layouts.app')
@section('title', 'Ruang Bunyi — MuSantara')
@section('content')
<div class="wrap" style="padding:7rem 0 1rem">
<a href="{{ url('/') }}" class="clay-btn reveal">← Beranda</a>
</div>

<section style="padding:1rem 0 4rem">
<div class="wrap">
<div class="sect-label reveal"><span class="n">RUANG</span><span class="t">Ruang Bunyi</span><span class="ln"></span></div>
<p class="reveal" style="color:var(--muted);max-width:52ch;margin-bottom:1.6rem">Galeri 3D tanah liat. Putar kamera, ketuk instrumen untuk membunyikannya. <span style="font-family:var(--mono);font-size:.72rem;letter-spacing:.1em">VISUALISASI 3D</span></p>

<div class="reveal" style="position:relative;border-radius:var(--r-l);overflow:hidden;
  box-shadow:0 6px 12px rgba(0,0,0,.5),0 30px 60px rgba(0,0,0,.35),inset 0 2px 4px rgba(229,214,192,.08)">
<div id="rb3d" style="width:100%;height:min(68vh,560px);min-height:380px;background:#141110;touch-action:none" role="img" aria-label="Visualisasi 3D alat musik tradisional"></div>

{{-- Plaque overlay --}}
<div id="rbPlaque" style="position:absolute;left:1.2rem;bottom:1.2rem;max-width:min(340px,80%)">
<div class="plaque" style="background:linear-gradient(145deg,rgba(58,46,37,.94),rgba(42,35,29,.94))">
<p class="k">ARTEFAK <span id="rbIdx">1 / {{ $items->count() }}</span></p>
<p class="v" id="rbNama" style="font-family:var(--display);font-size:1.25rem">—</p>
<p class="k" id="rbRegion" style="margin-top:.3rem">—</p>
</div>
</div>

{{-- Controls --}}
<div style="position:absolute;right:1.2rem;bottom:1.2rem;display:flex;gap:.6rem">
<button class="clay-btn" id="rbPrev" aria-label="Instrumen sebelumnya" style="padding:.7rem 1rem">←</button>
<button class="clay-btn primary" id="rbHit" style="padding:.7rem 1.3rem">Bunyikan</button>
<button class="clay-btn" id="rbNext" aria-label="Instrumen berikutnya" style="padding:.7rem 1rem">→</button>
</div>

<div id="rbFallback" style="display:none;position:absolute;inset:0;place-items:center;background:#141110;color:var(--muted);text-align:center;padding:2rem">
<p>Perangkat tidak mendukung 3D.<br>Lihat foto artefak sebagai gantinya.</p>
</div>
</div>

<p class="reveal" style="font-family:var(--mono);font-size:.62rem;letter-spacing:.16em;color:var(--muted);margin-top:1rem;text-align:center">GESER UNTUK MEMUTAR · CUBIT/RODA UNTUK ZOOM · KETUK INSTRUMEN UNTUK BUNYI</p>
</div>
</section>

<script type="importmap">
{"imports":{"three":"https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js","three/addons/":"https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/"}}
</script>
<script type="module">
import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

const ITEMS = @json($items);
const box = document.getElementById('rb3d');
let renderer;
try {
  renderer = new THREE.WebGLRenderer({antialias:true});
} catch(e) {
  document.getElementById('rbFallback').style.display='grid';
  throw e;
}
renderer.setPixelRatio(Math.min(devicePixelRatio,2));
renderer.shadowMap.enabled = true;
renderer.shadowMap.type = THREE.PCFSoftShadowMap;
box.appendChild(renderer.domElement);

const scene = new THREE.Scene();
scene.background = new THREE.Color(0x141110);
scene.fog = new THREE.Fog(0x141110, 9, 20);

const camera = new THREE.PerspectiveCamera(42, 1, .1, 60);
camera.position.set(3.4, 2.6, 5.2);

const controls = new OrbitControls(camera, renderer.domElement);
controls.target.set(0, 1.15, 0);
controls.enableDamping = true; controls.dampingFactor = .06;
controls.enablePan = false;
controls.minDistance = 2.6; controls.maxDistance = 9;
controls.maxPolarAngle = 1.45; controls.minPolarAngle = .35;

/* ——— LIGHTING: museum ——— */
scene.add(new THREE.AmbientLight(0x8a7a68, .55));
const key = new THREE.DirectionalLight(0xffe0b8, 1.5);
key.position.set(4, 7, 4); key.castShadow = true;
key.shadow.mapSize.set(1024,1024);
key.shadow.camera.left=-5; key.shadow.camera.right=5; key.shadow.camera.top=6; key.shadow.camera.bottom=-3;
scene.add(key);
const rim = new THREE.PointLight(0xA95135, 12, 12); rim.position.set(-3.5, 2.2, -2.5); scene.add(rim);
const fill = new THREE.PointLight(0xC9B59D, 6, 10); fill.position.set(0, 1.6, 4); scene.add(fill);

/* ——— MATERIALS: clay ——— */
const M = {
  clayDark: new THREE.MeshStandardMaterial({color:0x2A231D, roughness:.95}),
  clay: new THREE.MeshStandardMaterial({color:0x3A2E25, roughness:.9}),
  clayLight: new THREE.MeshStandardMaterial({color:0x62483A, roughness:.85}),
  bronze: new THREE.MeshStandardMaterial({color:0x745844, roughness:.55, metalness:.45}),
  bronzeDark: new THREE.MeshStandardMaterial({color:0x4a382c, roughness:.6, metalness:.4}),
  wood: new THREE.MeshStandardMaterial({color:0x4A3423, roughness:.9}),
  membrane: new THREE.MeshStandardMaterial({color:0xC9B59D, roughness:.85}),
  terra: new THREE.MeshStandardMaterial({color:0xA95135, roughness:.8}),
};
function shadowed(m){ m.castShadow=true; m.receiveShadow=true; return m; }

/* ——— ROOM ——— */
const floor = shadowed(new THREE.Mesh(new THREE.CircleGeometry(9, 48), M.clayDark));
floor.rotation.x = -Math.PI/2; floor.receiveShadow = true; scene.add(floor);
const wallGeo = new THREE.CylinderGeometry(9, 9, 7, 48, 1, true, Math.PI, Math.PI);
const wall = new THREE.Mesh(wallGeo, new THREE.MeshStandardMaterial({color:0x211B17, roughness:1, side:THREE.BackSide}));
wall.position.y = 3.5; scene.add(wall);
// pedestal
const ped = shadowed(new THREE.Mesh(new THREE.CylinderGeometry(1.05, 1.2, .5, 32), M.clay));
ped.position.y = .25; scene.add(ped);
const pedTop = shadowed(new THREE.Mesh(new THREE.CylinderGeometry(1.12, 1.05, .08, 32), M.clayLight));
pedTop.position.y = .53; scene.add(pedTop);
// small decor: clay pots
[[-2.6,-1.6],[2.8,-1.2]].forEach(([x,z],i)=>{
  const pot = shadowed(new THREE.Mesh(new THREE.SphereGeometry(.32-i*.06, 20, 16), M.clay));
  pot.scale.y = .82; pot.position.set(x,.26,z); scene.add(pot);
});

/* ——— PROCEDURAL INSTRUMENTS ——— */
function buildGong(){
  const g = new THREE.Group();
  const disc = shadowed(new THREE.Mesh(new THREE.CylinderGeometry(1.05, 1.05, .07, 40), M.bronze));
  disc.rotation.x = Math.PI/2; disc.position.y = 1.55; g.add(disc);
  const boss = shadowed(new THREE.Mesh(new THREE.SphereGeometry(.26, 24, 18), M.bronzeDark));
  boss.scale.z = .45; boss.position.set(0, 1.55, .06); g.add(boss);
  const rimT = shadowed(new THREE.Mesh(new THREE.TorusGeometry(1.05, .045, 14, 44), M.bronzeDark));
  rimT.position.y = 1.55; g.add(rimT);
  // stand
  [[-1.15],[1.15]].forEach(([x])=>{
    const post = shadowed(new THREE.Mesh(new THREE.BoxGeometry(.14, 2.1, .14), M.wood));
    post.position.set(x, 1.05, 0); g.add(post);
  });
  const bar = shadowed(new THREE.Mesh(new THREE.BoxGeometry(2.6, .12, .12), M.wood));
  bar.position.y = 2.05; g.add(bar);
  // mallet
  const mallet = new THREE.Group();
  const stick = shadowed(new THREE.Mesh(new THREE.CylinderGeometry(.03,.03,.7,10), M.wood));
  stick.rotation.z = .9; mallet.add(stick);
  const head = shadowed(new THREE.Mesh(new THREE.SphereGeometry(.11, 14, 12), M.terra));
  head.position.set(.3,.14,0); mallet.add(head);
  mallet.position.set(.85, 1.15, .5); mallet.rotation.x = -.5;
  mallet.name = 'mallet'; g.add(mallet);
  g.userData.hit = ()=>{ mallet.position.z = .1; setTimeout(()=>mallet.position.z=.5, 160); };
  g.userData.vibrate = (t)=>{ disc.position.z = Math.sin(t*40)*.02; boss.position.z = .06+Math.sin(t*40)*.02; };
  return g;
}
function buildKendang(){
  const g = new THREE.Group();
  const body = shadowed(new THREE.Mesh(new THREE.CylinderGeometry(.42, .34, 1.15, 24), M.wood));
  body.rotation.z = Math.PI/2; body.position.y = 1.25; g.add(body);
  [[-.58,.42],[.58,.34]].forEach(([x,r])=>{
    const mem = shadowed(new THREE.Mesh(new THREE.CylinderGeometry(r, r, .04, 24), M.membrane));
    mem.rotation.z = Math.PI/2; mem.position.set(x, 1.25, 0); g.add(mem);
    const ring = shadowed(new THREE.Mesh(new THREE.TorusGeometry(r, .03, 10, 28), M.terra));
    ring.rotation.y = Math.PI/2; ring.position.set(x, 1.25, 0); g.add(ring);
  });
  // X stand
  [[.5],[-.5]].forEach(([rz])=>{
    const leg = shadowed(new THREE.Mesh(new THREE.BoxGeometry(.1, 1.1, .1), M.wood));
    leg.position.y = .55; leg.rotation.z = rz; g.add(leg);
  });
  g.userData.hit = ()=>{ body.scale.set(1.06,1.06,1); setTimeout(()=>body.scale.set(1,1,1),150); };
  g.userData.vibrate = (t)=>{ body.rotation.x = Math.sin(t*36)*.015; };
  return g;
}
function buildSaron(){
  const g = new THREE.Group();
  const base = shadowed(new THREE.Mesh(new THREE.BoxGeometry(1.7, .16, .6), M.wood));
  base.position.y = .95; g.add(base);
  [[-.7],[.7]].forEach(([x])=>{
    const side = shadowed(new THREE.Mesh(new THREE.BoxGeometry(.12, .5, .6), M.wood));
    side.position.set(x, .78, 0); g.add(side);
  });
  for(let i=0;i<6;i++){
    const w = .5 - i*.035;
    const barM = shadowed(new THREE.Mesh(new THREE.BoxGeometry(w, .07, .34), M.bronze));
    barM.position.set(-.62 + i*.25, 1.08, 0); barM.name='bar'+i; g.add(barM);
    const pin = new THREE.Mesh(new THREE.CylinderGeometry(.02,.02,.1,8), M.bronzeDark);
    pin.position.set(-.62 + i*.25, 1.14, 0); g.add(pin);
  }
  g.userData.hit = ()=>{ const b=g.getObjectByName('bar2'); if(b){ b.position.y=.98; setTimeout(()=>b.position.y=1.08,160);} };
  g.userData.vibrate = (t)=>{ g.children.forEach(c=>{ if(c.name.startsWith('bar')) c.position.y = 1.08 + Math.sin(t*30 + c.position.x*8)*.008; }); };
  return g;
}
const BUILDERS = {gong: buildGong, kendang: buildKendang, saron: buildSaron};

/* ——— AUDIO SYNTH ——— */
let AC=null;
function ac(){ if(!AC) AC = new (window.AudioContext||window.webkitAudioContext)(); if(AC.state==='suspended') AC.resume(); return AC; }
function playTone(tipe){
  const c=ac(), t=c.currentTime;
  const conf={gong:{f:98,d:3.2,type:'sine'},kendang:{f:180,d:.5,type:'triangle'},saron:{f:392,d:1.6,type:'sine'}}[tipe]||{f:220,d:1.5,type:'sine'};
  const o=c.createOscillator(), g=c.createGain();
  o.type=conf.type; o.frequency.value=conf.f;
  const o2=c.createOscillator(), g2=c.createGain();
  o2.type='sine'; o2.frequency.value=conf.f*2.01; g2.gain.value=.25;
  g.gain.setValueAtTime(0,t); g.gain.linearRampToValueAtTime(.5,t+.015); g.gain.exponentialRampToValueAtTime(.0001,t+conf.d);
  g2.gain.setValueAtTime(.2,t); g2.gain.exponentialRampToValueAtTime(.0001,t+conf.d*.6);
  o.connect(g).connect(c.destination); o2.connect(g2).connect(c.destination);
  o.start(t); o.stop(t+conf.d+.1); o2.start(t); o2.stop(t+conf.d);
}

/* ——— RIPPLES ——— */
const ripples=[];
function ripple(){
  for(let i=0;i<3;i++){
    const r=new THREE.Mesh(new THREE.RingGeometry(.5,.56,40),
      new THREE.MeshBasicMaterial({color:0xA95135,transparent:true,opacity:.7-i*.2,side:THREE.DoubleSide}));
    r.rotation.x=-Math.PI/2; r.position.y=.58+i*.02; scene.add(r);
    ripples.push({m:r,t:0,delay:i*.18});
  }
}

/* ——— INSTRUMENT MANAGER ——— */
let cur=null, curIdx=0, vibT=0, vibrating=false;
function show(i){
  curIdx=(i+ITEMS.length)%ITEMS.length;
  const it=ITEMS[curIdx];
  if(cur) scene.remove(cur);
  cur=(BUILDERS[it.tipe_3d]||buildGong)();
  cur.position.y=.57; scene.add(cur);
  document.getElementById('rbNama').textContent=it.nama;
  document.getElementById('rbRegion').textContent=(it.pulau+' · '+it.sumber).toUpperCase();
  document.getElementById('rbIdx').textContent=(curIdx+1)+' / '+ITEMS.length;
}
function hit(){
  if(!cur) return;
  const it=ITEMS[curIdx];
  cur.userData.hit(); ripple(); playTone(it.tipe_3d);
  vibrating=true; vibT=0;
  setTimeout(()=>vibrating=false, 1200);
  if(window.mpShow) mpShow(it.nama, 'Ruang Bunyi · '+it.pulau);
}
show(0);
document.getElementById('rbPrev').onclick=()=>show(curIdx-1);
document.getElementById('rbNext').onclick=()=>show(curIdx+1);
document.getElementById('rbHit').onclick=hit;

// click instrument via raycast
const ray=new THREE.Raycaster(), ptr=new THREE.Vector2();
let downAt=0;
renderer.domElement.addEventListener('pointerdown',()=>downAt=Date.now());
renderer.domElement.addEventListener('pointerup',e=>{
  if(Date.now()-downAt>280) return; // itu drag, bukan klik
  const r=renderer.domElement.getBoundingClientRect();
  ptr.x=((e.clientX-r.left)/r.width)*2-1; ptr.y=-((e.clientY-r.top)/r.height)*2+1;
  ray.setFromCamera(ptr,camera);
  if(cur && ray.intersectObject(cur,true).length) hit();
});

/* ——— RESIZE + LOOP ——— */
function resize(){
  const w=box.clientWidth,h=box.clientHeight;
  renderer.setSize(w,h); camera.aspect=w/h; camera.updateProjectionMatrix();
}
new ResizeObserver(resize).observe(box); resize();
const clock=new THREE.Clock();
(function loop(){
  requestAnimationFrame(loop);
  const t=clock.getElapsedTime();
  if(cur && !vibrating) cur.rotation.y = Math.sin(t*.18)*.14; // idle: sangat pelan
  if(vibrating && cur){ vibT+=.016; cur.userData.vibrate(vibT); }
  for(let i=ripples.length-1;i>=0;i--){
    const r=ripples[i]; r.t+=.016;
    if(r.t<r.delay) continue;
    const k=(r.t-r.delay)/1.4;
    if(k>=1){ scene.remove(r.m); r.m.geometry.dispose(); r.m.material.dispose(); ripples.splice(i,1); continue; }
    const s=1+k*3.2; r.m.scale.set(s,s,1); r.m.material.opacity=.7*(1-k);
  }
  controls.update();
  renderer.render(scene,camera);
})();
</script>
@endsection
