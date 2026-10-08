@extends('layouts.app')
@section('content')

{{-- HERO: clay exhibition --}}
<section class="hero-clay" id="jelajahi">
<div class="wrap">
<div class="hero-grid">
<div class="reveal">
<p class="hero-kicker">MUSANTARA · ARSIP BUNYI NUSANTARA</p>
<h1 class="hero-title">Ruang Arsip<br><em>yang Hidup</em></h1>
<p class="hero-sub">Museum tanah liat digital untuk alat musik tradisional Indonesia. Sentuh, tekan, dengarkan — setiap objek punya bunyi.</p>
<div class="hero-ctas">
<a href="#arsip" class="clay-btn primary">Jelajahi Arsip <span class="arw">→</span></a>
<a href="#pulau" class="clay-btn">Peta Kepulauan</a>
<a href="{{ url('/ruang-bunyi') }}" class="clay-btn">Ruang Bunyi 3D</a>
</div>
<div class="hero-meta">
<div><b>{{ array_sum($islandCounts ?? []) }}</b><span>INSTRUMEN</span></div>
<div><b>7</b><span>KEPULAUAN</span></div>
<div><b>∞</b><span>BUNYI</span></div>
</div>
</div>
@if($hero)
<div class="artifact-stage reveal" id="heroArtifact"
  data-tipe="{{ $heroTipe ?? 'gong' }}"
  data-nama="{{ $hero->nama }}"
  data-region="{{ optional($hero->pulau)->nama ?? 'Nusantara' }}"
  data-freq="{{ $heroFreq ?? 98 }}">
<div class="as-spotlight" aria-hidden="true"></div>
<div class="as-platform" aria-hidden="true"><div class="as-tier1"></div><div class="as-tier2"></div></div>
<div class="as-shadow" aria-hidden="true"></div>
<div id="hero3d" role="button" tabindex="0" aria-label="Putar {{ $hero->nama }}"
  style="position:relative;z-index:2;width:min(340px,72vw);aspect-ratio:1;cursor:pointer"></div>
