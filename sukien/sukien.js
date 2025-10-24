document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('event-filter');
    const container = document.getElementById('event-list');

    //Xu ly toggle bo loc
    function toggleFilter() {
        const details = document.getElementById("filter-details");
        details.style.display = details.style.display === "none" || details.style.display === "" ? "block" : "none";
    }

    //Loc su kien
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(form);
        const diadiem = formData.get('diadiem');
        const loai = formData.get('loai_sukien');

        const params = new URLSearchParams();
        if (diadiem) params.append('diadiem', diadiem);
        if (loai) params.append('loai_sukien', loai);

        fetch(`http://localhost/php/webbanvesukien/sukien/sukien.php?${params.toString()}`)
            .then(res => res.json())
            .then(data => {
                container.innerHTML = '';
                if (data.length === 0) {
                    container.innerHTML = '<p>Không tìm thấy sự kiện phù hợp.</p>';
                    return;
                }
                data.forEach(event => {
                    const card = document.createElement('div');
                    card.className = 'event-card';
                    card.innerHTML = `
                        <img src="${event.img_sukien}" alt="${event.TenSK}">
                        <div class="gia">Từ ${Number(event.Gia).toLocaleString('vi-VN')}đ</div>
                        <h3>${event.TenSK}</h3>
                        <p>Thời gian: ${event.Tgian}</p>
                        <a href="${window.location.origin}/php/webbanvesukien/detail_sukien/chitietsk_1.php?MaSK=${encodeURIComponent(event.MaSK)}"><button>Xem chi tiết</button></a>
                    `;
                    container.appendChild(card);
                });
            });
        });
});

function toggleFilter() {
    const details = document.getElementById("filter-details");
    details.style.display = details.style.display === "none" || details.style.display === "" ? "block" : "none";
}

    //Chuyển hướng từ trang chủ sang trang sự kiện theo đúng thể loại
window.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const loai = params.get('loai_sukien');

    if (loai) {
        // Gán giá trị vào select thể loại
        const selectLoai = document.querySelector('select[name="loai_sukien"]');
        if (selectLoai) {
        selectLoai.value = loai;
        }

    // Tự động submit form lọc
    document.getElementById('event-filter').dispatchEvent(new Event('submit'));
    }
});