document.addEventListener("DOMContentLoaded", () => {

    /* ================= TOAST ================= */
    function showToast(msg, type = "info") {
        const toast = document.getElementById("toast");
        if (!toast) return;

        toast.innerText = msg;
        toast.className = "custom-toast show " + type;

        setTimeout(() => {
            toast.classList.remove("show");
        }, 2500);
    }

    /* ================= LOCK BUTTON ================= */
    const btnLocked = document.getElementById("quizLocked");

    if (btnLocked) {
        btnLocked.addEventListener("click", () => {
            showToast("Eksplor minimal 3 alat musik dulu 🎯", "info");

            btnLocked.classList.add("shake");
            setTimeout(() => btnLocked.classList.remove("shake"), 300);
        });
    }

    /* ================= QUIZ MODAL ================= */
    const btnQuiz = document.querySelector(".quiz-trigger");
    const modal = document.getElementById("quizModal");
    const retryBtn = document.querySelector(".quiz-retry");
    const closeBtn = document.querySelector(".quiz-close");

    if (btnQuiz && modal) {

        btnQuiz.addEventListener("click", () => {
            modal.classList.add("active");
            document.body.classList.add("modal-open");
        });

        function closeModal() {
            modal.classList.remove("active");
            document.body.classList.remove("modal-open");
        }

        modal.addEventListener("click", (e) => {
            if (e.target === modal) closeModal();
        });

        closeBtn?.addEventListener("click", closeModal);

        /* ================= QUIZ LOGIC ================= */
        const container = document.querySelector(".quiz-options");

        if (container) {

            const correct = container.dataset.correct;
            const alatId = container.dataset.id;
            const tipeSoal = container.dataset.tipe;

            const buttons = container.querySelectorAll(".quiz-choice");
            const feedback = document.getElementById("quiz-feedback");

            let answered = false;

            buttons.forEach(btn => {
                btn.addEventListener("click", () => {

                    if (answered) return;
                    answered = true;
                    container.classList.add("answered");

                    const val = btn.dataset.value;
                    const isCorrect = val === correct;

                    buttons.forEach(b => b.disabled = true);

                    if (isCorrect) {

                        btn.classList.add("correct");
                        feedback.textContent = "✅ Jawaban benar";

                        document.getElementById("correctSound")?.play();

                        const duration = 800;
                        const end = Date.now() + duration;

                        (function frame() {
                            confetti({
                                particleCount: 6,
                                spread: 70,
                                origin: { x: 0.5, y: 0.5 },
                                zIndex: 10000
                            });

                            if (Date.now() < end) {
                                requestAnimationFrame(frame);
                            }
                        })();

                        showToast("Jawaban benar 👍", "success");

                    } else {

                        btn.classList.add("wrong");
                        feedback.textContent = "❌ Salah. Jawaban benar: " + correct;

                        buttons.forEach(b => {
                            if (b.dataset.value === correct) {
                                b.classList.add("correct");
                            }
                        });

                        showToast("Yah salah 😅 coba lagi", "error");
                    }

                    fetch('/quiz/submit', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            alat_musik_id: alatId,
                            tipe_soal: tipeSoal,
                            is_correct: isCorrect
                        })
                    });

                });
            });

            retryBtn?.addEventListener("click", () => {
                answered = false;
                feedback.textContent = "";

                container.classList.remove("answered");

                buttons.forEach(b => {
                    b.disabled = false;
                    b.classList.remove("correct", "wrong");
                });
            });
        }
    }
    

});