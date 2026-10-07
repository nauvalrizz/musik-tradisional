// Karakter bunyi sintetis per sumber bunyi (Hornbostel-Sachs).
// Dipakai di halaman detail bila alat tidak punya file audio.
document.addEventListener('DOMContentLoaded', function () {
    var btn = document.getElementById('btnSynth');
    if (!btn) return;

    var kategori = (btn.dataset.sumber || '').toLowerCase();
    var ctx = null, master = null;

    function ensureCtx() {
        if (!ctx) {
            var AC = window.AudioContext || window.webkitAudioContext;
            if (!AC) return false;
            ctx = new AC();
            master = ctx.createGain();
            master.gain.value = 0.5;
            master.connect(ctx.destination);
        }
        if (ctx.state === 'suspended') ctx.resume();
        return true;
    }

    function tone(freq, type, gain, attack, decay, when, slideTo) {
        var t = ctx.currentTime + (when || 0);
        var o = ctx.createOscillator();
        o.type = type;
        o.frequency.setValueAtTime(freq, t);
        if (slideTo) o.frequency.exponentialRampToValueAtTime(slideTo, t + attack + decay);
        var g = ctx.createGain();
        g.gain.setValueAtTime(0.0001, t);
        g.gain.exponentialRampToValueAtTime(gain, t + attack);
        g.gain.exponentialRampToValueAtTime(0.0001, t + attack + decay);
        o.connect(g); g.connect(master);
        o.start(t); o.stop(t + attack + decay + 0.1);
    }

    function play() {
        if (!ensureCtx()) return;
        btn.classList.add('playing');
        setTimeout(function () { btn.classList.remove('playing'); }, 1200);

        if (kategori.indexOf('membran') !== -1) {
            // gendang: dentum rendah
            tone(110, 'sine', 0.9, 0.005, 0.5, 0, 55);
            tone(220, 'triangle', 0.25, 0.003, 0.2, 0);
        } else if (kategori.indexOf('kord') !== -1) {
            // petik: pluck cerah
            tone(329.63, 'triangle', 0.7, 0.004, 0.9, 0);
            tone(659.25, 'sine', 0.25, 0.004, 0.5, 0.01);
            tone(987.77, 'sine', 0.1, 0.004, 0.3, 0.02);
        } else if (kategori.indexOf('aer') !== -1) {
            // tiup: nada meniup dengan vibrato
            var t = ctx.currentTime;
            var o = ctx.createOscillator();
            o.type = 'sine'; o.frequency.value = 440;
            var vib = ctx.createOscillator();
            vib.frequency.value = 5.5;
            var vibG = ctx.createGain(); vibG.gain.value = 6;
            vib.connect(vibG); vibG.connect(o.frequency);
            var g = ctx.createGain();
            g.gain.setValueAtTime(0.0001, t);
            g.gain.exponentialRampToValueAtTime(0.55, t + 0.12);
            g.gain.exponentialRampToValueAtTime(0.0001, t + 1.1);
            o.connect(g); g.connect(master);
            o.start(t); vib.start(t);
            o.stop(t + 1.2); vib.stop(t + 1.2);
        } else if (kategori.indexOf('elektr') !== -1) {
            // elektrofon: saw lembut
            tone(196, 'sawtooth', 0.3, 0.01, 0.8, 0);
            tone(392, 'sine', 0.3, 0.01, 0.8, 0.02);
        } else {
            // idiofon default: metalik cerah
            tone(523.25, 'sine', 0.7, 0.004, 1.4, 0);
            tone(523.25 * 2.76, 'sine', 0.25, 0.004, 0.7, 0.01);
            tone(523.25 * 5.4, 'sine', 0.1, 0.004, 0.35, 0.02);
        }
    }

    btn.addEventListener('click', play);
});
