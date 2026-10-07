/* Ruang Bunyi: ensemble gamelan mini (Web Audio API, tanpa file audio).
   Tiap alat punya timbre sendiri: partial inharmonic + decay eksponensial. */
document.addEventListener('DOMContentLoaded', function () {
    var pads = document.querySelectorAll('.gamelan-pad');
    if (!pads.length) return;

    var ctx = null, master = null;

    /* [rasio partial, gain, decay] per alat */
    var TIMBRES = {
        angklung: [[1.00, 0.70, 0.9], [2.01, 0.32, 0.5], [2.98, 0.18, 0.3], [4.20, 0.08, 0.18]],
        kempul: [[1.00, 0.85, 3.2], [1.50, 0.35, 1.8], [2.09, 0.20, 1.0], [2.94, 0.10, 0.6]],
        kenong: [[1.00, 0.85, 2.6], [2.76, 0.30, 1.2], [5.40, 0.12, 0.5]],
        saron:  [[1.00, 0.85, 1.6], [2.76, 0.28, 0.8], [5.40, 0.10, 0.4], [8.93, 0.05, 0.2]],
        bonang: [[1.00, 0.80, 2.2], [2.76, 0.35, 1.1], [5.40, 0.15, 0.5]],
        gong: [[1.00, 0.90, 4.5], [1.48, 0.40, 2.5], [2.09, 0.22, 1.4], [2.94, 0.10, 0.8]],
        kendang: [[1.00, 0.90, 0.35], [1.59, 0.45, 0.2], [2.14, 0.20, 0.12]],
        suling: [[1.00, 0.75, 1.2], [2.00, 0.25, 0.7], [3.01, 0.10, 0.4]]
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

    /* sound-reactive: body berdenyut ngikutin bunyi */
    var analyser = null;
    function pulseBody(intensity) {
        document.body.style.setProperty('--pulse', intensity);
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

    function hitPad(pad) {
        var freq = parseFloat(pad.dataset.freq);
        var inst = pad.dataset.instrument;
        strike(freq, inst);
        // angklung digoyang: tabuhan kedua menyusul 90ms kemudian
        if (inst === 'angklung') {
            setTimeout(function () { strike(freq * 1.005, inst); }, 90);
        }

        var ripple = document.createElement('span');
        ripple.className = 'pad-ripple';
        pad.appendChild(ripple);
        setTimeout(function () { ripple.remove(); }, 700);

        pulseBody(1);
        setTimeout(function() { pulseBody(0); }, 300);
        pad.classList.add('struck');
        setTimeout(function () { pad.classList.remove('struck'); }, 320);

        // rekam ketukan
        if (recState.recording) {
            recState.events.push({
                idx: Array.prototype.indexOf.call(pads, pad),
                t: Date.now() - recState.start
            });
            updateRecUI();
        }
    }

    pads.forEach(function (pad) {
        pad.addEventListener('pointerdown', function () { hitPad(pad); });
    });

    /* ================= MODE REKAM ================= */
    var recState = { recording: false, playing: false, events: [], start: 0, timers: [] };
    var btnRec = document.getElementById('btnRec');
    var btnPlay = document.getElementById('btnPlay');
    var recStatus = document.getElementById('recStatus');

    function updateRecUI() {
        if (!btnRec || !btnPlay || !recStatus) return;
        btnRec.classList.toggle('rec-on', recState.recording);
        btnRec.querySelector('span').textContent = recState.recording ? 'Berhenti' : 'Rekam';
        btnPlay.disabled = recState.recording || recState.playing || recState.events.length === 0;
        if (recState.recording) {
            recStatus.textContent = 'Merekam… ' + recState.events.length + ' ketukan';
        } else if (recState.playing) {
            recStatus.textContent = 'Memutar…';
        } else if (recState.events.length) {
            recStatus.textContent = recState.events.length + ' ketukan terekam';
        } else {
            recStatus.textContent = 'Ketuk Rekam, mainkan alatnya';
        }
    }

    function stopPlayback() {
        recState.timers.forEach(clearTimeout);
        recState.timers = [];
        recState.playing = false;
        updateRecUI();
    }

    if (btnRec) {
        btnRec.addEventListener('click', function () {
            if (recState.playing) stopPlayback();
            recState.recording = !recState.recording;
            if (recState.recording) {
                recState.events = [];
                recState.start = Date.now();
            }
            updateRecUI();
        });
    }

    if (btnPlay) {
        btnPlay.addEventListener('click', function () {
            if (!recState.events.length || recState.playing) return;
            if (recState.recording) {
                recState.recording = false;
            }
            recState.playing = true;
            updateRecUI();
            recState.events.forEach(function (ev) {
                recState.timers.push(setTimeout(function () {
                    var pad = pads[ev.idx];
                    if (pad) hitPad(pad);
                }, ev.t));
            });
            var total = recState.events[recState.events.length - 1].t + 600;
            recState.timers.push(setTimeout(stopPlayback, total));
        });
    }

    updateRecUI();
});

/* v49 — ambient mode */
(function() {
    var btn = document.getElementById('ambient-toggle');
    if (!btn) return;
    var playing = false, timer = null;
    var seq = [130.81, 164.81, 196.00, 164.81]; // kenong-saron-bonang pattern
    var idx = 0;
    btn.addEventListener('click', function() {
        playing = !playing;
        btn.classList.toggle('on', playing);
        btn.querySelector('span').textContent = playing ? 'Matikan' : 'Ambient';
        if (playing) {
            timer = setInterval(function() {
                var pads = document.querySelectorAll('.gamelan-pad');
                if (pads[idx % pads.length]) pads[idx % pads.length].click();
                idx++;
            }, 1200);
        } else {
            clearInterval(timer);
        }
    });
})();
