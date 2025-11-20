$(document).ready(function() { // Đảm bảo mọi thứ chạy sau khi DOM đã được tải

    // =================================================================
    // 1. KHAI BÁO BIẾN TOÀN CỤC VÀ SỰ KIỆN DANH MỤC
    // =================================================================
    
    // Biến toàn cục để lưu dữ liệu và biểu đồ (Sử dụng let/window cho nhất quán)
    let uniqueEventsMap = {}; 
    let myChartDoanhThuVeNgay = null;
    let myChartEventDetail = null;
    let myChartSuKien = null;
    let myChartDoanhThu = null;
    let myChartLuotTruyCap = null;
    
    // Xử lý Sự kiện khi click vào Danh mục (Phần này đã đúng, giữ nguyên)
    $(document).on('click', '.loaisk-link', function(e) {
        
        e.preventDefault(); 
        const maLoai = $(this).data('id'); 
        const tenLoai = $(this).text().trim();
        const chiTietDiv = $('#sukien-chi-tiet');
        const listDiv = $('#sukien-list');

        listDiv.html('<i class="fa-solid fa-spinner fa-spin"></i> Đang tải danh sách sự kiện...');
        chiTietDiv.show();
        $('#chi-tiet-title').text('Danh sách Sự kiện thuộc loại "'+tenLoai+'":');

        $.ajax({
            url: 'get_sk_by_loai.php', 
            type: 'GET',
            data: { maloai: maLoai },
            success: function(response) {
                listDiv.html(response);
            },
            error: function() {
                listDiv.html('<p style="color: red;">Lỗi khi tải dữ liệu sự kiện.</p>');
            }
        });
    }); 

    // =================================================================
    // 2. KHAI BÁO PHẦN TỬ VÀ HÀM CHUYỂN SECTION
    // =================================================================

    // Các Sections (Khu vực nội dung)
    const sectionSukien = document.getElementById("sukien-section");
    const sectionDanhmuc = document.getElementById("danhmuc-section");
    const sectionNguoidung = document.getElementById("nguoidung-section");
    const sectionVe = document.getElementById("ve-section");
    const sectionThongke = document.getElementById("thongke-section");
    const sectionBieuDo = document.getElementById("bieudo-sukien-section"); 

    // Các Nút Sidebar
    const btnSukien = document.getElementById("btn-sukien");
    const btnDanhmuc = document.getElementById("btn-danhmuc");
    const btnNguoidung = document.getElementById("btn-nguoidung");
    const btnVe = document.getElementById("btn-ve");
    const btnThongke = document.getElementById("btn-thongke");
    const btnBieuDo = document.getElementById("btn-bieudo");
    
    // Gom các phần tử vào mảng (dùng cho hàm showSection an toàn hơn)
    const sections = [sectionSukien, sectionDanhmuc, sectionNguoidung, sectionVe, sectionThongke, sectionBieuDo];
    const buttons = [btnSukien, btnDanhmuc, btnNguoidung, btnVe, btnThongke, btnBieuDo];
    
    // --- HÀM CHUYỂN SECTION AN TOÀN (FIX LỖI CANNOT READ PROPERTIES OF NULL) ---
    function showSection(sectionToShow, clickedButton) {
        
        // 1. Ẩn tất cả các section (chỉ xử lý nếu phần tử tồn tại)
        sections.forEach(sec => {
            if (sec) sec.classList.add("hidden");
        });
        // 2. Hiện section được chọn
        if (sectionToShow) sectionToShow.classList.remove("hidden");
        
        // 3. Xóa trạng thái active của tất cả các nút (chỉ xử lý nếu phần tử tồn tại)
        buttons.forEach(btn => {
            if (btn) btn.classList.remove("active");
        });
        // 4. Đặt trạng thái active cho nút được click
        if (clickedButton) clickedButton.classList.add("active");
    }

    // =================================================================
    // 3. HÀM VẼ BIỂU ĐỒ VÀ XỬ LÝ DỮ LIỆU
    // =================================================================
    
    // Hàm này giữ nguyên
    function loadSukienByLoai(maLoai) {
        const listDiv = $('#sukien-list'); 
        listDiv.html('<p class="text-primary">Đang tải danh sách sự kiện...</p>');
        $.ajax({
            url: 'get_sk_by_loai.php', 
            type: 'GET',
            data: { maloai: maLoai },
            success: function(response) {
                listDiv.html(response); 
            },
            error: function(xhr, status, error) {
                console.error("Lỗi AJAX khi tải sự kiện theo loại:", status, error);
                listDiv.html('<p style="color: red;">Lỗi khi tải dữ liệu sự kiện. Vui lòng kiểm tra file get_sk_by_loai.php và Console.</p>');
            }
        });
    }

    // Hàm vẽ Biểu đồ Số lượng Sự kiện theo Loại (Doughnut)
    function loadBieuDoSuKien() {
        $.ajax({
            url: 'bieudo_sukien.php', 
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data && data.error) {
                    console.error("Lỗi từ CSDL:", data.error);
                    $('#bieuDoSuKienLoai').parent().html('<p class="text-danger">Lỗi CSDL: ' + data.error + '</p>');
                    return;
                }
                const labels = data.map(item => item.label);
                const values = data.map(item => item.value);
                const ctx = document.getElementById('bieuDoSuKienLoai');
                if (ctx) {
                    if (myChartSuKien) { myChartSuKien.destroy(); }
                    myChartSuKien = new Chart(ctx, {
                        type: 'doughnut', 
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Số lượng Sự kiện',
                                data: values,
                                backgroundColor: [
                                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
                                ],
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'right' },
                                title: { display: false }
                            }
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi tải dữ liệu biểu đồ:", status, error);
                $('#bieuDoSuKienLoai').parent().html('<p class="text-danger">Không thể tải dữ liệu biểu đồ.</p>');
            }
        });
    }

    // Hàm vẽ Biểu đồ Doanh thu theo Sự kiện (Bar - Ngang)
    function loadBieuDoDoanhThu() {
        $.ajax({
            url: 'bieudo_doanhthu.php', 
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data && data.error) {
                    console.error("Lỗi từ CSDL (Doanh Thu):", data.error);
                    $('#bieuDoDoanhThuSuKien').parent().html('<p class="text-danger">Lỗi CSDL khi tải Doanh Thu.</p>');
                    return;
                }

                const labels = data.map(item => item.label);
                const values = data.map(item => item.value);

                const ctx = document.getElementById('bieuDoDoanhThuSuKien');
                if (ctx) {
                    if (myChartDoanhThu) { myChartDoanhThu.destroy(); }

                    myChartDoanhThu = new Chart(ctx, {
                        type: 'bar', 
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Doanh thu (Triệu VNĐ)',
                                data: values,
                                backgroundColor: '#1cc88a', 
                                borderColor: '#18a571',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: 'y', 
                            scales: {
                                x: { 
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Doanh Thu (Triệu VNĐ)'
                                    },
                                    ticks: {
                                        callback: function(value, index, values) {
                                        // BƯỚC 1: Chia giá trị gốc cho 1,000,000 (đã bị bỏ qua trong code cũ)
                                        // Giả sử dữ liệu trả về từ server đã là đơn vị triệu hoặc đã được chia. 
                                        // Nếu dữ liệu trả về là VNĐ, bạn cần sửa file PHP hoặc thêm logic chia ở đây:
                                        // const valueInMillions = value / 1000000;
                                            const valueInMillions = value; // Giữ nguyên theo logic code cũ
                                            
                                            if (valueInMillions >= 1000) {
                                                // Sửa lỗi cú pháp .toString().replace() trong code cũ
                                                return valueInMillions.toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                            }
                                            return valueInMillions.toFixed(2);
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: { display: false },
                                title: { display: false }
                            }
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi tải dữ liệu Doanh Thu:", status, error);
                $('#bieuDoDoanhThuSuKien').parent().html('<p class="text-danger">Không thể tải dữ liệu Doanh Thu.</p>');
            }
        });
    }

    // Hàm vẽ Biểu đồ Lượt Truy Cập (Bar - Dọc)
    function loadBieuDoLuotTruyCap() {
        $.getJSON('get_luot_truy_cap.php', function(response) {
            if (response.success && response.data && response.data.length > 0) {
                const labels = response.data.map(item => item.label);
                const values = response.data.map(item => item.value);

                const ctx = document.getElementById('bieuDoLuotTruyCap'); 
                
                if (myChartLuotTruyCap) { myChartLuotTruyCap.destroy(); }

                if (ctx) {
                    myChartLuotTruyCap = new Chart(ctx, {
                        type: 'bar', 
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Lượt Truy Cập',
                                data: values,
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false, 
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            if (Number.isInteger(value)) {
                                                return value;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            } else {
                $('#bieuDoLuotTruyCap').parent().html('<p class="text-center text-muted">Không có dữ liệu lượt truy cập để hiển thị.</p>');
            }
        }).fail(function() {
            console.error("Lỗi khi tải dữ liệu lượt truy cập từ server.");
            $('#bieuDoLuotTruyCap').parent().html('<p class="text-danger">Lỗi khi tải dữ liệu lượt truy cập.</p>');
        });
    }

    // Hàm vẽ Biểu đồ TỔNG HỢP Doanh thu và Vé (Bar & Line)
    function loadBieuDoDoanhThuVeNgay(days = 10) { 
        // Hủy biểu đồ CHI TIẾT sự kiện (nếu đang hiển thị)
        if (window.myChartEventDetail) {
            window.myChartEventDetail.destroy();
            window.myChartEventDetail = null;
        }

        $('#chart-title').text('Biểu đồ Tổng hợp Doanh thu và Vé (' + days + ' ngày gần nhất)'); 
        
        $.getJSON('bieudo_doanhthu_ngay.php', { days: days }, function(response) {
            if (response.success && response.data && response.data.length > 0) {
                
                const ctx = document.getElementById('bieuDoDoanhThuVeNgay');
                if (!ctx) return;
                
                // Hủy biểu đồ TỔNG HỢP cũ
                if (window.myChartDoanhThuVeNgay) {
                    window.myChartDoanhThuVeNgay.destroy();
                    window.myChartDoanhThuVeNgay = null; // Đặt lại biến
                }

                const labels = response.data.map(item => item.Ngay);
                // SỬA LỖI: Cần chia giá trị tiền tệ cho 1,000,000 để hiển thị 'Triệu VNĐ'
                const revenueData = response.data.map(item => item.TongDoanhThu / 1000000); 
                const ticketData = response.data.map(item => item.TongSoVe);

                // SỬA LỖI: Thay thế doanhThuValues và soVeValues bằng revenueData và ticketData
                window.myChartDoanhThuVeNgay = new Chart(ctx, {
                type: 'bar', 
                data: {
                    labels: labels,
                    datasets: [{
                        type: 'bar',
                        label: 'Doanh thu (Triệu VNĐ)',
                        data: revenueData, // ĐÃ SỬA
                        backgroundColor: 'rgba(78, 115, 223, 0.7)', 
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 1,
                        yAxisID: 'yDoanhThu'
                    }, {
                        type: 'line',
                        label: 'Số vé bán',
                        data: ticketData, // ĐÃ SỬA
                        backgroundColor: 'rgba(28, 200, 138, 0.9)', 
                        borderColor: 'rgba(28, 200, 138, 1)',
                        borderWidth: 2,
                        fill: false,
                        yAxisID: 'ySoVe'
                    }]
                },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        const value = context.parsed.y;
                                        if (context.dataset.type === 'bar') {
                                            // Định dạng cho tooltip (Triệu VNĐ)
                                            return label + value.toFixed(2).toLocaleString('vi-VN') + ' Triệu VNĐ';
                                        }
                                        // Định dạng cho tooltip (Vé)
                                        return label + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' Vé';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Ngày'
                                }
                            },
                            yDoanhThu: {
                                type: 'linear',
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Doanh thu (Triệu VNĐ)'
                                },
                                ticks: {
                                    // Định dạng dấu chấm phân cách hàng nghìn cho đơn vị Triệu VNĐ
                                    callback: function(value) {
                                        return value.toLocaleString('vi-VN'); 
                                    }
                                }
                            },
                            ySoVe: {
                                type: 'linear',
                                position: 'right', 
                                title: {
                                    display: true,
                                    text: 'Số vé bán'
                                },
                                grid: {
                                    drawOnChartArea: false, 
                                },
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(value) {
                                        if (Number.isInteger(value)) {
                                            return value.toLocaleString('vi-VN');
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                $('#chart-title').text('Không có giao dịch tổng hợp trong ' + days + ' ngày qua.');
                if (myChartDoanhThuVeNgay) myChartDoanhThuVeNgay.destroy();
            }
        }).fail(function() {
            console.error("Lỗi khi tải dữ liệu tổng hợp doanh thu.");
            $('#chart-title').text('Lỗi khi tải dữ liệu tổng hợp doanh thu.');
        });
    }


    function drawEventDetailChart(tenSK, chartData) {
    const ctx = document.getElementById('bieuDoDoanhThuVeNgay');
    if (!ctx) return; 

    // --- BƯỚC SỬA LỖI CHÍNH: TRÍCH XUẤT DỮ LIỆU TỪ THAM SỐ chartData ---
    // Khai báo và khởi tạo các mảng dữ liệu cần thiết cho Chart.js
    const labels = chartData.map(item => item.Ngay);
    const revenueData = chartData.map(item => item.TongDoanhThu); 
    const ticketData = chartData.map(item => item.TongSoVe);
    // ------------------------------------------------------------------

    // Hủy biểu đồ cũ (Biểu đồ Tổng hợp hoặc Biểu đồ Chi tiết)
    if (window.myChartDoanhThuVeNgay) { window.myChartDoanhThuVeNgay.destroy(); window.myChartDoanhThuVeNgay = null; }
    if (window.myChartEventDetail) { window.myChartEventDetail.destroy(); window.myChartEventDetail = null; }

    // Cập nhật tiêu đề
    $('#chart-title').text('Biểu đồ Doanh thu và Vé cho sự kiện: ' + tenSK);

    // Vẽ biểu đồ chi tiết sự kiện
    window.myChartEventDetail = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Doanh Thu (VND)',
                    data: revenueData, // ĐÃ SỬA: Sử dụng biến đã trích xuất
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    yAxisID: 'y-doanhthu' 
                },
                {
                    label: 'Số Lượng Vé',
                    data: ticketData, // ĐÃ SỬA: Sử dụng biến đã trích xuất
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    type: 'line', 
                    yAxisID: 'y-sove' 
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            const value = context.parsed.y;
                            // Định dạng số tiền/số vé theo chuẩn Việt Nam (có dấu chấm phân cách hàng nghìn)
                            return label + value.toLocaleString('vi-VN');
                        }
                    }
                }
            },
            scales: {
                'y-doanhthu': { 
                    type: 'linear', position: 'left',
                    title: { display: true, text: 'Doanh Thu (VND)' },
                    ticks: { 
                        callback: function(value) { 
                            return value.toLocaleString('vi-VN'); 
                        } 
                    }
                },
                'y-sove': { 
                    type: 'linear', position: 'right',
                    title: { display: true, text: 'Số Lượng Vé' },
                    grid: { drawOnChartArea: false }, min: 0,
                    ticks: {
                        callback: function(value) {
                            if (Number.isInteger(value)) {
                                return value.toLocaleString('vi-VN');
                            }
                        }
                    }
                }
            }
        }
    });
}

    // Hàm Tải Dữ Liệu và Vẽ Biểu Đồ Chi tiết Sự kiện
    function loadEventDetailDataAndDraw(maSK, tenSK, days) {
        if (!maSK) return;
        
        // *SỬA LỖI 1: BỎ DÒNG HARDCODE days = 10;*
        // days = 10; 
        
        // *SỬA LỖI 2: Dùng biến days được truyền vào để hiển thị tiêu đề*
        $('#chart-title').text('Đang tải dữ liệu chi tiết cho: ' + tenSK + '...');
        if (window.myChartEventDetail) { window.myChartEventDetail.destroy(); }
        if (window.myChartDoanhThuVeNgay) { window.myChartDoanhThuVeNgay.destroy(); }

        $.ajax({
            url: 'get_event_detail_data.php', 
            type: 'GET',
            data: { maSK: maSK, days: days }, // Gửi tham số days
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data && response.data.length > 0) {
                    // *SỬA LỖI 3: Gọi hàm với tên biến đúng là tenSK*
                    drawEventDetailChart(tenSK, response.data); 
                } else {
                    // *Dùng biến days được truyền vào để hiển thị số ngày*
                    $('#chart-title').text('Không có dữ liệu giao dịch trong ' + days + ' ngày qua cho sự kiện: ' + tenSK);
                }
            },
            error: function() {
                $('#chart-title').text('Lỗi khi tải dữ liệu chi tiết sự kiện.');
               
            }
        });
    }

    // Hàm Tải Danh sách Sự kiện cho Dropdown (giữ nguyên logic)
    function fetchAndPopulateEvents(days) {
        uniqueEventsMap = {}; 

        $.ajax({
            url: 'get_event_list.php', 
            type: 'GET',
            data: { days: days },
            dataType: 'json', 
            success: function(response) {
                const selectElement = $('#select-event');
                const currentMaSK = selectElement.data('selected-maSK'); 
                
                selectElement.html('<option value="">-- Chọn Sự kiện --</option>'); 
                
                if (response.success && response.events && response.events.length > 0) { 
                    response.events.forEach(function(event) {
                        uniqueEventsMap[event.MaSK] = event.TenSK;
                        
                        const isSelected = (event.MaSK == currentMaSK) ? 'selected' : '';
                        selectElement.append(`<option value="${event.MaSK}" ${isSelected}>${event.TenSK}</option>`);
                    });
                    
                    if (currentMaSK && uniqueEventsMap[currentMaSK]) {
                        const tenSK = uniqueEventsMap[currentMaSK];
                        loadEventDetailDataAndDraw(currentMaSK, tenSK, days);
                    }
                } else {
                    console.log("Không tìm thấy sự kiện hoặc lỗi khi fetch:", response.error);
                }
            },
            error: function(xhr, status, error) {
                console.error("Lỗi AJAX khi tải danh sách sự kiện:", error); 
            }
        });
    }


    // =================================================================
    // 4. KHỞI TẠO BAN ĐẦU VÀ GÁN EVENT LISTENER
    // =================================================================

    // Xử lý section mặc định khi load trang
    const tabMap = {
        'sukien': { button: btnSukien, section: sectionSukien },
        'danhmuc': { button: btnDanhmuc, section: sectionDanhmuc },
        'nguoidung': { button: btnNguoidung, section: sectionNguoidung },
        've': { button: btnVe, section: sectionVe },
        'thongke': { button: btnThongke, section: sectionThongke },
        'bieudo': { button: btnBieuDo, section: sectionBieuDo } 
    };

    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab') || 'sukien'; 

    if (tabMap[activeTab] && tabMap[activeTab].section && tabMap[activeTab].button) {
        showSection(tabMap[activeTab].section, tabMap[activeTab].button);
        if (activeTab === 'bieudo') {
             loadBieuDoSuKien();
             loadBieuDoDoanhThu();
             loadBieuDoDoanhThuVeNgay();
             loadBieuDoLuotTruyCap();
             const selectedDays = $('#select-days').val(); 
             fetchAndPopulateEvents(selectedDays);
        }
    } else {
        // Cần đảm bảo btnSukien và sectionSukien không null ở đây
        if(sectionSukien && btnSukien) {
             showSection(sectionSukien, btnSukien);
        }
    }


    // Gán Event Listener cho các nút Sidebar
    // (Giữ nguyên cách kiểm tra an toàn đã có)
    btnSukien && btnSukien.addEventListener("click", () => showSection(sectionSukien, btnSukien));
    btnDanhmuc && btnDanhmuc.addEventListener("click", () => showSection(sectionDanhmuc, btnDanhmuc));
    btnNguoidung && btnNguoidung.addEventListener("click", () => showSection(sectionNguoidung, btnNguoidung));
    btnVe && btnVe.addEventListener("click", () => showSection(sectionVe, btnVe));
    
    // Nút Thống kê
    btnThongke && btnThongke.addEventListener("click", () => {
        showSection(sectionThongke, btnThongke);
    });

    // NÚT BIỂU ĐỒ MỚI
    if (btnBieuDo) {
        btnBieuDo.addEventListener("click", (e) => {
            e.preventDefault(); 
            showSection(sectionBieuDo, btnBieuDo); 
            // Tải lại tất cả biểu đồ khi chuyển sang tab Biểu đồ
            loadBieuDoSuKien(); 
            loadBieuDoDoanhThu();
            loadBieuDoDoanhThuVeNgay();
            loadBieuDoLuotTruyCap();
            const selectedDays = $('#select-days').val(); 
            fetchAndPopulateEvents(selectedDays);
        });
    }

    // Listener cho Dropdown Chọn Sự kiện (Đã sửa lỗi logic và thêm kiểm tra)
    $('#select-event').off('change').on('change', function() {
        const maSK = $(this).val();
        const selectedDays = $('#select-days').val(); 

        if (maSK) {
            $(this).data('selected-maSK', maSK); 
            const tenSK = uniqueEventsMap[maSK];
            
            loadEventDetailDataAndDraw(maSK, tenSK, selectedDays); 
            
        } else {
            $(this).data('selected-maSK', null); 
            $('#chart-title').text('Biểu đồ Tổng hợp Doanh thu và Vé'); 
            loadBieuDoDoanhThuVeNgay(selectedDays); 
        }
    });

    // Listener cho việc thay đổi KHOẢNG THỜI GIAN (Đã sửa lỗi logic)
   $('#select-days').off('change').on('change', function() {
        const selectedDays = $(this).val();
        
        // 1. Tải lại danh sách sự kiện (và tự động tải lại chi tiết nếu có MaSK)
        fetchAndPopulateEvents(selectedDays); 

        // 2. Nếu không có sự kiện nào được chọn, tải lại biểu đồ TỔNG HỢP theo ngày mới
        const currentMaSK = $('#select-event').data('selected-maSK'); 
        if (!currentMaSK) {
             loadBieuDoDoanhThuVeNgay(selectedDays); 
        }
    });
    
});