<div class="hero-tag">
<p class="tn">{{ $hero->nama }}</p>
<p class="ts">{{ optional($hero->pulau)->nama ?? 'Nusantara' }}{{ $hero->sumber_bunyi ? ' · '.$hero->sumber_bunyi : '' }}</p>
</div>
<button class="hero-play" id="heroPlayBtn" aria-label="Bunyikan {{ $hero->nama }}">▶</button>
</div>
<script type="importmap">
{"imports":{"three":"https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js"}}
</script>
<script type="module">
import * as THREE from 'three';
(function(){
const stage=document.getElementById('heroArtifact'); if(!stage) return;
const box=document.getElementById('hero3d');
const TIPE=stage.dataset.tipe||'gong', NAMA=stage.dataset.nama||'Instrumen',
      REGION=stage.dataset.region||'Nusantara', FREQ=parseFloat(stage.dataset.freq||'98');
// Single source of truth: semua dari TIPE/NAMA/REGION/FREQ di atas
let renderer;
try{ renderer=new THREE.WebGLRenderer({antialias:true,alpha:true}); }
catch(e){ box.innerHTML='<p style="color:var(--muted);text-align:center">3D tidak didukung</p>'; return; }
renderer.setPixelRatio(Math.min(devicePixelRatio,2));
box.appendChild(renderer.domElement);
const scene=new THREE.Scene();
const cam=new THREE.PerspectiveCamera(38,1,.1,50); cam.position.set(0,1.4,5.4); cam.lookAt(0,.4,0);
// Lights: museum
scene.add(new THREE.AmbientLight(0x9a8878,.7));
const key=new THREE.DirectionalLight(0xffe2b8,1.6); key.position.set(3,6,4); scene.add(key);
const warm=new THREE.PointLight(0xA95135,8,10); warm.position.set(-2.5,1.5,2.5); scene.add(warm);
const M={
  bronze:new THREE.MeshStandardMaterial({color:0x745844,roughness:.5,metalness:.5}),
  bronzeD:new THREE.MeshStandardMaterial({color:0x4a382c,roughness:.6,metalness:.4}),
  wood:new THREE.MeshStandardMaterial({color:0x4A3423,roughness:.9}),
  bamboo:new THREE.MeshStandardMaterial({color:0x8a7a52,roughness:.85}),
  clay:new THREE.MeshStandardMaterial({color:0x62483A,roughness:.9}),
  skin:new THREE.MeshStandardMaterial({color:0xC9B59D,roughness:.85}),
  terra:new THREE.MeshStandardMaterial({color:0xA95135,roughness:.8}),
};
function sh(m){m.castShadow=true;return m;}
const G=new THREE.Group(); scene.add(G);
function buildGong(){
  const d=sh(new THREE.Mesh(new THREE.CylinderGeometry(1.05,1.05,.07,40),M.bronze));
  d.rotation.x=Math.PI/2; d.position.y=.6; G.add(d);
  const b=sh(new THREE.Mesh(new THREE.SphereGeometry(.26,24,18),M.bronzeD));
  b.scale.z=.45; b.position.set(0,.6,.07); G.add(b);
  const r=sh(new THREE.Mesh(new THREE.TorusGeometry(1.05,.05,14,44),M.bronzeD)); r.position.y=.6; G.add(r);
  [[-1.2],[1.2]].forEach(([x])=>{const p=sh(new THREE.Mesh(new THREE.BoxGeometry(.13,2,.13),M.wood));p.position.set(x,.1,0);G.add(p);});
  const bar=sh(new THREE.Mesh(new THREE.BoxGeometry(2.7,.11,.11),M.wood)); bar.position.y=1.05; G.add(bar);
  G.userData.vib=(t)=>{d.position.z=Math.sin(t*42)*.02;b.position.z=.07+Math.sin(t*42)*.02;};
}
function buildKendang(){
  const bd=sh(new THREE.Mesh(new THREE.CylinderGeometry(.42,.34,1.15,24),M.wood));
  bd.rotation.z=Math.PI/2; bd.position.y=.35; G.add(bd);
  [[-.58,.42],[.58,.34]].forEach(([x,r])=>{
    const m=sh(new THREE.Mesh(new THREE.CylinderGeometry(r,r,.05,24),M.skin));
    m.rotation.z=Math.PI/2; m.position.set(x,.35,0); G.add(m);
    const rg=sh(new THREE.Mesh(new THREE.TorusGeometry(r,.03,10,28),M.terra));
    rg.rotation.y=Math.PI/2; rg.position.set(x,.35,0); G.add(rg);});
  G.userData.vib=(t)=>{bd.rotation.x=Math.sin(t*38)*.02;};
}
function buildSaron(){
  const base=sh(new THREE.Mesh(new THREE.BoxGeometry(1.7,.15,.6),M.wood)); base.position.y=.1; G.add(base);
  for(let i=0;i<6;i++){const w=.52-i*.04;
    const b=sh(new THREE.Mesh(new THREE.BoxGeometry(w,.07,.34),M.bronze));
    b.position.set(-.62+i*.25,.24,0); b.name='br'+i; G.add(b);}
  G.userData.vib=(t)=>{G.children.forEach(c=>{if(c.name.startsWith('br'))c.position.y=.24+Math.sin(t*32+c.position.x*9)*.01;});};
}
function buildSasando(){
  const fan=sh(new THREE.Mesh(new THREE.CylinderGeometry(.15,.75,.9,24,1,true),M.clay));
  fan.position.y=.35; G.add(fan);
  for(let i=-3;i<=3;i++){const s=new THREE.Mesh(new THREE.CylinderGeometry(.008,.008,1.1,6),M.skin);
    s.position.set(i*.14,.45,0); s.rotation.z=-i*.09; G.add(s);}
  const pole=sh(new THREE.Mesh(new THREE.CylinderGeometry(.05,.05,1.3,10),M.wood)); pole.position.y=.3; G.add(pole);
  G.userData.vib=(t)=>{G.rotation.z=Math.sin(t*30)*.012;};
}
function buildAngklung(){
  const top=sh(new THREE.Mesh(new THREE.BoxGeometry(1.3,.09,.09),M.bamboo)); top.position.y=1; G.add(top);
  [[-.45,.75],[-.15,.9],[.15,.75],[.45,.6]].forEach(([x,h])=>{
    const tb=sh(new THREE.Mesh(new THREE.CylinderGeometry(.09,.09,h,12),M.bamboo));
    tb.position.set(x,1-h/2,0); tb.name='tb'; G.add(tb);});
  G.userData.vib=(t)=>{G.children.forEach((c,i)=>{if(c.name==='tb')c.rotation.z=Math.sin(t*26+i)*.03;});};
}
function buildSape(){
  const body=sh(new THREE.Mesh(new THREE.SphereGeometry(.34,20,16),M.wood));
  body.scale.set(1,1.35,.55); body.position.y=.1; G.add(body);
  const neck=sh(new THREE.Mesh(new THREE.BoxGeometry(.12,1.1,.1),M.wood)); neck.position.y=.85; G.add(neck);
  for(let i=-1;i<=1;i++){const s=new THREE.Mesh(new THREE.CylinderGeometry(.008,.008,1.6,6),M.skin);
    s.position.set(i*.05,.5,.06); G.add(s);}
  G.userData.vib=(t)=>{G.rotation.y=Math.sin(t*.2)*.14+Math.sin(t*34)*.008;};
}
({gong:buildGong,kendang:buildKendang,saron:buildSaron,sasando:buildSasando,angklung:buildAngklung,sape:buildSape}[TIPE]||buildGong)();
// Sound rings
const rings=[];
function ripple(){
  for(let i=0;i<3;i++){
    const r=new THREE.Mesh(new THREE.RingGeometry(.6,.66,40),
      new THREE.MeshBasicMaterial({color:0xA95135,transparent:true,opacity:.75,side:THREE.DoubleSide}));
    r.position.y=.6; r.rotation.x=0; scene.add(r); rings.push({m:r,t:-i*.22});
  }
}
// Audio: synth dari FREQ hero (single source of truth)
let AC=null;
function playAudio(){
  try{
    AC=AC||new (window.AudioContext||window.webkitAudioContext)();
    if(AC.state==='suspended')AC.resume();
    const t=AC.currentTime, o=AC.createOscillator(), g=AC.createGain();
    o.type='sine'; o.frequency.value=FREQ;
    const o2=AC.createOscillator(), g2=AC.createGain();
    o2.type='sine'; o2.frequency.value=FREQ*2.01;
    g.gain.setValueAtTime(0,t); g.gain.linearRampToValueAtTime(.45,t+.02);
    g.gain.exponentialRampToValueAtTime(.0001,t+2.4);
    g2.gain.setValueAtTime(.18,t); g2.gain.exponentialRampToValueAtTime(.0001,t+1.4);
    o.connect(g).connect(AC.destination); o2.connect(g2).connect(AC.destination);
    o.start(t);o.stop(t+2.6); o2.start(t);o2.stop(t+1.6);
  }catch(e){}
}
// Play: SEMUA dari NAMA/TIPE/FREQ yang sama
let sounding=false, vibT=0;
function play(){
  playAudio(); ripple();
  sounding=true; vibT=0;
  stage.classList.add('sounding');
  const pb=document.getElementById('heroPlayBtn'); if(pb){pb.classList.add('playing');pb.textContent='\u275A\u275A';}
  if(window.mpShow) mpShow(NAMA, 'Artefak · '+REGION); // toast: NAMA yang sama
  const st=document.getElementById('soundStatus');
  if(st) st.textContent='\u266A '+NAMA.toUpperCase()+' \u00B7 '+REGION.toUpperCase();
  clearTimeout(stage._pt);
  stage._pt=setTimeout(stop, 2600);
}
function stop(){
  sounding=false; stage.classList.remove('sounding');
  const pb=document.getElementById('heroPlayBtn'); if(pb){pb.classList.remove('playing');pb.textContent='\u25B6';}
}
box.addEventListener('click',play);
box.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();play();}});
document.getElementById('heroPlayBtn')?.addEventListener('click',play);
// Parallax pointer
if(matchMedia('(hover:hover)').matches){
  stage.addEventListener('mousemove',e=>{
    const r=stage.getBoundingClientRect();
    const x=(e.clientX-r.left)/r.width-.5, y=(e.clientY-r.top)/r.height-.5;
    G.rotation.y=x*.5; G.position.x=x*.25; cam.position.x=x*.7;
  });
  stage.addEventListener('mouseleave',()=>{G.position.x=0;cam.position.x=0;});
}
// Resize + loop
function rs(){const w=box.clientWidth,h=box.clientHeight;renderer.setSize(w,h);cam.aspect=w/h;cam.updateProjectionMatrix();}
new ResizeObserver(rs).observe(box); rs();
const clk=new THREE.Clock();
(function loop(){
  requestAnimationFrame(loop);
  const t=clk.getElapsedTime();
  if(!sounding) G.rotation.y=Math.sin(t*.22)*.16; // idle
  else { vibT+=.016; if(G.userData.vib)G.userData.vib(vibT); }
  for(let i=rings.length-1;i>=0;i--){const r=rings[i]; r.t+=.016; if(r.t<0)continue;
    const k=r.t/1.5;
    if(k>=1){scene.remove(r.m);r.m.geometry.dispose();r.m.material.dispose();rings.splice(i,1);continue;}
    const s=1+k*2.6; r.m.scale.set(s,s,1); r.m.material.opacity=.75*(1-k);}
  renderer.render(scene,cam);
})();
})();
</script>
@endif
</div>
</div>
</section>

