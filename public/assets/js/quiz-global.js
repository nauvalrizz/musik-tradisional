document.addEventListener("DOMContentLoaded", () => {

    let questions = [];
    let current = 0;
    let history = [];
    let answered = false;
    let lock = false;
    let score = 0;
    let quizData = [];
    let timer;
    let timeLeft = 10;


    const questionEl = document.getElementById("question");
    const optionsEl = document.getElementById("options");
    const progressEl = document.getElementById("progress");
    const progressBar = document.getElementById("progressBar");
    const loadingEl = document.getElementById("quizLoading");

    // ================= SOUND =================
    const soundCorrect = new Audio('/assets/sound/correct.mp3');
    const soundWrong = new Audio('/assets/sound/wrong.mp3');
    const soundTick = new Audio('/assets/sound/tick.mp3');
    const soundTimeout = new Audio('/assets/sound/timeout.mp3');

    // preload biar ga delay
    soundTick.preload = "auto";
    soundTimeout.preload = "auto";

    // 🔊 volume global
    soundCorrect.volume = 0.7;
    soundWrong.volume = 0.6;
    soundTick.volume = 0.3;
    soundTimeout.volume = 0.5;

    // flag biar ga spam
    let tickPlayed = false;

    function shuffle(arr) {
        return arr.sort(() => 0.5 - Math.random());
    }

    function showToast(msg, type = "success") {
        const toast = document.getElementById("toast");
        if (!toast) return;

        toast.innerText = msg;
        toast.classList.remove("success", "error");
        toast.classList.add(type, "show");

        setTimeout(() => toast.classList.remove("show"), 2000);
    }

    // ================= GENERATE =================

    // ================= RENDER =================
    function renderQuestion(q) {

        const questionEl = document.getElementById("question");
        const optionsEl = document.getElementById("options");
        const imgContainer = document.getElementById("quizImage");
        document.getElementById("currentStep").innerText = String(current + 1).padStart(2, "0");
        document.getElementById("totalStep").innerText = String(questions.length).padStart(2, "0");

        // 🔥 RESET STATE
        answered = false;

        // 🔥 RESET ANIMATION (biar ga numpuk)
        questionEl.classList.remove("slide-in");
        questionEl.classList.add("slide-in");

        // =========================
        // 🔥 IMAGE (SPLIT LAYOUT)
        // =========================
        if (imgContainer) {
            let label = '';

            if (q.tipe === 'nama') label = 'Tebak Nama';
            if (q.tipe === 'kategori') label = 'Cara Main';
            if (q.tipe === 'sumber') label = 'Sumber Bunyi';

            imgContainer.innerHTML = `
            <p class="quiz-label">${label}</p>
            ${q.image ? `<img src="${q.image}" class="quiz-img">` : ''}
        `;
        }

        // =========================
        // 🔥 QUESTION TEXT
        // =========================
        questionEl.innerHTML = `
        <p>${q.question}</p>
    `;

        // =========================
        // 🔥 OPTIONS
        // =========================
        optionsEl.innerHTML = "";

        q.options.forEach((opt, idx) => {
            const btn = document.createElement("button");
            btn.className = "quiz-opt";
            btn.dataset.value = opt;
            btn.innerHTML = '<span class="opt-num">' + String(idx + 1).padStart(2, "0") + '</span><span class="opt-text">' + opt + '</span>';

            btn.onclick = () => {
                if (!answered) {
                    selectAnswer(btn, opt, q);
                }
            };

            optionsEl.appendChild(btn);
        });

        // =========================
        // 🔥 PROGRESS
        // =========================
        const progressEl = document.getElementById("progress");
        const progressBar = document.getElementById("progressBar");

        if (progressEl) {
            progressEl.innerText = `Soal ${current + 1} / ${questions.length}`;
        }

        if (progressBar) {
            progressBar.style.width = ((current + 1) / questions.length) * 100 + "%";
        }

        // =========================
        // 🔥 START TIMER (WAJIB)
        // =========================
        if (typeof startTimer === "function") {
            startTimer();
        }
    }

    function showQuestion() {

        if (current >= questions.length) {

            localStorage.setItem("quiz_score", score);
            localStorage.setItem("quiz_total", questions.length);
            localStorage.setItem("quiz_history", JSON.stringify(history));

            submitToDatabase();
            return;
        }

        renderQuestion(questions[current]);
        startTimer();
    }

    // ================= ANSWER =================
    function selectAnswer(btn, selected, q) {

        if (answered || lock) return;
        clearInterval(timer);
        const isCorrect = selected === q.correct;

        // 🔥 PLAY AUDIO DI AWAL (FIX)
        const sound = isCorrect ? soundCorrect : soundWrong;

        if (sound) {
            sound.currentTime = 0;
            sound.play().catch(() => { });
        }

        answered = true;
        lock = true;

        const buttons = document.querySelectorAll(".option");
        buttons.forEach(b => b.disabled = true);

        // 🔥 SIMPAN HISTORY (VERSI LENGKAP)
        history.push({
            alat_id: q.alat_id,
            tipe: q.tipe,
            question: q.question,
            selected: selected,
            correct: q.correct,
            is_correct: isCorrect ? 1 : 0
        });

        if (isCorrect) {
            score += 10;
            btn.classList.add("correct");
            showToast("Tepat.", "success");
        } else {
            btn.classList.add("wrong");

            buttons.forEach(b => {
                if (b.dataset.value === q.correct) {
                    b.classList.add("correct");
                }
            });

            showToast("Belum tepat.", "error");
        }

        loadingEl?.classList.add("active");

        setTimeout(() => {
            current++;
            showQuestion();

            setTimeout(() => {
                loadingEl?.classList.remove("active");
                lock = false;
            }, 300);

        }, 1200);
    }

    // ================= DB =================
    function submitToDatabase() {

        fetch('/quiz/submit-global', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ answers: history })
        })
            .then(() => window.location.href = "/quiz-result");
    }

    // ================= LOAD =================
    async function loadQuiz() {
        try {
            const res = await fetch('/quiz/questions');
            const data = await res.json();

            questions = data; // 🔥 langsung dari backend
            showQuestion();

        } catch (err) {
            console.error(err);
            questionEl.innerText = 'Gagal memuat kuis.';
        }
    }

    loadQuiz();

    function startTimer() {
        tickPlayed = false;
        const timerEl = document.getElementById("quizTimer");
        const bar = document.getElementById("timerProgress");

        timeLeft = 10;
        timerEl.textContent = timeLeft;

        bar.style.width = "100%";
        bar.style.background = "var(--bronze)";
        bar.style.boxShadow = "none";

        clearInterval(timer);

        timer = setInterval(() => {
            timeLeft--;

            timerEl.textContent = timeLeft;

            let percent = (timeLeft / 10) * 100;
            bar.style.width = percent + "%";

            // warna timer
            if (timeLeft > 6) {
                bar.style.background = "var(--bronze)";
            } else if (timeLeft > 3) {
                bar.style.background = "#facc15";
            } else {
                bar.style.background = "#ef4444";
            }

            // ================= SOUND TICK =================
            if (timeLeft <= 3 && timeLeft > 0) {
                soundTick.pause();
                soundTick.currentTime = 0;
                soundTick.play().catch(() => { });
            }

            if (timeLeft > 3) {
                tickPlayed = false;
            }

            // ================= TIMEOUT =================
            if (timeLeft <= 0) {
                clearInterval(timer);

                soundTimeout.pause();
                soundTimeout.currentTime = 0;
                soundTimeout.play().catch(() => { });

                autoNext();
            }

        }, 1000);
    }

    function autoNext() {

        if (answered || lock) return;

        answered = true;
        lock = true;

        const q = questions[current];

        // 🔥 simpan sebagai SALAH
        history.push({
            alat_id: q.alat_id,
            tipe: q.tipe,
            question: q.question,
            selected: "",
            correct: q.correct,
            is_correct: 0
        });

        showToast("Waktu habis.", "error");

        const buttons = document.querySelectorAll(".option");
        buttons.forEach(b => b.disabled = true);

        // highlight jawaban benar
        buttons.forEach(b => {
            if (b.dataset.value === q.correct) {
                b.classList.add("correct");
            }
        });

        loadingEl?.classList.add("active");

        setTimeout(() => {
            current++;
            showQuestion();

            setTimeout(() => {
                loadingEl?.classList.remove("active");
                lock = false;
            }, 300);

        }, 1200);
    }
});