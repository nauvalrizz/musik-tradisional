@extends('layouts.app')
@section('content')

{{-- Preload model 3D selama animasi loader — pas hero muncul udah siap render --}}
<link rel="preload" as="fetch" href="{{ asset('assets/models/gong.glb') }}" crossorigin>
<link rel="preload" as="fetch" href="{{ asset('assets/models/kenong.glb') }}" crossorigin>
<link rel="preload" as="fetch" href="{{ asset('assets/models/angklung.glb') }}" crossorigin>

{{-- Full-screen sections + parallax backgrounds (scroll-snap dimatikan atas permintaan user) --}}
<style>
.hero-clay,.sect,.map-sec{
  min-height:100vh;min-height:100svh;
  display:flex;flex-direction:column;justify-content:center;
  position:relative;padding-top:6rem!important;padding-bottom:4rem!important;
}
.hero-clay .wrap,.sect .wrap,.map-sec .wrap{width:100%}
/* Background animatif khusus section arsip: riak gelombang bunyi */
.arsip-bg{position:absolute;inset:0;overflow:hidden;pointer-events:none;z-index:0}
.sect .wrap{position:relative;z-index:1}
.wave{position:absolute;border-radius:50%;border:1px solid rgba(181,80,47,.13)}
.wave.w1{width:520px;height:520px;left:-160px;top:6%;animation:waveDrift 26s ease-in-out infinite alternate}
.wave.w2{width:760px;height:760px;right:-220px;top:30%;animation:waveDrift 34s ease-in-out infinite alternate-reverse}
.wave.w3{width:380px;height:380px;left:36%;bottom:-140px;animation:waveDrift 22s ease-in-out infinite alternate}
.wave i{position:absolute;inset:18%;border-radius:50%;border:1px solid rgba(181,80,47,.1)}
@keyframes waveDrift{from{transform:translate3d(0,0,0) scale(1)}to{transform:translate3d(0,-44px,0) scale(1.07)}}
@media(prefers-reduced-motion:reduce){.wave{animation:none}}
#jelajahi{background:radial-gradient(720px 480px at 22% 18%,rgba(169,110,70,.08),transparent 65%),#141110}
#arsip{background:radial-gradient(640px 460px at 78% 30%,rgba(140,95,60,.08),transparent 65%),linear-gradient(180deg,#171310,#141110);background-attachment:fixed}
#pulau{background:radial-gradient(800px 500px at 50% 70%,rgba(110,80,55,.07),transparent 60%),#151210;background-attachment:fixed}
#koleksi{background:radial-gradient(700px 480px at 25% 60%,rgba(169,110,70,.07),transparent 65%),linear-gradient(180deg,#141110,#171310);background-attachment:fixed}
#kuis{background:radial-gradient(640px 460px at 75% 25%,rgba(140,95,60,.08),transparent 65%),#141110;background-attachment:fixed}
@media(max-width:860px){#arsip,#pulau,#koleksi,#kuis{background-attachment:scroll}}
/* Idle text animations — napas museum yang halus */
@keyframes idleBreathe{0%,100%{letter-spacing:.015em;opacity:1}50%{letter-spacing:.045em;opacity:.94}}
@keyframes idleGlow{0%,100%{text-shadow:0 0 0 rgba(169,81,53,0)}50%{text-shadow:0 0 28px rgba(169,81,53,.28)}}
@keyframes idleFloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
.hero-title{animation:idleGlow 7s ease-in-out infinite}
.sect-label .t,.sect-label .ax-mask span{animation:idleBreathe 6s ease-in-out infinite;display:inline-block}
.hero-kicker{animation:idleBreathe 8s ease-in-out infinite}
@media(prefers-reduced-motion:reduce){.hero-title,.sect-label .t,.sect-label .ax-mask span,.hero-kicker{animation:none}}
/* Entrance animation tiap section — fade + naik saat masuk viewport */
.sec-enter{opacity:0;transform:translateY(64px) scale(.93);transition:opacity .55s ease,transform .65s cubic-bezier(.16,1,.3,1)}
.sec-enter.inview{opacity:1;transform:none}
.sec-enter.d1{transition-delay:.1s}.sec-enter.d2{transition-delay:.22s}.sec-enter.d3{transition-delay:.34s}
/* Label section: teks popup spring, garis draw sendiri (tidak nyatu) */
.sect-label.sec-enter .n,.sect-label.sec-enter .ax-mask{display:inline-block;opacity:0;transform:scale(.7) translateY(34px);transition:opacity .45s ease,transform .7s cubic-bezier(.34,1.56,.64,1)}
.sect-label.sec-enter.inview .n,.sect-label.sec-enter.inview .ax-mask{opacity:1;transform:none}
.sect-label.sec-enter .ax-mask{transition-delay:.08s}
.sect-label.sec-enter .ln{display:block;transform:scaleX(0);transform-origin:left;transition:transform .8s cubic-bezier(.16,1,.3,1) .15s}
.sect-label.sec-enter.inview .ln{transform:scaleX(1)}
@media(prefers-reduced-motion:reduce){.sec-enter{opacity:1;transform:none;transition:none}}
</style>


{{-- HERO: clay exhibition --}}
<section class="hero-clay" id="jelajahi">
<div class="wrap">
<div class="hero-grid">
<div id="heroTitleBlock" data-px="0.12">
<p class="hero-kicker" id="heroEyebrow">DIGITAL MUSEUM OF INDONESIAN SOUND</p>
<h1 class="hero-title" id="heroTitle" data-full="MUSANTARA">MUSANTARA</h1>
<p class="hero-sub ax-slideL">Museum tanah liat digital untuk alat musik tradisional Indonesia. Sentuh, tekan, dengarkan — setiap objek punya bunyi.</p>
<div class="hero-ctas ax-spring">
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
<div class="artifact-stage" id="heroArtifact" data-px="0.05">
<div class="as-spotlight" aria-hidden="true"></div>
<div class="as-soundfield" aria-hidden="true"><i></i><i></i><i></i></div>
<div class="as-platform" aria-hidden="true"><div class="as-tier1"></div><div class="as-tier2"></div></div>
<div class="as-shadow" aria-hidden="true"></div>
<div id="hero3d" role="button" tabindex="0" aria-label="Putar instrumen"
  style="position:relative;z-index:2;width:min(360px,74vw);aspect-ratio:1;cursor:pointer">
<div id="gongStatus" style="display:none;position:absolute;inset:0;align-items:center;justify-content:center;padding:24px">
<style>
.gong-spin{width:44px;height:44px;border-radius:50%;border:3px solid rgba(181,80,47,.18);border-top-color:#b5502f;animation:gspin .8s linear infinite}
@keyframes gspin{to{transform:rotate(360deg)}}
</style>
<div class="gong-spin" id="gongSpin"></div>
<div id="gongErr" style="display:none;color:#c9b99a;font-size:13px;text-align:center"></div>
</div>
</div>
<div class="hero-tag" id="heroPlaque">
<p class="tn" id="heroName">GONG</p>
<p class="ts" id="heroOrigin">JAWA · PERUNGGU</p>
</div>
<div class="hero-selector" id="heroSelector" role="tablist" aria-label="Pilih artefak">
<button class="hs-btn active" data-id="gong" role="tab" aria-selected="true"><i></i><span>GONG</span></button>
<button class="hs-btn" data-id="kenong" role="tab" aria-selected="false"><i></i><span>KENONG</span></button>
<button class="hs-btn" data-id="angklung" role="tab" aria-selected="false"><i></i><span>ANGKLUNG</span></button>
</div>
<button class="hero-play" id="heroPlayBtn" aria-label="Bunyikan Gong">▶</button>
</div>
</div>
</div>
<script type="importmap">
{"imports":{
  "three":"https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js",
  "three/addons/":"https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/"
}}
</script>
<script>
window.HERO_DATA = @json($heroInstruments ?? []);
window.GONG_GLB_URL = "{{ asset('assets/models/gong.glb') }}";
window.KENONG_GLB_URL = "{{ asset('assets/models/kenong.glb') }}";
window.ANGKLUNG_GLB_URL = "{{ asset('assets/models/angklung.glb') }}";
</script>
<script type="module">
import * as THREE from 'three';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
(function(){
// ═══ SINGLE SOURCE OF TRUTH: database records ═══
const DB = window.HERO_DATA || {};
const INSTRUMENTS = {
  gong:   Object.assign({id:'gong',   model:'gong',   viz:'deep'},    DB.gong||{}),
  kenong: Object.assign({id:'kenong', model:'kenong', viz:'pulse'},   DB.kenong||{}),
  angklung:Object.assign({id:'angklung',model:'angklung',viz:'layered'},DB.angklung||{}),
};
Object.values(INSTRUMENTS).forEach(o=>{
  o.name=(o.nama||o.id).toUpperCase();
  o.origin=((o.region||'Nusantara')+(o.sumber?' · '+o.sumber:'')).toUpperCase();
});
let SEL=INSTRUMENTS.gong;
const stage=document.getElementById('heroArtifact'), box=document.getElementById('hero3d');
let renderer;
try{renderer=new THREE.WebGLRenderer({antialias:true,alpha:true});}
catch(e){box.innerHTML='<p style="color:var(--muted);text-align:center">3D tidak didukung</p>';return;}
renderer.setPixelRatio(Math.min(devicePixelRatio,2));
box.appendChild(renderer.domElement);
const scene=new THREE.Scene();
const cam=new THREE.PerspectiveCamera(38,1,.1,50); cam.position.set(0,1.5,5.6); cam.lookAt(0,.5,0);
scene.add(new THREE.AmbientLight(0x9a8878,.75));
const key=new THREE.DirectionalLight(0xffe2b8,1.7); key.position.set(3,6,4); scene.add(key);
const warm=new THREE.PointLight(0xA95135,9,11); warm.position.set(-2.5,1.5,2.5); scene.add(warm);
// Environment untuk refleksi logam (model GLB butuh ini biar perunggu hidup)
try{
  const pmrem=new THREE.PMREMGenerator(renderer);
  const envS=new THREE.Scene(); envS.background=new THREE.Color(0x1a1512);
  const envL=new THREE.PointLight(0xffd9a0,5,0); envL.position.set(2,3,2); envS.add(envL);
  scene.environment=pmrem.fromScene(envS).texture;
}catch(e){}
const M={
  bronze:new THREE.MeshStandardMaterial({color:0x7a6248,roughness:.45,metalness:.65}),
  bronzeD:new THREE.MeshStandardMaterial({color:0x4a382c,roughness:.55,metalness:.5}),
  bronzeL:new THREE.MeshStandardMaterial({color:0x9a7f5e,roughness:.35,metalness:.7}),
  wood:new THREE.MeshStandardMaterial({color:0x4A3423,roughness:.9}),
  bamboo:new THREE.MeshStandardMaterial({color:0x8a7a52,roughness:.85}),
  bambooD:new THREE.MeshStandardMaterial({color:0x6b5f40,roughness:.9}),
  rope:new THREE.MeshStandardMaterial({color:0x3a2e24,roughness:1}),
};
function sh(m){m.castShadow=true;return m;}
// ─── FALLBACK PROSEDURAL: tampil jika GLB gagal load (ketuk untuk retry) ───
function fallbackGong(holder){
  const disc=new THREE.Mesh(new THREE.CylinderGeometry(1.0,1.0,.09,48),M.bronze);
  disc.rotation.x=Math.PI/2; holder.add(sh(disc));
  const boss=new THREE.Mesh(new THREE.SphereGeometry(.24,24,16),M.bronzeL);
  boss.position.z=.12; boss.scale.z=.55; holder.add(sh(boss));
  const rim=new THREE.Mesh(new THREE.TorusGeometry(1.0,.055,12,64),M.bronzeD);
  holder.add(sh(rim));
  const pg=new THREE.CylinderGeometry(.07,.09,2.3,12);
  [-1.18,1.18].forEach(x=>{ const q=new THREE.Mesh(pg,M.wood); q.position.set(x,-.15,0); holder.add(sh(q)); });
  const bar=new THREE.Mesh(new THREE.CylinderGeometry(.055,.055,2.6,12),M.wood);
  bar.rotation.z=Math.PI/2; bar.position.y=1.02; holder.add(sh(bar));
}
function fallbackKenong(holder){
  const body=new THREE.Mesh(new THREE.SphereGeometry(.62,32,24),M.bronze);
  body.scale.set(1,.72,1); holder.add(sh(body));
  const boss=new THREE.Mesh(new THREE.SphereGeometry(.18,20,14),M.bronzeL);
  boss.position.y=.42; boss.scale.y=.6; holder.add(sh(boss));
  const ring=new THREE.Mesh(new THREE.TorusGeometry(.62,.04,10,40),M.bronzeD);
  ring.rotation.x=Math.PI/2; ring.position.y=-.1; holder.add(sh(rim));
  const stand=new THREE.Mesh(new THREE.CylinderGeometry(.5,.62,.3,24),M.wood);
  stand.position.y=-.55; holder.add(sh(stand));
}
function fallbackAngklung(holder){
  const hg=[1.5,1.25,1.0,.8];
  hg.forEach((h,i)=>{
    const x=(i-1.5)*.42;
    const tube=new THREE.Mesh(new THREE.CylinderGeometry(.11,.11,h,16),M.bamboo);
    tube.position.set(x,h/2-.7,0); holder.add(sh(tube));
    const cap=new THREE.Mesh(new THREE.CylinderGeometry(.115,.115,.06,16),M.bambooD);
    cap.position.set(x,h-.7,0); holder.add(sh(cap));
  });
  const frame=new THREE.Mesh(new THREE.BoxGeometry(1.9,.12,.3),M.wood);
  frame.position.y=-.75; holder.add(sh(frame));
  [-.85,.85].forEach(x=>{
    const leg=new THREE.Mesh(new THREE.CylinderGeometry(.05,.06,1.0,10),M.wood);
    leg.position.set(x,-1.2,0); holder.add(sh(leg));
  });
}
let G=null, vib=null, rings=[];
function clearG(){if(G){scene.remove(G);G.traverse(o=>{if(o.userData.keep)return;if(o.geometry)o.geometry.dispose();});}rings.forEach(r=>scene.remove(r.m));rings=[];}
// ─── GONG: model GLB + frame kayu prosedural ───
const gltfLoader=new GLTFLoader();
let gongGLBCache=null;
let gongSeq=0; // anti-double: hanya build terakhir yang boleh pasang model
function buildGong(){
  const mySeq=++gongSeq;
  const alive=()=> mySeq===gongSeq;
  G=new THREE.Group();
  // gong 3D model pengguna — tanpa frame kayu, mengambang di tengah
  const holder=new THREE.Group(); holder.position.y=.6; G.add(holder);
  const place=(model)=>{
    if(!alive()) return;
    while(holder.children.length) holder.remove(holder.children[0]); // holder cuma boleh isi 1
    model.traverse(o=>{ if(o.isMesh){ o.castShadow=true; o.userData.keep=true; } });
    const bb=new THREE.Box3().setFromObject(model);
    const size=new THREE.Vector3(); bb.getSize(size);
    const c=new THREE.Vector3(); bb.getCenter(c);
    model.position.sub(c);
    model.scale.setScalar(2.25/Math.max(size.x,size.y));
    holder.add(model);
  };
  const statusEl=document.getElementById('gongStatus');
  const spinEl=document.getElementById('gongSpin');
  const errEl=document.getElementById('gongErr');
  const setStatus=(mode,retry)=>{
    if(!statusEl) return;
    statusEl.style.display=mode?'flex':'none';
    if(spinEl) spinEl.style.display=mode==='loading'?'block':'none';
    if(errEl){ errEl.style.display=mode==='error'?'block':'none'; if(mode==='error') errEl.textContent='Mode cadangan — ketuk untuk muat model 3D'; }
    statusEl.style.pointerEvents=retry?'auto':'none';
    statusEl.style.cursor=retry?'pointer':'default';
    statusEl.onclick=retry?()=>{ if(alive()){ setStatus('loading',false); attempt(); } }:null;
  };
  const attempt=()=>{
    gltfLoader.load(window.GONG_GLB_URL,
      (glb)=>{ if(!alive()) return; gongGLBCache=glb.scene;
        try{ place(glb.scene); setStatus('',false); }
        catch(e){ if(!alive())return; fallbackGong(holder); setStatus('error',true); } },
      undefined,
      ()=>{ if(!alive()) return; fallbackGong(holder); setStatus('error',true); });
  };
  if(gongGLBCache){ place(gongGLBCache.clone()); setStatus('',false); }
  else{ setStatus('loading',false); attempt(); }
  vib=t=>{ holder.position.z=Math.sin(t*30)*.025; };
}
// ─── KENONG: vessel bundar + boss, di atas pangkon silang ───
let kenongGLBCache=null;
let kenongSeq=0;
function buildKenong(){
  const mySeq=++kenongSeq;
  const alive=()=> mySeq===kenongSeq;
  G=new THREE.Group();
  // kenong 3D model pengguna — tanpa frame, mengambang di tengah (seperti gong)
  const holder=new THREE.Group(); holder.position.y=.6; G.add(holder);
  const place=(model)=>{
    if(!alive()) return;
    while(holder.children.length) holder.remove(holder.children[0]);
    model.traverse(o=>{ if(o.isMesh){ o.castShadow=true; o.userData.keep=true; } });
    const bb=new THREE.Box3().setFromObject(model);
    const size=new THREE.Vector3(); bb.getSize(size);
    const c=new THREE.Vector3(); bb.getCenter(c);
    model.position.sub(c);
    model.scale.setScalar(1.9/Math.max(size.x,size.y,size.z));
    holder.add(model);
  };
  const statusEl=document.getElementById('gongStatus');
  const spinEl=document.getElementById('gongSpin');
  const errEl=document.getElementById('gongErr');
  const setStatus=(mode,retry)=>{
    if(!statusEl) return;
    statusEl.style.display=mode?'flex':'none';
    if(spinEl) spinEl.style.display=mode==='loading'?'block':'none';
    if(errEl){ errEl.style.display=mode==='error'?'block':'none'; if(mode==='error') errEl.textContent='Mode cadangan — ketuk untuk muat model 3D'; }
    statusEl.style.pointerEvents=retry?'auto':'none';
    statusEl.style.cursor=retry?'pointer':'default';
    statusEl.onclick=retry?()=>{ if(alive()){ setStatus('loading',false); attempt(); } }:null;
  };
  const attempt=()=>{
    gltfLoader.load(window.KENONG_GLB_URL,
      (glb)=>{ if(!alive()) return; kenongGLBCache=glb.scene;
        try{ place(glb.scene); setStatus('',false); }
        catch(e){ if(!alive())return; fallbackKenong(holder); setStatus('error',true); } },
      undefined,
      ()=>{ if(!alive()) return; fallbackKenong(holder); setStatus('error',true); });
  };
  if(kenongGLBCache){ place(kenongGLBCache.clone()); setStatus('',false); }
  else{ setStatus('loading',false); attempt(); }
  vib=t=>{ holder.position.z=Math.sin(t*36)*.012; };
}
// ─── ANGKLUNG: model 3D pengguna ───
let angklungGLBCache=null;
let angklungSeq=0;
function buildAngklung(){
  const mySeq=++angklungSeq;
  const alive=()=> mySeq===angklungSeq;
  G=new THREE.Group();
  // angklung 3D model pengguna — tanpa frame, mengambang di tengah (seperti gong)
  const holder=new THREE.Group(); holder.position.y=.6; G.add(holder);
  const place=(model)=>{
    if(!alive()) return;
    while(holder.children.length) holder.remove(holder.children[0]);
    model.traverse(o=>{ if(o.isMesh){ o.castShadow=true; o.userData.keep=true; } });
    const bb=new THREE.Box3().setFromObject(model);
    const size=new THREE.Vector3(); bb.getSize(size);
    const c=new THREE.Vector3(); bb.getCenter(c);
    model.position.sub(c);
    model.scale.setScalar(1.9/Math.max(size.x,size.y,size.z));
    holder.add(model);
  };
  const statusEl=document.getElementById('gongStatus');
  const spinEl=document.getElementById('gongSpin');
  const errEl=document.getElementById('gongErr');
  const setStatus=(mode,retry)=>{
    if(!statusEl) return;
    statusEl.style.display=mode?'flex':'none';
    if(spinEl) spinEl.style.display=mode==='loading'?'block':'none';
    if(errEl){ errEl.style.display=mode==='error'?'block':'none'; if(mode==='error') errEl.textContent='Mode cadangan — ketuk untuk muat model 3D'; }
    statusEl.style.pointerEvents=retry?'auto':'none';
    statusEl.style.cursor=retry?'pointer':'default';
    statusEl.onclick=retry?()=>{ if(alive()){ setStatus('loading',false); attempt(); } }:null;
  };
  const attempt=()=>{
    gltfLoader.load(window.ANGKLUNG_GLB_URL,
      (glb)=>{ if(!alive()) return; angklungGLBCache=glb.scene;
        try{ place(glb.scene); setStatus('',false); }
        catch(e){ if(!alive())return; fallbackAngklung(holder); setStatus('error',true); } },
      undefined,
      ()=>{ if(!alive()) return; fallbackAngklung(holder); setStatus('error',true); });
  };
  if(angklungGLBCache){ place(angklungGLBCache.clone()); setStatus('',false); }
  else{ setStatus('loading',false); attempt(); }
  vib=t=>{ holder.rotation.z=Math.sin(t*24)*.02; };
}
function select(id){
  stopAllAudio(); stop();
  SEL=INSTRUMENTS[id]||INSTRUMENTS.gong; clearG();
  ({gong:buildGong,kenong:buildKenong,angklung:buildAngklung}[SEL.id])();
  scene.add(G);
  G.scale.setScalar(.01); // transisi masuk
  userRY=0;userRX=0;velY=0;G.rotation.set(0,0,0);
  document.getElementById('heroName').textContent=SEL.name;
  document.getElementById('heroOrigin').textContent=SEL.origin;
  document.getElementById('heroPlayBtn').setAttribute('aria-label','Bunyikan '+SEL.name);
  document.querySelectorAll('.hs-btn').forEach(b=>{
    const on=b.dataset.id===SEL.id;
    b.classList.toggle('active',on); b.setAttribute('aria-selected',on);
  });
  refreshPlayBtn();
}
// Audio: REAL database audio — satu <audio> per instrumen
const audioEls={};
Object.values(INSTRUMENTS).forEach(o=>{
  if(o.audio){
    const a=document.createElement('audio');
    a.src=o.audio; a.preload='none';
    audioEls[o.id]=a;
    a.addEventListener('ended',()=>{ if(SEL.id===o.id) stop(); });
  }
});
function stopAllAudio(){ Object.values(audioEls).forEach(a=>{a.pause();a.currentTime=0;}); }
function refreshPlayBtn(){
  const pb=document.getElementById('heroPlayBtn');
  if(!pb)return;
  if(!audioEls[SEL.id]){ pb.disabled=true; pb.style.opacity='.35'; pb.title='Audio belum tersedia'; }
  else{ pb.disabled=false; pb.style.opacity=''; pb.title=''; }
}
// Visual reaksi per instrumen
function ripple(){
  const conf={deep:{n:3,col:0xA95135,w:.05,spd:1.6},pulse:{n:2,col:0xC97B4A,w:.07,spd:1.0},layered:{n:4,col:0x8a7a52,w:.03,spd:.8}}[SEL.viz];
  for(let i=0;i<conf.n;i++){
    const r=new THREE.Mesh(new THREE.RingGeometry(.55,.55+conf.w,44),
      new THREE.MeshBasicMaterial({color:conf.col,transparent:true,opacity:.8,side:THREE.DoubleSide}));
    r.position.y=.55; r.rotation.x=0; scene.add(r);
    rings.push({m:r,t:-i*.18,spd:conf.spd});
  }
}
let sounding=false, vibT=0;
function play(){
  const a=audioEls[SEL.id];
  if(!a) return; // tidak ada audio DB: jangan substitusi bunyi lain
  stopAllAudio();
  a.currentTime=0;
  a.play().catch(()=>{});
  ripple();
  sounding=true; vibT=0; stage.classList.add('sounding');
  const pb=document.getElementById('heroPlayBtn');
  if(pb){pb.classList.add('playing');pb.innerHTML='&#10074;&#10074;';}
  if(window.mpShow) mpShow(SEL.name.charAt(0)+SEL.name.slice(1).toLowerCase(),'Artefak · '+SEL.origin);
  const st=document.getElementById('soundStatus');
  if(st) st.textContent='\u266A '+SEL.name+' \u00B7 '+SEL.origin;
  // Hero preview: maksimal 2 detik
  clearTimeout(stage._cap);
  stage._cap=setTimeout(()=>{ a.pause(); a.currentTime=0; stop(); }, 2000);
}
function stop(){
  clearTimeout(stage._cap);
  sounding=false; stage.classList.remove('sounding');
  const pb=document.getElementById('heroPlayBtn');
  if(pb){pb.classList.remove('playing');pb.innerHTML='&#9654;';}
}
box.addEventListener('click',play);
box.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();play();}});
document.getElementById('heroPlayBtn').addEventListener('click',play);
document.getElementById('heroSelector').addEventListener('click',e=>{
  const b=e.target.closest('.hs-btn'); if(b) select(b.dataset.id);
});
// Manual drag rotation — artefak museum yang bisa diputar tangan
let dragging=false, lastX=0, lastY=0, velY=0, dragRX=0, userRY=0, userRX=0;
const reduced=matchMedia('(prefers-reduced-motion: reduce)').matches;
function dragStart(x,y){dragging=true;lastX=x;lastY=y;velY=0;}
function dragMove(x,y){
  if(!dragging||!G)return;
  const dx=x-lastX, dy=y-lastY; lastX=x; lastY=y;
  userRY+=dx*.008; userRX=Math.max(-.35,Math.min(.35,userRX+dy*.004));
  velY=dx*.008;
  G.rotation.y=userRY; G.rotation.x=userRX;
}
function dragEnd(){dragging=false;}
box.addEventListener('pointerdown',e=>{box.setPointerCapture(e.pointerId);dragStart(e.clientX,e.clientY);});
box.addEventListener('pointermove',e=>dragMove(e.clientX,e.clientY));
box.addEventListener('pointerup',dragEnd);
box.addEventListener('pointercancel',dragEnd);
// Cegah drag = klik play: hanya play jika tidak bergerak
let downPos=null;
box.addEventListener('pointerdown',e=>{downPos=[e.clientX,e.clientY];});
box.addEventListener('click',e=>{
  if(downPos && Math.hypot(e.clientX-downPos[0],e.clientY-downPos[1])>8){e.stopImmediatePropagation();return;}
},true);
function rs(){const w=box.clientWidth,h=box.clientHeight;renderer.setSize(w,h);cam.aspect=w/h;cam.updateProjectionMatrix();}
new ResizeObserver(rs).observe(box); rs();
select('gong');
// preload kenong & angklung langsung (bytes-nya udah di-preload via <link>, tinggal parse)
if(!kenongGLBCache) gltfLoader.load(window.KENONG_GLB_URL,(glb)=>{kenongGLBCache=glb.scene;},undefined,()=>{});
if(!angklungGLBCache) gltfLoader.load(window.ANGKLUNG_GLB_URL,(glb)=>{angklungGLBCache=glb.scene;},undefined,()=>{});
refreshPlayBtn();
const clk=new THREE.Clock();
(function loop(){
  requestAnimationFrame(loop);
  const t=clk.getElapsedTime();
  if(G){
    if(G.scale.x<1)G.scale.setScalar(Math.min(1,G.scale.x+.03)); // transisi masuk
    if(sounding){vibT+=.016;if(vib)vib(vibT);}
    else if(!dragging && !reduced){
      // idle: micro-rotation + breathing, settle setelah drag
      if(Math.abs(velY)>.0002){ userRY+=velY; velY*=.95; G.rotation.y=userRY; }
      else { userRY+=Math.sin(t*.18)*.0009; G.rotation.y=userRY; }
      G.position.y=Math.sin(t*.5)*.02; // breathing
      warm.intensity=9+Math.sin(t*.4)*1.2; // cahaya hidup
    }
  }
  for(let i=rings.length-1;i>=0;i--){const r=rings[i];r.t+=.016;if(r.t<0)continue;
    const k=r.t/r.spd;
    if(k>=1){scene.remove(r.m);r.m.geometry.dispose();r.m.material.dispose();rings.splice(i,1);continue;}
    const s=1+k*2.8;r.m.scale.set(s,s,1);r.m.material.opacity=.8*(1-k);}
  renderer.render(scene,cam);
})();
})();
</script>