{{-- 01 ARSIP BUNYI --}}
<section class="sect" id="arsip">
<div class="wrap">
<div class="sect-label reveal"><span class="n">01 / ARSIP</span><span class="t">Ruang Bunyi</span><span class="ln"></span></div>
<div class="gamelan gamelan-sec">
<div class="gamelan-frame reveal">
<div class="gamelan-pads">
<button type="button" class="gamelan-pad" data-instrument="gong" data-freq="98" aria-label="Pukul gong besar"><b>Gong</b><span>98 Hz</span></button>
<button type="button" class="gamelan-pad" data-instrument="kempul" data-freq="147" aria-label="Pukul kempul"><b>Kempul</b><span>147 Hz</span></button>
<button type="button" class="gamelan-pad" data-instrument="kenong" data-freq="220" aria-label="Pukul kenong"><b>Kenong</b><span>220 Hz</span></button>
<button type="button" class="gamelan-pad" data-instrument="saron" data-freq="392" aria-label="Pukul saron"><b>Saron</b><span>392 Hz</span></button>
<button type="button" class="gamelan-pad" data-instrument="bonang" data-freq="523.25" aria-label="Pukul bonang"><b>Bonang</b><span>523 Hz</span></button>
<button type="button" class="gamelan-pad" data-instrument="gambang" data-freq="659.25" aria-label="Pukul gambang"><b>Gambang</b><span>659 Hz</span></button>
<button type="button" class="gamelan-pad" data-instrument="demung" data-freq="174.61" aria-label="Pukul demung"><b>Demung</b><span>175 Hz</span></button>
<button type="button" class="gamelan-pad" data-instrument="peking" data-freq="880" aria-label="Pukul peking"><b>Peking</b><span>880 Hz</span></button>
</div>
<p class="gamelan-status" id="soundStatus" aria-live="polite">KETUK OBJEK UNTUK MENDENGAR</p>
</div>
</div>
</div>
</section>

