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
#arsip{position:relative;z-index:1}
.arsip-bg{position:sticky;top:0;height:100vh;height:100svh;overflow:hidden;pointer-events:none;z-index:0;margin-bottom:-100vh;margin-bottom:-100svh}
/* section sesudah #arsip menimpa bg sticky */
#pulau,#koleksi,#kuis,footer{position:relative;z-index:2;background-color:var(--bg0);
  background-image:radial-gradient(1200px 600px at 70% -10%,rgba(169,81,53,.07),transparent 60%),radial-gradient(900px 500px at 10% 110%,rgba(98,72,58,.09),transparent 60%)}
.sect .wrap{position:relative;z-index:1}
.wave{position:absolute;border-radius:50%;border:2px solid rgba(181,80,47,.28);box-shadow:0 0 60px rgba(181,80,47,.08) inset}
.wave.w1{width:560px;height:560px;left:-140px;top:4%;animation:waveDrift 26s ease-in-out infinite alternate}
.wave.w2{width:820px;height:820px;right:-200px;top:28%;animation:waveDrift 34s ease-in-out infinite alternate-reverse}
.wave.w3{width:420px;height:420px;left:34%;bottom:-120px;animation:waveDrift 22s ease-in-out infinite alternate}
.wave i{position:absolute;inset:16%;border-radius:50%;border:2px solid rgba(181,80,47,.2)}
.wave b{position:absolute;inset:34%;border-radius:50%;border:1px solid rgba(217,138,95,.22)}
@keyframes waveDrift{from{transform:translate3d(0,0,0) scale(1)}to{transform:translate3d(0,-56px,0) scale(1.09)}}
@media(prefers-reduced-motion:reduce){.wave{animation:none}}

