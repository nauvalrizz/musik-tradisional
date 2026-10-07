document.addEventListener("DOMContentLoaded", () => {

    const scoreEl = document.getElementById('score');
    const resultText = document.getElementById('resultText');
    const percentText = document.getElementById('percentText');
    const reviewContainer = document.getElementById('reviewContainer');
    const toggleBtn = document.getElementById('toggleReview');

    const score = parseInt(localStorage.getItem("quiz_score")) || 0;
    const total = parseInt(localStorage.getItem("quiz_total")) || 0;
    const history = JSON.parse(localStorage.getItem('quiz_history')) || [];

    if (!localStorage.getItem('quiz_score')) {
        window.location.href = '/quiz-global';
        return;
    }

    const percent = total > 0
        ? Math.round((score / (total * 10)) * 100)
        : 0;

    animateScore(score);
    percentText.innerText = percent + '%';

    let text = "";

    if (percent === 100) {
        text = "Sempurna.";
    } else if (percent >= 80) {
        text = "Sangat baik.";
    } else if (percent >= 60) {
        text = "Cukup baik.";
    } else {
        text = "Masih bisa ditingkatkan.";
    }

    resultText.innerText = text;

    if (percent >= 80 && typeof confetti === "function") {
        setTimeout(() => {
            confetti({
                particleCount: 120,
                spread: 70,
                origin: { y: 0.6 }
            });
        }, 500);
    }

    reviewContainer.style.display = 'none';

    toggleBtn.onclick = () => {
        if (reviewContainer.style.display === 'none') {
            renderReview();
            reviewContainer.style.display = 'block';
            toggleBtn.innerText = 'Tutup Review';
        } else {
            reviewContainer.style.display = 'none';
            toggleBtn.innerText = 'Lihat Review Jawaban';
        }
    };

    function renderReview() {
        reviewContainer.innerHTML = '';

        history.forEach((item, i) => {

            // 🔥 kondisi jawaban user
            let userAnswerHtml = '';

            if (item.selected && item.selected !== "") {
                userAnswerHtml = `
                <p>Jawaban kamu: 
                    <span class="${item.is_correct ? 'correct' : 'wrong'}">
                        ${item.selected}
                    </span>
                </p>
            `;
            } else {
                userAnswerHtml = `
                <p class="text-muted">Waktu habis</p>
            `;
            }

            reviewContainer.innerHTML += `
            <div class="review-item">
                <p><strong>${i + 1}. ${item.question}</strong></p>

                ${userAnswerHtml}

                ${!item.is_correct
                    ? `<p>Jawaban benar: 
                        <span class="correct">${item.correct}</span>
                        </p>`
                    : ''
                }
            </div>
        `;
        });
    }

    function animateScore(finalScore) {
        let current = 0;

        const interval = setInterval(() => {
            current += Math.ceil(finalScore / 30);

            if (current >= finalScore) {
                current = finalScore;
                clearInterval(interval);
            }

            scoreEl.innerText = current;
        }, 30);
    }
});