{{-- 01 ARSIP BUNYI --}}
<section class="sect" id="arsip">
<div class="arsip-bg" data-pxsec="0.35" aria-hidden="true"><div class="wave w1"><i></i></div><div class="wave w2"><i></i></div><div class="wave w3"><i></i></div></div>
<div class="wrap">
<div class="sect-label sec-enter"><span class="n">01 / ARSIP</span><span class="ax-mask"><span>Musantara</span></span><span class="ln"></span></div>
<div class="sec-enter d1" style="text-align:center;max-width:640px;margin:0 auto 2.5rem">
<p style="font-family:var(--mono);font-size:.62rem;letter-spacing:.24em;color:var(--terra);margin-bottom:1rem">GAMELAN INTERAKTIF</p>
<h2 style="font-family:var(--display);font-size:clamp(2.2rem,5vw,3.4rem);font-weight:600;line-height:1.1;margin-bottom:1rem">Sentuh &<br>Dengarkan</h2>
<p style="color:var(--muted);font-size:1.02rem;line-height:1.7">Setiap bilah dan gong di bawah ini bisa diketuk — persis seperti memainkan gamelan asli. Coba ketuk satu per satu.</p>
</div>
<div class="gamelan gamelan-sec sec-enter d2">
<div class="gamelan-stage" aria-hidden="true"><div class="gs-top"></div><div class="gs-body"></div></div>
@php
$rb_svg = [
'gong' => '<svg viewBox="0 0 100 100" class="rb-svg"><circle cx="50" cy="50" r="40" fill="#7a6248" stroke="#3a2e24" stroke-width="3"/><circle cx="50" cy="50" r="32" fill="none" stroke="#3a2e24" stroke-width="2"/><circle cx="50" cy="50" r="13" fill="#9a7f5e" stroke="#3a2e24" stroke-width="2.5"/><ellipse cx="44" cy="44" rx="5" ry="3.5" fill="#c9b896" opacity=".55"/></svg>',
'kempul' => '<svg viewBox="0 0 100 100" class="rb-svg"><circle cx="50" cy="50" r="30" fill="#7a6248" stroke="#3a2e24" stroke-width="3"/><circle cx="50" cy="50" r="23" fill="none" stroke="#3a2e24" stroke-width="2"/><circle cx="50" cy="50" r="10" fill="#9a7f5e" stroke="#3a2e24" stroke-width="2.5"/><ellipse cx="45" cy="45" rx="4" ry="2.8" fill="#c9b896" opacity=".55"/></svg>',
'kenong' => '<svg viewBox="0 0 100 100" class="rb-svg"><path d="M22 62 Q50 78 78 62 L70 40 Q50 50 30 40 Z" fill="#7a6248" stroke="#3a2e24" stroke-width="3"/><ellipse cx="50" cy="40" rx="20" ry="9" fill="#9a7f5e" stroke="#3a2e24" stroke-width="2.5"/><circle cx="50" cy="38" r="5" fill="#c9b896" opacity=".6"/></svg>',
'saron' => '<svg viewBox="0 0 100 100" class="rb-svg"><rect x="14" y="62" width="72" height="16" rx="4" fill="#4A3423" stroke="#2a1f16" stroke-width="2.5"/><rect x="20.0" y="48" width="9" height="12" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/><rect x="31.5" y="48" width="9" height="12" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/><rect x="43.0" y="48" width="9" height="12" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/><rect x="54.5" y="48" width="9" height="12" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/><rect x="66.0" y="48" width="9" height="12" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/><rect x="77.5" y="48" width="9" height="12" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/></svg>',
'bonang' => '<svg viewBox="0 0 100 100" class="rb-svg"><rect x="12" y="66" width="76" height="10" rx="3" fill="#4A3423" stroke="#2a1f16" stroke-width="2"/><rect x="12" y="40" width="76" height="10" rx="3" fill="#4A3423" stroke="#2a1f16" stroke-width="2"/><circle cx="18.0" cy="35" r="5.5" fill="#7a6248" stroke="#3a2e24" stroke-width="1.8"/><circle cx="18.0" cy="33.5" r="2" fill="#c9b896" opacity=".5"/><circle cx="31.5" cy="35" r="5.5" fill="#7a6248" stroke="#3a2e24" stroke-width="1.8"/><circle cx="31.5" cy="33.5" r="2" fill="#c9b896" opacity=".5"/><circle cx="45.0" cy="35" r="5.5" fill="#7a6248" stroke="#3a2e24" stroke-width="1.8"/><circle cx="45.0" cy="33.5" r="2" fill="#c9b896" opacity=".5"/><circle cx="58.5" cy="35" r="5.5" fill="#7a6248" stroke="#3a2e24" stroke-width="1.8"/><circle cx="58.5" cy="33.5" r="2" fill="#c9b896" opacity=".5"/><circle cx="72.0" cy="35" r="5.5" fill="#7a6248" stroke="#3a2e24" stroke-width="1.8"/><circle cx="72.0" cy="33.5" r="2" fill="#c9b896" opacity=".5"/><circle cx="18.0" cy="61" r="5.5" fill="#7a6248" stroke="#3a2e24" stroke-width="1.8"/><circle cx="18.0" cy="59.5" r="2" fill="#c9b896" opacity=".5"/><circle cx="31.5" cy="61" r="5.5" fill="#7a6248" stroke="#3a2e24" stroke-width="1.8"/><circle cx="31.5" cy="59.5" r="2" fill="#c9b896" opacity=".5"/><circle cx="45.0" cy="61" r="5.5" fill="#7a6248" stroke="#3a2e24" stroke-width="1.8"/><circle cx="45.0" cy="59.5" r="2" fill="#c9b896" opacity=".5"/><circle cx="58.5" cy="61" r="5.5" fill="#7a6248" stroke="#3a2e24" stroke-width="1.8"/><circle cx="58.5" cy="59.5" r="2" fill="#c9b896" opacity=".5"/><circle cx="72.0" cy="61" r="5.5" fill="#7a6248" stroke="#3a2e24" stroke-width="1.8"/><circle cx="72.0" cy="59.5" r="2" fill="#c9b896" opacity=".5"/></svg>',
'gambang' => '<svg viewBox="0 0 100 100" class="rb-svg"><rect x="14.0" y="34" width="8" height="34" rx="2" fill="#8a7a52" stroke="#3a2e24" stroke-width="1.8"/><rect x="24.5" y="37" width="8" height="31" rx="2" fill="#8a7a52" stroke="#3a2e24" stroke-width="1.8"/><rect x="35.0" y="40" width="8" height="28" rx="2" fill="#8a7a52" stroke="#3a2e24" stroke-width="1.8"/><rect x="45.5" y="43" width="8" height="25" rx="2" fill="#8a7a52" stroke="#3a2e24" stroke-width="1.8"/><rect x="56.0" y="46" width="8" height="22" rx="2" fill="#8a7a52" stroke="#3a2e24" stroke-width="1.8"/><rect x="66.5" y="49" width="8" height="19" rx="2" fill="#8a7a52" stroke="#3a2e24" stroke-width="1.8"/><rect x="77.0" y="52" width="8" height="16" rx="2" fill="#8a7a52" stroke="#3a2e24" stroke-width="1.8"/><rect x="10" y="70" width="80" height="8" rx="3" fill="#4A3423" stroke="#2a1f16" stroke-width="2"/></svg>',
'demung' => '<svg viewBox="0 0 100 100" class="rb-svg"><rect x="10" y="60" width="80" height="18" rx="4" fill="#4A3423" stroke="#2a1f16" stroke-width="2.5"/><rect x="16" y="44" width="9" height="14" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/><rect x="27" y="44" width="9" height="14" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/><rect x="38" y="44" width="9" height="14" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/><rect x="49" y="44" width="9" height="14" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/><rect x="60" y="44" width="9" height="14" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/><rect x="71" y="44" width="9" height="14" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/><rect x="82" y="44" width="9" height="14" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/></svg>',
'peking' => '<svg viewBox="0 0 100 100" class="rb-svg"><rect x="20" y="64" width="60" height="14" rx="4" fill="#4A3423" stroke="#2a1f16" stroke-width="2.5"/><rect x="25" y="50" width="8" height="12" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/><rect x="36" y="50" width="8" height="12" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/><rect x="47" y="50" width="8" height="12" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/><rect x="58" y="50" width="8" height="12" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/><rect x="69" y="50" width="8" height="12" rx="2" fill="#8a6f52" stroke="#3a2e24" stroke-width="1.8"/></svg>',
];
$rb_freq = ['gong'=>98,'kempul'=>147,'kenong'=>220,'saron'=>392,'bonang'=>523.25,'gambang'=>659.25,'demung'=>174.61,'peking'=>880];
@endphp
<div class="gamelan-pads">
@foreach($ruangBunyi as $kw => $rb)
<button type="button" class="gamelan-pad rb-pad" data-instrument="{{ $kw }}" data-freq="{{ $rb_freq[$kw] ?? 220 }}" data-audio="{{ $rb['audio'] ?? '' }}" aria-label="Mainkan {{ $rb['nama'] }}">
<span class="res-ring" aria-hidden="true"></span>
{!! $rb_svg[$kw] ?? $rb_svg['gong'] !!}
<b>{{ $rb['nama'] }}</b>
</button>
@endforeach
</div>
<p class="gamelan-status" id="soundStatus" aria-live="polite">KETUK OBJEK UNTUK MENDENGAR</p>
</div>
</div>
</section>