/* Back to top */
#toTop{position:fixed;right:1.1rem;bottom:1.4rem;z-index:60;width:46px;height:46px;border-radius:50%;
border:1px solid rgba(181,80,47,.35);background:rgba(43,38,34,.88);color:#ede6d6;font-size:1.15rem;line-height:1;
cursor:pointer;opacity:0;transform:translateY(12px);pointer-events:none;transition:opacity .35s,transform .35s;backdrop-filter:blur(4px)}
#toTop.show{opacity:1;transform:none;pointer-events:auto}
#toTop:active{transform:scale(.92)}
#jelajahi{background:radial-gradient(720px 480px at 22% 18%,rgba(169,110,70,.08),transparent 65%),#141110}
#arsip{background:radial-gradient(640px 460px at 78% 30%,rgba(140,95,60,.08),transparent 65%),linear-gradient(180deg,#171310,#141110);background-attachment:fixed}
#pulau{background:radial-gradient(800px 500px at 50% 70%,rgba(110,80,55,.07),transparent 60%),linear-gradient(180deg,#141110,#201915);background-attachment:fixed}
#koleksi{background:radial-gradient(700px 480px at 25% 60%,rgba(169,110,70,.07),transparent 65%),linear-gradient(180deg,#201915,#141110);background-attachment:fixed}
#kuis{background:radial-gradient(640px 460px at 75% 25%,rgba(140,95,60,.08),transparent 65%),linear-gradient(180deg,#141110,#100d0b);background-attachment:fixed}
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
/* v253: galeri horizontal desktop / snap strip mobile */
/* v262: pin sticky 1 layar, scroll bawah -> geser kiri */
#koleksi{padding-top:0;padding-bottom:0}
.hgal{position:relative}
.hgal-pin{position:sticky;top:4vh;top:4svh;height:92vh;height:92svh;overflow:hidden;display:flex;align-items:center;max-width:min(1280px,96vw);margin:0 auto;background:linear-gradient(180deg,#201915,#181310);border:1px solid rgba(229,214,192,.14);border-radius:28px;box-shadow:0 30px 80px rgba(0,0,0,.5),inset 0 1px 0 rgba(229,214,192,.09)}
.hgal-track{display:flex;align-items:center;width:max-content;padding-left:7vw}
.hgal-panel{flex:0 0 auto;padding-right:6vw}
.hgal-intro{width:min(400px,80vw)}
.hgal-lead{opacity:.72;line-height:1.7;margin:1rem 0 0}
.hgal-plabel{margin:0 0 1.4rem}
.hgal-cards{display:flex;gap:1.4rem}
.hgal-cards .pod{width:min(280px,68vw);flex:0 0 auto}
.hgal-outro{display:flex;align-items:center}
@media(max-width:860px){
  .hgal-track{padding-left:6vw}
  .hgal-panel{padding-right:8vw}
  .hgal-intro{width:80vw}
  .hgal-cards{gap:1rem}
  .hgal-cards .pod{width:min(240px,62vw)}
  .wave.w1{width:340px;height:340px;left:-90px;top:6%}
  .wave.w2{width:480px;height:480px;right:-140px;top:32%}
  .wave.w3{width:260px;height:260px;left:30%;bottom:-80px}
}



/* ——— KUIS PASSAGE v3: scrub nempel scroll + gelombang bunyi ——— */
#kuis{overflow:hidden}
.kuis-glow{position:absolute;inset:-20% -10%;pointer-events:none;z-index:0;
  background:radial-gradient(640px 460px at 75% 25%,rgba(140,95,60,.13),transparent 65%);
  will-change:transform}
.kuis-rings{position:absolute;inset:0;z-index:0;pointer-events:none;display:flex;align-items:center;justify-content:center}
.kuis-rings span{position:absolute;border-radius:50%;border:1.5px solid rgba(169,81,53,.45);
  animation:kring 5.5s ease-out infinite}
.kuis-rings span:nth-child(1){width:340px;height:340px}
.kuis-rings span:nth-child(2){width:520px;height:520px;animation-delay:1.8s}
.kuis-rings span:nth-child(3){width:700px;height:700px;animation-delay:3.6s}
@keyframes kring{0%{transform:scale(.72);opacity:0}25%{opacity:.55}100%{transform:scale(1.12);opacity:0}}
#kuis .wrap{position:relative;z-index:1}
#kuis.k-live .sect-label .n,#kuis.k-live .sect-label .ax-mask{display:inline-block}
#kuis.k-live .sect-label .ln{display:block;transform-origin:left}
#kuis.k-live .sect-label .n,#kuis.k-live .sect-label .ax-mask,
#kuis.k-live .sect-label .ln,#kuis.k-live .quiz-card{transition:none!important;will-change:transform,opacity}
#kuis .quiz-card{position:relative;overflow:hidden}
#kuis .quiz-card>*:not(.quiz-glare):not(.quiz-ripple){position:relative;z-index:1}
.quiz-glare{position:absolute;inset:0;z-index:0;pointer-events:none;opacity:var(--gop,0);
  background:radial-gradient(var(--gsize,420px) circle at var(--gx,50%) var(--gy,50%),rgba(229,214,192,var(--galpha,.16)),transparent 60%)}
#kuis .quiz-card:hover{--gop:1}
.quiz-ripple{position:absolute;z-index:0;pointer-events:none;border-radius:50%;
  border:2px solid rgba(169,81,53,.55);transform:translate(-50%,-50%) scale(0);animation:qrip .9s ease-out forwards}
@keyframes qrip{to{transform:translate(-50%,-50%) scale(1);opacity:0}}
.kuis-strip{overflow:hidden;width:100%;margin-bottom:2.4rem;
  -webkit-mask-image:linear-gradient(90deg,transparent,#000 12%,#000 88%,transparent);
  mask-image:linear-gradient(90deg,transparent,#000 12%,#000 88%,transparent)}
.kuis-strip-in{display:flex;width:max-content;animation:stripMove 32s linear infinite;white-space:nowrap}
.strip-set{font-family:var(--mono);font-size:.68rem;letter-spacing:.3em;color:rgba(229,214,192,.45)}
.strip-set span{margin-right:2.4rem}
.strip-set i{color:var(--terra);font-style:normal;margin-right:2.4rem}
@keyframes stripMove{to{transform:translateX(-50%)}}
.mini-quiz{margin:0 auto 1.5rem;max-width:400px}
.mini-img{width:100%;height:170px;object-fit:cover;border-radius:14px;display:block;box-shadow:0 8px 20px rgba(0,0,0,.35)}
.mini-q{font-family:var(--mono);font-size:.68rem;letter-spacing:.22em;color:var(--terra);margin:.95rem 0 .65rem}
.mini-opts{display:flex;flex-direction:column;gap:.45rem}
.mini-opt{display:block;width:100%;text-align:left;background:linear-gradient(145deg,var(--clay2),var(--clay1));color:var(--cream);border:none;border-radius:12px;padding:.65rem 1rem;font-size:.92rem;cursor:pointer;box-shadow:0 2px 4px rgba(0,0,0,.4),inset 0 1px 0 rgba(229,214,192,.1);transition:transform .15s ease,background .25s ease;min-height:42px;font-family:inherit}
.mini-opt:hover:not(:disabled){transform:translateY(-2px)}
.mini-opt:disabled{cursor:default;opacity:.7}
.mini-opt.ok{background:linear-gradient(145deg,#5A6B52,var(--green));color:#fff;opacity:1}
.mini-opt.no{background:linear-gradient(145deg,#7a3a2a,#5e2c1f);color:#fff;opacity:1}
.mini-fb{margin-top:.85rem;font-size:.95rem;color:var(--cream)}
.kuis-meta{margin-top:1.05rem;font-family:var(--mono);font-size:.6rem;letter-spacing:.18em;color:var(--muted)}
#kuisStartBtn.attn{animation:btnPulse .55s ease 2}
@keyframes btnPulse{50%{transform:scale(1.07)}}
@media(prefers-reduced-motion:reduce){.kuis-strip-in{animation:none}#kuisStartBtn.attn{animation:none}}
@media(prefers-reduced-motion:reduce){.kuis-rings{display:none}.kuis-glow{display:none}}
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
const pingHero=()=>{ try{ if(window.__msnHeroPing) window.__msnHeroPing(); }catch(e){} };
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
        try{ place(glb.scene); setStatus('',false); pingHero(); }
        catch(e){ if(!alive())return; fallbackGong(holder); setStatus('error',true); pingHero(); } },
      undefined,
      ()=>{ if(!alive()) return; fallbackGong(holder); setStatus('error',true); pingHero(); });
  };
  if(gongGLBCache){ place(gongGLBCache.clone()); setStatus('',false); pingHero(); }
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
<div class="arsip-bg" aria-hidden="true"><div class="wave w1"><i></i><b></b></div><div class="wave w2"><i></i><b></b></div><div class="wave w3"><i></i><b></b></div></div>
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

{{-- 03 KOLEKSI — galeri horizontal desktop / snap strip mobile (v253) --}}
<section class="sect" id="koleksi">
<div class="hgal" id="hgal">
<div class="hgal-pin">
<div class="hgal-track">
<div class="hgal-panel hgal-intro">
<div class="sect-label"><span class="n">03 / ARSIP</span><span class="ax-mask"><span>Koleksi</span></span><span class="ln"></span></div>
<p class="hgal-lead">Gulir ke bawah — koleksi berjalan ke samping, dari ujung ke ujung Nusantara.</p>
</div>
@foreach($regions as $ri => $rg)
<div class="hgal-panel">
<div class="sect-label hgal-plabel"><span class="n">{{ $rg['pulau']->nama }}</span><span class="ax-mask"><span style="font-size:1rem">{{ $rg['items']->count() }} objek</span></span><span class="ln"></span></div>
<div class="hgal-cards">
@foreach($rg['items'] as $idx => $item)
@include('components.card',['item'=>$item,'idx'=>$idx])
@endforeach
</div>
</div>
@endforeach
<div class="hgal-panel hgal-outro">
<a href="{{ url('/search') }}" class="clay-btn primary">Lihat Semua Arsip <span class="arw">→</span></a>
</div>
</div>
</div>
</div>
</section>

{{-- 04 KUIS --}}
<section class="sect" id="kuis" style="padding-bottom:6rem">
<div class="kuis-glow" aria-hidden="true"></div>
<div class="kuis-rings" aria-hidden="true"><span></span><span></span><span></span></div>
<div class="kuis-strip" aria-hidden="true"><div class="kuis-strip-in"><div class="strip-set"><span>GONG</span><i>✦</i><span>KENONG</span><i>✦</i><span>ANGKLUNG</span><i>✦</i><span>BONANG</span><i>✦</i><span>SARON</span><i>✦</i><span>GAMBANG</span><i>✦</i><span>DEMUNG</span><i>✦</i><span>KEMPUL</span><i>✦</i><span>PEKING</span><i>✦</i></div><div class="strip-set"><span>GONG</span><i>✦</i><span>KENONG</span><i>✦</i><span>ANGKLUNG</span><i>✦</i><span>BONANG</span><i>✦</i><span>SARON</span><i>✦</i><span>GAMBANG</span><i>✦</i><span>DEMUNG</span><i>✦</i><span>KEMPUL</span><i>✦</i><span>PEKING</span><i>✦</i></div></div></div>
<div class="wrap">
<div class="sect-label"><span class="n">04 / KUIS</span><span class="ax-mask"><span>Uji Pengetahuan</span></span><span class="ln"></span></div>
<div class="quiz-card" style="max-width:560px;margin:0 auto">
<div class="quiz-glare" aria-hidden="true"></div>
<p style="font-family:var(--mono);font-size:.72rem;font-weight:700;letter-spacing:.24em;color:var(--terra);margin-bottom:.8rem">SEPULUH SOAL</p>
<p style="font-size:1.12rem;font-weight:600;line-height:1.55;color:var(--cream);margin-bottom:1.6rem">Lihat gambarnya, tebak instrumennya. Sepuluh soal singkat tentang Nusantara.</p>
@if(!empty($kuisTeaser))
<div class="mini-quiz" id="miniQuiz" data-fb="Itu {{ $kuisTeaser['nama'] }} dari {{ $kuisTeaser['asal'] }}.">
<img class="mini-img" src="{{ $kuisTeaser['gambar'] }}" alt="Foto alat musik tradisional" loading="lazy" decoding="async">
<p class="mini-q">ALAT MUSIK APA INI?</p>
<div class="mini-opts">
@foreach($kuisTeaser['pilihan'] as $pil)
<button type="button" class="mini-opt" data-ok="{{ $pil === $kuisTeaser['nama'] ? '1' : '0' }}">{{ $pil }}</button>
@endforeach
</div>
<p class="mini-fb" id="miniFb" hidden></p>
</div>
@endif
<a href="{{ url('/quiz-global') }}" class="clay-btn primary" id="kuisStartBtn">Mulai Kuis <span class="arw">→</span></a>
<p class="kuis-meta">10 SOAL · TEBAK DARI GAMBAR · ±2 MENIT · TANPA DAFTAR</p>
</div>
</div>
</section>

<script>
document.addEventListener('DOMContentLoaded',function(){
  var io=new IntersectionObserver(function(es){
    es.forEach(function(e){
      if(e.isIntersecting){ e.target.classList.add('inview'); io.unobserve(e.target); }
    });
  },{threshold:.15});
  var els=document.querySelectorAll('.sec-enter');
  els.forEach(function(el){ io.observe(el); });
  // Kuis passage v3: scrub nempel scroll (maju-mundur ikut posisi scroll)
  var ksect=document.getElementById('kuis');
  if(ksect){
    var kglow=document.querySelector('.kuis-glow'),
        kcard=ksect.querySelector('.quiz-card'),
        kn=ksect.querySelector('.sect-label .n'),
        kax=ksect.querySelector('.sect-label .ax-mask'),
        kln=ksect.querySelector('.sect-label .ln'),
        kred=window.matchMedia('(prefers-reduced-motion:reduce)').matches;
    if(!kred){
      ksect.classList.add('k-live');
      var ktick=false;
      function kclamp(v){ return v<0?0:v>1?1:v; }
      function kease(v){ return 1-Math.pow(1-v,3); }
      function kup(){
        ktick=false;
        var r=ksect.getBoundingClientRect(), vh=window.innerHeight;
        if(r.bottom<-80||r.top>vh+80) return;
        var p=kclamp((vh*.95-r.top)/(vh*.53)), e=kease(p);
        if(kn){ kn.style.opacity=e.toFixed(3); kn.style.transform='translateY('+((1-e)*26).toFixed(1)+'px)'; }
        var p2=kease(kclamp((p-.12)/.88));
        if(kax){ kax.style.opacity=p2.toFixed(3); kax.style.transform='translateY('+((1-p2)*26).toFixed(1)+'px)'; }
        if(kln) kln.style.transform='scaleX('+p2.toFixed(3)+')';
        var p3=kease(kclamp((p-.18)/.82));
        if(kcard){ kcard.style.opacity=p3.toFixed(3); kcard.style.transform='translateY(calc('+((1-p3)*54).toFixed(1)+'px + var(--float,0px))) scale('+(.96+.04*p3).toFixed(3)+') perspective(900px) rotateX(var(--trx,0deg)) rotateY(var(--try,0deg)) rotateZ(var(--frz,0deg))'; }
        if(kglow){
          var prog=(vh-r.top)/(vh+r.height);
          kglow.style.transform='translateY('+((prog-.5)*64).toFixed(1)+'px)';
        }
      }
      window.addEventListener('scroll',function(){ if(!ktick){ ktick=true; requestAnimationFrame(kup); } },{passive:true});
      window.addEventListener('resize',function(){ if(!ktick){ ktick=true; requestAnimationFrame(kup); } });
      kup();
      // Interaksi RARE kartu kuis: tilt 3D + kilau + ripple gelombang bunyi
      if(kcard){
        var tRX=0,tRY=0,cRX=0,cRY=0,loopOn=false,hoverOn=false;
        function tiltLoop(){
          cRX+=(tRX-cRX)*.12; cRY+=(tRY-cRY)*.12;
          kcard.style.setProperty('--trx',cRX.toFixed(2)+'deg');
          kcard.style.setProperty('--try',cRY.toFixed(2)+'deg');
          if(hoverOn||Math.abs(cRX-tRX)>.02||Math.abs(cRY-tRY)>.02) requestAnimationFrame(tiltLoop);
          else loopOn=false;
        }
        function tiltStart(){ if(!loopOn){ loopOn=true; requestAnimationFrame(tiltLoop); } }
        kcard.addEventListener('pointerenter',function(){ hoverOn=true; tiltStart(); });
        kcard.addEventListener('pointermove',function(e){
          var r=kcard.getBoundingClientRect();
          var px=(e.clientX-r.left)/r.width, py=(e.clientY-r.top)/r.height;
          tRY=(px-.5)*12; tRX=(.5-py)*10;
          kcard.style.setProperty('--gx',(px*100).toFixed(1)+'%');
          kcard.style.setProperty('--gy',(py*100).toFixed(1)+'%');
          tiltStart();
        });
        kcard.addEventListener('pointerleave',function(){ hoverOn=false; tRX=0; tRY=0; tiltStart(); });
        kcard.addEventListener('pointerdown',function(e){
          if(e.target.closest('a')) return;
          var r=kcard.getBoundingClientRect();
          var sp=document.createElement('span'); sp.className='quiz-ripple';
          var d=Math.max(r.width,r.height)*2.2;
          sp.style.width=sp.style.height=d+'px';
          sp.style.left=(e.clientX-r.left)+'px'; sp.style.top=(e.clientY-r.top)+'px';
          kcard.appendChild(sp);
          sp.addEventListener('animationend',function(){ sp.remove(); });
        });
        window.addEventListener('deviceorientation',function(e){
          if(e.beta==null||e.gamma==null) return;
          tRX=Math.max(-6,Math.min(6,(e.beta-45)*.25));
          tRY=Math.max(-8,Math.min(8,e.gamma*.3));
          tiltStart();
        });
      }
      // Idle: kartu mengambang sendiri + kilau nyapu otomatis tiap ~6 detik
      var fT=Math.random()*6.28, waitT=0, sweepP=-1;
      function idleLoop(){
        var r=ksect.getBoundingClientRect(), vh=window.innerHeight;
        if(r.bottom>-80&&r.top<vh+80){
          fT+=0.02;
          kcard.style.setProperty('--float',(Math.sin(fT*1.15)*13).toFixed(2)+'px');
          kcard.style.setProperty('--frz',(Math.sin(fT*.7)*.6).toFixed(2)+'deg');
          if(sweepP<0){ waitT+=0.016; if(waitT>5){ sweepP=0; waitT=0; } }
          else{
            sweepP+=0.016/1.4;
            var sp=Math.min(1,sweepP);
            kcard.style.setProperty('--gx',(-20+sp*140).toFixed(1)+'%');
            kcard.style.setProperty('--gy','35%');
            kcard.style.setProperty('--gop',(Math.sin(sp*Math.PI)).toFixed(2));
            kcard.style.setProperty('--galpha','.30');
            kcard.style.setProperty('--gsize','560px');
            if(sp>=1){ sweepP=-1; kcard.style.setProperty('--gop',''); kcard.style.setProperty('--galpha',''); kcard.style.setProperty('--gsize',''); }
          }
        }
        requestAnimationFrame(idleLoop);
      }
      requestAnimationFrame(idleLoop);
    }
  }
  // Mini kuis: contoh soal langsung di kartu (klik pilihan -> feedback)
  var mq=document.getElementById('miniQuiz');
  if(mq){
    var mfb=document.getElementById('miniFb'),
        mbtn=document.getElementById('kuisStartBtn'),
        mdone=false;
    mq.querySelectorAll('.mini-opt').forEach(function(b){
      b.addEventListener('click',function(){
        if(mdone) return; mdone=true;
        var ok=b.getAttribute('data-ok')==='1';
        mq.querySelectorAll('.mini-opt').forEach(function(x){
          x.disabled=true;
          if(x.getAttribute('data-ok')==='1') x.classList.add('ok');
        });
        if(!ok) b.classList.add('no');
        if(mfb){
          mfb.hidden=false;
          mfb.textContent=(ok?'Betul! ':'Belum tepat — ')+(mq.getAttribute('data-fb')||'')
            +(ok?' Berani lanjut 10 soal aslinya?':' Balas di kuis aslinya, yuk.');
        }
        if(mbtn){ mbtn.classList.remove('attn'); void mbtn.offsetWidth; mbtn.classList.add('attn'); }
      });
    });
  }
  // fallback: kalau IO tidak support, tampilkan semua
  if(!('IntersectionObserver' in window)){ els.forEach(function(el){ el.classList.add('inview'); }); }
});
</script>
<script>
/* v264: scrollytelling — tandai step aktif */
(function(){
  var steps=document.querySelectorAll('.story-step'); if(!steps.length) return;
  var tick=false;
  function upd(){
    tick=false;
    var mid=window.innerHeight*0.62, best=null, bestD=1e9;
    steps.forEach(function(st){
      var r=st.getBoundingClientRect(), c=r.top+r.height/2, d=Math.abs(c-mid);
      if(d<bestD){ bestD=d; best=st; }
    });
    steps.forEach(function(st){ st.classList.toggle('active', st===best); });
  }
  function req(){ if(!tick){ tick=true; requestAnimationFrame(upd); } }
  window.addEventListener('scroll',req,{passive:true});
  window.addEventListener('resize',req);
  upd();
})();
/* v262: pin 1 layar — scroll bawah menggerakkan track ke kiri */
</script>
<script>
(function(){
  if(window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  var sec=document.getElementById('koleksi'); if(!sec) return;
  var hgal=sec.querySelector('.hgal'); if(!hgal) return;
  var pin=hgal.querySelector('.hgal-pin'); if(!pin) return;
  var panels=hgal.querySelectorAll('.hgal-panel');
  hgal.style.height=(panels.length*60)+'vh';
  var tick=false;
  function upd(){
    tick=false;
    var r=hgal.getBoundingClientRect(), total=hgal.offsetHeight-window.innerHeight;
    var p=total>0?Math.min(1,Math.max(0,-r.top/total)):0;
    pin.scrollLeft=p*Math.max(0,pin.scrollWidth-pin.clientWidth);
  }
  function req(){ if(!tick){ tick=true; requestAnimationFrame(upd); } }
  window.addEventListener('scroll',req,{passive:true});
  window.addEventListener('resize',req);
  upd();
})();
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
/* Back to top */
(function(){
  var b=document.createElement('button');b.id='toTop';b.setAttribute('aria-label','Kembali ke atas');b.innerHTML='&#8593;';
  document.body.appendChild(b);
  var onScroll=function(){b.classList.toggle('show',(window.scrollY||window.pageYOffset)>600);};
  window.addEventListener('scroll',onScroll,{passive:true});onScroll();
  b.addEventListener('click',function(){window.scrollTo({top:0,behavior:'smooth'});});
})();
</script>



@endsection