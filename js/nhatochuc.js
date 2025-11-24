document.addEventListener("DOMContentLoaded", () => {
    const btns = { qly: document.getElementById("btn-qly"), xembc: document.getElementById("btn-xembc") };
    const sections = { nhatochuc: document.querySelector(".nhatochuc"), taosk: document.getElementById("taosk-section"), qly: document.getElementById("qly-section"), xembc: document.getElementById("xembc-section") };
    const qlyLists = { saptoi: document.querySelector('.qly-list-saptoi'), daqua: document.querySelector('.qly-list-daqua'), choduyet: document.querySelector('.qly-list-khac'), nhap: document.querySelector('.qly-list-khac') };

    Object.values(sections).forEach(s => s?.classList.add("hidden"));
    
    if (new URLSearchParams(window.location.search).has('event_id')) {
        sections.qly?.classList.remove("hidden");
        btns.qly?.classList.add("active");
        sections.nhatochuc?.classList.add("hidden");
    } else {
        sections.nhatochuc?.classList.remove("hidden");
        btns.qly?.classList.remove("active");
    }

    const showSection = (name) => {
        Object.values(sections).forEach(s => s?.classList.add("hidden"));
        sections[name]?.classList.remove("hidden");
        Object.values(btns).forEach(b => b?.classList.remove("active"));
        btns[name]?.classList.add("active");
    };

    Object.keys(btns).forEach(n => btns[n]?.addEventListener("click", () => showSection(n)));
    document.getElementById("btn-create-qly")?.addEventListener("click", (e) => { e.preventDefault(); showSection("taosk"); });

    document.querySelectorAll('#qly-section .tabs .tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('#qly-section .tabs .tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            Object.values(qlyLists).forEach(l => l?.classList.add('hidden'));
            qlyLists[tab.dataset.status]?.classList.remove('hidden');
        });
    });

    document.querySelectorAll('.qly-card .btn-manage').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const menu = btn.nextElementSibling;
            if (!menu?.classList.contains('manage-menu')) return;
            menu.classList.toggle('show');
            document.querySelectorAll('.manage-menu').forEach(m => m !== menu && m.classList.remove('show'));
        });
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.qly-card-actions')) document.querySelectorAll('.manage-menu').forEach(m => m?.classList.remove('show'));
    });

    document.querySelectorAll('.manage-menu button').forEach(btn => btn.addEventListener('click', () => btn.closest('.manage-menu')?.classList.remove('show')));

    const togglePanel = (btn, type) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const id = btn.getAttribute('data-event-id');
            if (!id) return;
            document.querySelectorAll('.orders-panel, .revenue-panel').forEach(p => p.classList.add('hidden'));
            const p = document.getElementById(`${type}-panel-${id}`);
            btn.closest('.manage-menu')?.classList.remove('show');
            p?.classList.remove('hidden');
        });
    };

    document.querySelectorAll('.btn-orders').forEach(b => togglePanel(b, 'orders'));
    document.querySelectorAll('.btn-revenue').forEach(b => togglePanel(b, 'revenue'));
    document.querySelectorAll('.orders-close, .revenue-close').forEach(b => b.addEventListener('click', (e) => { e.preventDefault(); b.closest('.orders-panel, .revenue-panel')?.classList.add('hidden'); }));

    const dlModalEl = document.getElementById('downloadSuccessModal');
    if (dlModalEl && window.bootstrap) {
        const modal = new bootstrap.Modal(dlModalEl);
        document.querySelectorAll('.btn-download-report').forEach(btn => btn.addEventListener('click', (e) => {
            e.preventDefault(); modal.show(); setTimeout(() => modal.hide(), 1500);
        }));
    }

    const qlyInput = document.querySelector('#qly-section .searchbar input[name="q"]');
    const filterQly = () => {
        const q = qlyInput?.value.trim().toLowerCase();
        document.querySelectorAll('#qly-section .qly-events-list:not(.hidden) .qly-card').forEach(card => {
            card.style.display = !q || (card.querySelector('.qly-card-title')?.textContent || '').toLowerCase().includes(q) ? '' : 'none';
        });
    };
    qlyInput?.addEventListener('input', filterQly);
    document.querySelector('#qly-section .searchbar')?.addEventListener('submit', (e) => { e.preventDefault(); filterQly(); });

    const slVe = document.getElementById("soloaive"), khungVe = document.getElementById("khungloaive");
    if (slVe && khungVe) {
        slVe.addEventListener("change", function() {
            const n = parseInt(this.value);
            khungVe.innerHTML = "";
            if (n > 0 && n <= 10) {
                for (let i = 1; i <= n; i++) {
                    khungVe.insertAdjacentHTML('beforeend', `<div class="ve-item"><input type="text" placeholder="Tên vé ${i}" name="loaive_ten[]" required><input type="number" min="0" placeholder="SL vé ${i}" name="loaive_soluong[]" required><input type="number" min="0" placeholder="Giá vé ${i}" name="loaive_gia[]" required></div>`);
                }
            } else if (n > 10) khungVe.innerHTML = "<p>Tối đa 10 loại vé.</p>";
        });
    }
});