{{-- 02 PULAU --}}
<section class="map-sec" id="pulau">
<div class="wrap">
<div class="sect-label sec-enter"><span class="n">02 / PULAU</span><span class="ax-mask"><span>Kepulauan</span></span><span class="ln"></span></div>
<p class="sec-enter d1" style="font-family:var(--mono);font-size:.62rem;letter-spacing:.2em;color:var(--muted);margin:-1rem 0 1.2rem">KETUK PULAU UNTUK LIHAT INSTRUMENNYA</p>
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
<div class="sect-label sec-enter"><span class="n">03 / ARSIP</span><span class="ax-mask"><span>Koleksi</span></span><span class="ln"></span></div>
@foreach($regions as $ri => $rg)
<div class="sect-label" style="margin:2.4rem 0 1.6rem"><span class="n">{{ $rg['pulau']->nama }}</span><span class="ax-mask"><span style="font-size:1rem">{{ $rg['items']->count() }} objek</span></span><span class="ln"></span></div>
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
<div class="sect-label sec-enter"><span class="n">04 / KUIS</span><span class="ax-mask"><span>Uji Telinga</span></span><span class="ln"></span></div>
<div class="quiz-card sec-enter d1" style="max-width:560px;margin:0 auto">
<p style="font-family:var(--mono);font-size:.62rem;letter-spacing:.24em;color:var(--terra);margin-bottom:.8rem">SEMBILAN SOAL</p>
<p style="font-size:1.05rem;color:var(--muted);margin-bottom:1.6rem">Dengarkan bunyinya, tebak instrumennya. Sepuluh soal singkat tentang Nusantara.</p>
<a href="{{ url('/quiz-global') }}" class="clay-btn primary">Mulai Kuis <span class="arw">→</span></a>
</div>
</div>
</section>

