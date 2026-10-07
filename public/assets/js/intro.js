/* Intro: tirai pembuka — sekali per sesi, failsafe berlapis. */
(function () {
    var curtain = document.getElementById('introCurtain');
    if (!curtain) return;

    function hide(instant) {
        document.body.classList.remove('intro-lock');
        try { sessionStorage.setItem('musantara_intro', '1'); } catch (e) {}
        if (instant) {
            curtain.style.display = 'none';
            return;
        }
        curtain.classList.add('lift');
        setTimeout(function () { curtain.style.display = 'none'; }, 950);
    }

    var seen = false;
    try { seen = !!sessionStorage.getItem('musantara_intro'); } catch (e) {}

    if (seen) {
        hide(true);
        return;
    }

    document.body.classList.add('intro-lock');
    setTimeout(function () { hide(false); }, 1000);
    /* hard failsafe: halaman tidak boleh ketahan */
    setTimeout(function () { hide(true); }, 5000);
})();
