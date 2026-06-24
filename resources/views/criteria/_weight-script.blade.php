<script>
(function () {
    const inp   = document.getElementById('weightInput');
    const pct   = document.getElementById('weightPct');
    const warn  = document.getElementById('weightWarn');
    if (!inp) return;
    const remaining = parseFloat(inp.dataset.remaining || '1');

    function refresh() {
        const val = parseFloat(inp.value || 0);
        pct.textContent = Math.round((val || 0) * 100) + '%';
        if (val > remaining + 0.0001) {
            warn.classList.remove('d-none');
            inp.classList.add('is-invalid');
        } else {
            warn.classList.add('d-none');
            inp.classList.remove('is-invalid');
        }
    }
    inp.addEventListener('input', refresh);
    refresh();
})();
</script>