{{-- 02 PULAU --}}
<section class="map-sec" id="pulau">
<div class="wrap">
<div class="sect-label reveal"><span class="n">02 / PULAU</span><span class="t">Kepulauan</span><span class="ln"></span></div>
<div class="map-frame reveal">
<div class="map-wrap">
@include('partials.map')
</div>
<div class="map-bar" id="regionBar"></div>
<div id="island-data" data-counts='@json($islandCounts ?? [])' hidden></div>
</div>
</div>
</section>

{{-- 03 KOLEKSI --}}
<section class="sect" id="koleksi">
<div class="wrap">
<div class="sect-label reveal"><span class="n">03 / ARSIP</span><span class="t">Koleksi</span><span class="ln"></span></div>
@foreach($regions as $ri => $rg)
<div class="sect-label reveal" style="margin:2.4rem 0 1.6rem"><span class="n">{{ $rg['pulau']->nama }}</span><span class="t" style="font-size:1rem">{{ $rg['items']->count() }} objek</span><span class="ln"></span></div>
<div class="pods">
@foreach($rg['items'] as $idx => $item)
@include('components.card',['item'=>$item,'idx'=>$idx])
@endforeach
</div>
@endforeach
<div class="reveal" style="margin:3.5rem 0 1rem;text-align:center">
<a href="{{ url('/search') }}" class="clay-btn primary">Lihat Semua Arsip <span class="arw">→</span></a>
</div>
</div>
</section>

{{-- 04 KUIS --}}
<section class="sect" id="kuis" style="padding-bottom:6rem">
<div class="wrap">
<div class="sect-label reveal"><span class="n">04 / KUIS</span><span class="t">Uji Telinga</span><span class="ln"></span></div>
<div class="quiz-card reveal" style="max-width:560px;margin:0 auto">
<p style="font-family:var(--mono);font-size:.62rem;letter-spacing:.24em;color:var(--terra);margin-bottom:.8rem">SEMBILAN SOAL</p>
<p style="font-size:1.05rem;color:var(--muted);margin-bottom:1.6rem">Dengarkan bunyinya, tebak instrumennya. Sepuluh soal singkat tentang Nusantara.</p>
<a href="{{ url('/quiz-global') }}" class="clay-btn primary">Mulai Kuis <span class="arw">→</span></a>
</div>
</div>
</section>

@endsection
