@extends('layouts.app')
@section('content')

{{-- HERO: clay exhibition --}}
<section class="hero-clay" id="jelajahi">
<div class="wrap">
<div class="hero-grid">
<div class="reveal" id="heroTitleBlock">
<p class="hero-kicker" id="heroEyebrow">DIGITAL MUSEUM OF INDONESIAN SOUND</p>
<h1 class="hero-title" id="heroTitle">Musa<span>ntara</span></h1>
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
<script type="module">
import * as THREE from 'three';
(function(){
// ═══ SINGLE SOURCE OF TRUTH ═══
const INSTRUMENTS={
  gong:{id:'gong',name:'GONG',origin:'JAWA · PERUNGGU',freq:98,decay:3.2,viz:'deep',
    desc:'Gong ageng — jantung gamelan'},
  kenong:{id:'kenong',name:'KENONG',origin:'JAWA · PERUNGGU',freq:220,decay:1.6,viz:'pulse',
    desc:'Kenong — penanda irama gamelan'},
  angklung:{id:'angklung',name:'ANGKLUNG',origin:'SUNDA · BAMBU',freq:440,decay:1.1,viz:'layered',
    desc:'Angklung — orkestra bambu Sunda'},
};
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
  SEL=INSTRUMENTS[id]||INSTRUMENTS.gong; clearG();
  ({gong:buildGong,kenong:buildKenong,angklung:buildAngklung}[SEL.id])();
  scene.add(G);
  G.scale.setScalar(.01); // transisi masuk
  document.getElementById('heroName').textContent=SEL.name;
  document.getElementById('heroOrigin').textContent=SEL.origin;
  document.getElementById('heroPlayBtn').setAttribute('aria-label','Bunyikan '+SEL.name);
  document.querySelectorAll('.hs-btn').forEach(b=>{
    const on=b.dataset.id===SEL.id;
    b.classList.toggle('active',on); b.setAttribute('aria-selected',on);
  });
}
// Audio: synth per instrumen (arsitektur audio hero yang sudah ada)
let AC=null;
function playAudio(){
  try{
    AC=AC||new (window.AudioContext||window.webkitAudioContext)();
    if(AC.state==='suspended')AC.resume();
    const t=AC.currentTime, f=SEL.freq, d=SEL.decay;
    const mk=(type,freq,vol,dec)=>{
      const o=AC.createOscillator(),g=AC.createGain();
      o.type=type;o.frequency.value=freq;
      g.gain.setValueAtTime(0,t);g.gain.linearRampToValueAtTime(vol,t+.02);
      g.gain.exponentialRampToValueAtTime(.0001,t+dec);
      o.connect(g).connect(AC.destination);o.start(t);o.stop(t+dec+.1);
    };
    if(SEL.id==='gong'){mk('sine',f,.5,d);mk('sine',f*2.02,.2,d*.7);mk('sine',f*2.94,.1,d*.5);}
    else if(SEL.id==='kenong'){mk('triangle',f,.45,d);mk('sine',f*2.4,.15,d*.6);}
    else{mk('triangle',f,.4,d);mk('sine',f*2,.12,d*.7);mk('sine',f*3.01,.06,d*.5);}
  }catch(e){}
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
  playAudio(); ripple();
  sounding=true; vibT=0; stage.classList.add('sounding');
  const pb=document.getElementById('heroPlayBtn');
  if(pb){pb.classList.add('playing');pb.innerHTML='&#10074;&#10074;';}
  if(window.mpShow) mpShow(SEL.name.charAt(0)+SEL.name.slice(1).toLowerCase(),'Artefak · '+SEL.origin);
  const st=document.getElementById('soundStatus');
  if(st) st.textContent='♪ '+SEL.name+' · '+SEL.origin;
  clearTimeout(stage._pt); stage._pt=setTimeout(stop, SEL.decay*1000+300);
}
function stop(){
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
// Parallax pointer (desktop)
const hoverOK=matchMedia('(hover:hover)').matches;
if(hoverOK){
  stage.addEventListener('mousemove',e=>{
    const r=stage.getBoundingClientRect();
    const x=(e.clientX-r.left)/r.width-.5, y=(e.clientY-r.top)/r.height-.5;
    if(G){G.rotation.y=x*.5;G.position.x=x*.25;}
    cam.position.x=x*.7; cam.position.y=1.5-y*.3;
    const tb=document.getElementById('heroTitleBlock');
    if(tb) tb.style.transform='translate('+(-x*10)+'px,'+(-y*8)+'px)';
  });
  stage.addEventListener('mouseleave',()=>{cam.position.x=0;cam.position.y=1.5;
    const tb=document.getElementById('heroTitleBlock'); if(tb)tb.style.transform='';});
}
function rs(){const w=box.clientWidth,h=box.clientHeight;renderer.setSize(w,h);cam.aspect=w/h;cam.updateProjectionMatrix();}
new ResizeObserver(rs).observe(box); rs();
select('gong');
const clk=new THREE.Clock();
(function loop(){
  requestAnimationFrame(loop);
  const t=clk.getElapsedTime();
  if(G){
    if(G.scale.x<1)G.scale.setScalar(Math.min(1,G.scale.x+.03)); // transisi masuk
    if(!sounding)G.rotation.y+= (hoverOK?0:Math.sin(t*.2)*.002)+ (hoverOK?0:.0012);
    else{vibT+=.016;if(vib)vib(vibT);}
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
