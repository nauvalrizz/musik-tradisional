
// Custom dropdown (filter pencarian)
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('filterForm');
    document.querySelectorAll('[data-cdd]').forEach(function (dd) {
        var btn = dd.querySelector('.cdd-btn');
        var list = dd.querySelector('.cdd-list');
        var hidden = dd.querySelector('input[type="hidden"]');
        var label = btn.querySelector('em');

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var wasOpen = dd.classList.contains('open');
            document.querySelectorAll('[data-cdd].open').forEach(function (o) { o.classList.remove('open'); });
            if (!wasOpen) dd.classList.add('open');
        });

        list.querySelectorAll('button').forEach(function (opt) {
            opt.addEventListener('click', function () {
                hidden.value = opt.dataset.value;
                label.textContent = opt.textContent.replace(/ \u2713/g, '');
                list.querySelectorAll('button').forEach(function (b) { b.classList.remove('sel'); });
                opt.classList.add('sel');
                dd.classList.remove('open');
                if (form) form.submit();
            });
        });
    });
    document.addEventListener('click', function () {
        document.querySelectorAll('[data-cdd].open').forEach(function (o) { o.classList.remove('open'); });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') document.querySelectorAll('[data-cdd].open').forEach(function (o) { o.classList.remove('open'); });
    });
});
