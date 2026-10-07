/* Ruang Bunyi: ensemble gamelan mini (Web Audio API, tanpa file audio).
   Tiap alat punya timbre sendiri: partial inharmonic + decay eksponensial. */
document.addEventListener('DOMContentLoaded', function () {
    var pads = document.querySelectorAll('.gamelan-pad');
    if (!pads.length) return;

    var ctx = null, master = null;

    /* [rasio partial, gain, decay] per alat */
    var TIMBRES = {
        gong:   [[1.00, 0.90, 5.0], [1.19, 0.40, 3.5], [1.50, 0.30, 2.8], [2.00, 0.20, 2.0], [2.74, 0.12, 1.2]],
        kempul: [[1.00, 0.85, 3.2], [1.50, 0.35, 1.8], [2.09, 0.20, 1.0], [2.94, 0.10, 0.6]],
        kenong: [[1.00, 0.85, 2.6], [2.76, 0.30, 1.2], [5.40, 0.12, 0.5]],
        saron:  [[1.00, 0.85, 1.6], [2.76, 0.28, 0.8], [5.40, 0.10, 0.4], [8.93, 0.05, 0.2]],
        bonang: [[1.00, 0.80, 2.2], [2.76, 0.35, 1.1], [5.40, 0.15, 0.5]]
    };

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

    function strike(freq, instrument) {
        if (!ensureCtx()) return;
        var partials = TIMBRES[instrument] || TIMBRES.saron;
        var t = ctx.currentTime + 0.01;
        partials.forEach(function (p) {
            var o = ctx.createOscillator();
            o.type = 'sine';
            o.frequency.value = freq * p[0];
            var gn = ctx.createGain();
            gn.gain.setValueAtTime(0.0001, t);
            gn.gain.exponentialRampToValueAtTime(p[1], t + 0.004);
            gn.gain.exponentialRampToValueAtTime(0.0001, t + p[2]);
            o.connect(gn);
            gn.connect(master);
            o.start(t);
            o.stop(t + p[2] + 0.1);
        });
    }

    pads.forEach(function (pad) {
        pad.addEventListener('pointerdown', function () {
            strike(parseFloat(pad.dataset.freq), pad.dataset.instrument);

            var ripple = document.createElement('span');
            ripple.className = 'pad-ripple';
            pad.appendChild(ripple);
            setTimeout(function () { ripple.remove(); }, 700);

            pad.classList.add('struck');
            setTimeout(function () { pad.classList.remove('struck'); }, 320);
        });
    });
});
