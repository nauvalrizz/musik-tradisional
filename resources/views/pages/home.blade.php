@extends('layouts.app')
@section('content')

{{-- HERO: clay exhibition --}}
<section class="hero-clay" id="jelajahi">
<div class="wrap">
<div class="hero-grid">
<div class="reveal" id="heroTitleBlock">
<p class="hero-kicker" id="heroEyebrow">DIGITAL MUSEUM OF INDONESIAN SOUND</p>
<h1 class="hero-title" id="heroTitle" data-full="MUSANTARA">MUSANTARA</h1>
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
<div class="artifact-stage reveal" id="heroArtifact">
<div class="as-spotlight" aria-hidden="true"></div>
<div class="as-soundfield" aria-hidden="true"><i></i><i></i><i></i></div>
<div class="as-platform" aria-hidden="true"><div class="as-tier1"></div><div class="as-tier2"></div></div>
<div class="as-shadow" aria-hidden="true"></div>
<div id="hero3d" role="button" tabindex="0" aria-label="Putar instrumen"
  style="position:relative;z-index:2;width:min(360px,74vw);aspect-ratio:1;cursor:pointer"></div>
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
{"imports":{"three":"https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js"}}
</script>
<script>
window.HERO_DATA = @json($heroInstruments ?? []);
</script>
<script type="module">
import * as THREE from 'three';
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
let G=null, vib=null, rings=[];
function clearG(){if(G){scene.remove(G);G.traverse(o=>{if(o.geometry)o.geometry.dispose();});}rings.forEach(r=>scene.remove(r.m));rings=[];}
// ─── GONG: disc + boss + rim, digantung frame kayu ───
function buildGong(){
  G=new THREE.Group();
  const disc=sh(new THREE.Mesh(new THREE.CylinderGeometry(1.0,1.0,.06,48),M.bronze));
  disc.rotation.x=Math.PI/2; disc.position.y=.75; G.add(disc);
  const boss=sh(new THREE.Mesh(new THREE.SphereGeometry(.3,28,20),M.bronzeL));
  boss.scale.z=.5; boss.position.set(0,.75,.06); G.add(boss);
  const rim=sh(new THREE.Mesh(new THREE.TorusGeometry(1.0,.07,16,48),M.bronzeD));
  rim.position.y=.75; G.add(rim);
  const rim2=sh(new THREE.Mesh(new THREE.TorusGeometry(.78,.02,10,44),M.bronzeD));
  rim2.position.set(0,.75,.02); G.add(rim2);
  [[-1.15],[1.15]].forEach(([x])=>{const p=sh(new THREE.Mesh(new THREE.BoxGeometry(.14,2.1,.14),M.wood));p.position.set(x,.25,0);G.add(p);});
  const bar=sh(new THREE.Mesh(new THREE.BoxGeometry(2.6,.12,.12),M.wood)); bar.position.y=1.28; G.add(bar);
  [[-.62],[.62]].forEach(([x])=>{const rp=new THREE.Mesh(new THREE.CylinderGeometry(.02,.02,.5,8),M.rope);rp.position.set(x,1.1,0);G.add(rp);});
  vib=t=>{disc.position.z=Math.sin(t*30)*.025;boss.position.z=.06+Math.sin(t*30)*.025;};
}
// ─── KENONG: vessel bundar + boss, di atas pangkon silang ───
function buildKenong(){
  G=new THREE.Group();
  const pts=[];
  for(let i=0;i<=16;i++){const a=i/16*Math.PI;pts.push(new THREE.Vector2(Math.sin(a)*.62,.42+Math.cos(a)*.34));}
  const body=sh(new THREE.Mesh(new THREE.LatheGeometry(pts,36),M.bronze));
  body.position.y=.15; G.add(body);
  const boss=sh(new THREE.Mesh(new THREE.SphereGeometry(.2,24,18),M.bronzeL));
  boss.scale.y=.55; boss.position.y=.5; G.add(boss);
  const ring=sh(new THREE.Mesh(new THREE.TorusGeometry(.44,.03,10,32),M.bronzeD));
  ring.rotation.x=Math.PI/2; ring.position.y=.2; G.add(ring);
  [[-.4],[.4]].forEach(([x])=>{const b=sh(new THREE.Mesh(new THREE.BoxGeometry(.1,.5,1.0),M.wood));b.position.set(x,-.1,0);b.rotation.z=x>0?.35:-.35;G.add(b);});
  const base=sh(new THREE.Mesh(new THREE.BoxGeometry(1.1,.08,1.1),M.wood)); base.position.y=-.32; G.add(base);
  vib=t=>{body.position.y=.15+Math.sin(t*36)*.012;boss.position.y=.5+Math.sin(t*36)*.012;};
}
// ─── ANGKLUNG: tabung bambu beda panjang dalam bingkai ───
function buildAngklung(){
  G=new THREE.Group();
  [[-.5],[.5]].forEach(([x])=>{const p=sh(new THREE.Mesh(new THREE.CylinderGeometry(.05,.05,1.5,10),M.bambooD));p.position.set(x,.55,0);G.add(p);});
  const top=sh(new THREE.Mesh(new THREE.CylinderGeometry(.045,.045,1.15,10),M.bambooD));
  top.rotation.z=Math.PI/2; top.position.y=1.28; G.add(top);
  const bot=top.clone(); bot.position.y=-.15; G.add(bot);
  const tubes=[];
  [[-.38,.95],[-.19,1.05],[0,1.15],[.19,1.0],[.38,.88]].forEach(([x,h])=>{
    const tg=new THREE.Group();
    const tb=sh(new THREE.Mesh(new THREE.CylinderGeometry(.075,.075,h,12),M.bamboo)); tg.add(tb);
    const cap=sh(new THREE.Mesh(new THREE.CylinderGeometry(.078,.078,.06,12),M.bambooD)); cap.position.y=h/2; tg.add(cap);
    const tongue=sh(new THREE.Mesh(new THREE.BoxGeometry(.05,h*.42,.02),M.bambooD)); tongue.position.y=-h*.2; tg.add(tongue);
    tg.position.set(x,1.28-h/2-.04,0); tg.userData.baseX=x; G.add(tg); tubes.push(tg);
  });
  vib=t=>{tubes.forEach((tg,i)=>{tg.rotation.z=Math.sin(t*24+i*1.3)*.045;tg.position.x=tg.userData.baseX+Math.sin(t*24+i)*.012;});};
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
<div class="wrap">
<div class="sect-label reveal"><span class="n">01 / ARSIP</span><span class="t">Musantara</span><span class="ln"></span></div>
<div class="gamelan gamelan-sec">
<div class="gamelan-frame reveal">
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
{!! $rb_svg[$kw] ?? $rb_svg['gong'] !!}
<b>{{ $rb['nama'] }}</b>
</button>
@endforeach
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
