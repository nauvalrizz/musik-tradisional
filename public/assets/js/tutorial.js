document.addEventListener("DOMContentLoaded", () => {

    const modal = document.getElementById("tutorialModal");
    const img = document.getElementById("tutorialImg");
    const title = document.getElementById("tutorialTitle");
    const desc = document.getElementById("tutorialDesc");
    const stepText = document.getElementById("tutorialStep");
    const bar = document.getElementById("tutorialBar");

    const next = document.getElementById("tutorialNext");
    const close = document.getElementById("tutorialClose");

    if (!modal || !img || !title || !desc) return;

    let step = 0;

    const steps = [
        {
            img: "/assets/img/tutorial/tutorial-map.png",
            title: "Pilih Pulau",
            desc: "Klik pulau atau gunakan pencarian"
        },
        {
            img: "/assets/img/tutorial/tutorial-card.png",
            title: "Pilih Alat Musik",
            desc: "Klik alat musik untuk melihat detail"
        },
        {
            img: "/assets/img/tutorial/tutorial-detail.png",
            title: "Pelajari Detail",
            desc: "Lihat informasi dan suara alat musik"
        },
        {
            img: "/assets/img/tutorial/tutorial-quiz.png",
            title: "Mulai Kuis",
            desc: "Setelah 3 eksplor, kamu bisa mulai kuis"
        }
    ];

    function showStep() {
        const s = steps[step];

        img.src = s.img;
        title.innerText = s.title;
        desc.innerText = s.desc;

        stepText.innerText = `${step + 1}/${steps.length}`;

        const percent = ((step + 1) / steps.length) * 100;
        bar.style.width = percent + "%";
    }

    function openModal() {
        modal.classList.add("active");
        step = 0;
        showStep();
    }

    function nextStep() {
        step++;
        if (step >= steps.length) {
            modal.classList.remove("active");
        } else {
            showStep();
        }
    }

    next?.addEventListener("click", nextStep);
    close?.addEventListener("click", () => modal.classList.remove("active"));

    if (!localStorage.getItem("tutorial_done")) {
        setTimeout(openModal, 800);
        localStorage.setItem("tutorial_done", "true");
    }

});