<script>
document.addEventListener('DOMContentLoaded',function(){
  var io=new IntersectionObserver(function(es){
    es.forEach(function(e){
      if(e.isIntersecting){ e.target.classList.add('inview'); }
      else { e.target.classList.remove('inview'); }
    });
  },{threshold:.15});
  var els=document.querySelectorAll('.sec-enter');
  els.forEach(function(el){ io.observe(el); });
  // fallback: kalau IO tidak support, tampilkan semua
  if(!('IntersectionObserver' in window)){ els.forEach(function(el){ el.classList.add('inview'); }); }
});
</script>
<script>
// Parallax hero simpel: judul jalan sedikit lebih cepat dari artefak (depth halus).
// Hanya transform GPU, mati otomatis jika prefers-reduced-motion.
(function(){
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  var els = Array.prototype.slice.call(document.querySelectorAll('[data-px]'));
  var sels = Array.prototype.slice.call(document.querySelectorAll('[data-pxsec]'));
  if (!els.length && !sels.length) return;
  var ticking = false;
  function update(){
    var y = window.scrollY || window.pageYOffset;
    var vh = window.innerHeight;
    var i, el, sp;
    // hero: berbasis scrollY absolut
    if (y < vh * 1.2) {
      for (i = 0; i < els.length; i++) {
        el = els[i];
        sp = parseFloat(el.getAttribute('data-px')) || 0;
        el.style.transform = 'translate3d(0,' + (y * sp).toFixed(1) + 'px,0)';
      }
    }
    // section: relatif posisi section di viewport (bg jalan lebih lambat)
    for (i = 0; i < sels.length; i++) {
      el = sels[i];
      sp = parseFloat(el.getAttribute('data-pxsec')) || 0;
      var r = el.parentElement.getBoundingClientRect();
      if (r.bottom > 0 && r.top < vh) {
        var prog = (vh - r.top) / (vh + r.height); // 0..1 selama section terlihat
        var off = (prog - 0.5) * 140 * sp;
        el.style.transform = 'translate3d(0,' + off.toFixed(1) + 'px,0)';
      }
    }
    ticking = false;
  }
  window.addEventListener('scroll', function(){
    if (!ticking) { requestAnimationFrame(update); ticking = true; }
  }, {passive:true});
  update();
})();
</script>
@endsection