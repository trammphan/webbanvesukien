
$(document).on('click', '.loaisk-link', function(e) {
    
    e.preventDefault(); 
    const maLoai = $(this).data('id'); 
    const tenLoai = $(this).text().trim();
    const chiTietDiv = $('#sukien-chi-tiet');
    const listDiv = $('#sukien-list');

    // Hiển thị loading
    listDiv.html('<i class="fa-solid fa-spinner fa-spin"></i> Đang tải danh sách sự kiện...');
    chiTietDiv.show();
    $('#chi-tiet-title').text('Danh sách Sự kiện thuộc loại "'+tenLoai+'":');

    // Gửi yêu cầu AJAX
    $.ajax({
        url: 'get_sk_by_loai.php', // Tên file mới sẽ tạo ở Bước 3
        type: 'GET',
        data: { maloai: maLoai },
        success: function(response) {
            listDiv.html(response);
        },
        error: function() {
            listDiv.html('<p style="color: red;">Lỗi khi tải dữ liệu sự kiện.</p>');
        }
    });
    // Cuộn màn hình về khu vực hiển thị kết quả
    // $('html, body').animate({
    //     scrollTop: chiTietDiv.offset().top - 20 
    // }, 500);
 }); 

document.addEventListener("DOMContentLoaded", function() {

    // --- 1. KHAI BÁO CÁC PHẦN TỬ (SỬ DỤNG ID CHÍNH XÁC TỪ HTML) ---
    
    // Các Sections (Khu vực nội dung)
    const sectionSukien = document.getElementById("sukien-section");
    const sectionDanhmuc = document.getElementById("danhmuc-section");
    const sectionNguoidung = document.getElementById("nguoidung-section");
    const sectionVe = document.getElementById("ve-section");
    const sectionThongke = document.getElementById("thongke-section");
    const sectionBieuDo = document.getElementById("bieudo-sukien-section"); // ID khu vực biểu đồ mới

    // Các Nút Sidebar (SỬ DỤNG ID CÓ DẤU GẠCH NGANG)
    const btnSukien = document.getElementById("btn-sukien");
    const btnDanhmuc = document.getElementById("btn-danhmuc");
    const btnNguoidung = document.getElementById("btn-nguoidung");
    const btnVe = document.getElementById("btn-ve");
    const btnThongke = document.getElementById("btn-thongke");
    const btnBieuDo = document.getElementById("btn-bieudo");
    
    const sections = [sectionSukien, sectionDanhmuc, sectionNguoidung, sectionVe, sectionThongke, sectionBieuDo];
    const buttons = [btnSukien, btnDanhmuc, btnNguoidung, btnVe, btnThongke, btnBieuDo];
    
    const tabMap = {
        'sukien': { button: btnSukien, section: sectionSukien },
        'danhmuc': { button: btnDanhmuc, section: sectionDanhmuc },
        'nguoidung': { button: btnNguoidung, section: sectionNguoidung },
        've': { button: btnVe, section: sectionVe },
        'thongke': { button: btnThongke, section: sectionThongke },
        'bieudo': { button: btnBieuDo, section: sectionBieuDo } // Thêm tab biểu đồ
    };

    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab') || 'sukien'; 

    if (tabMap[activeTab] && tabMap[activeTab].section && tabMap[activeTab].button) {
        showSection(tabMap[activeTab].section, tabMap[activeTab].button);
        // Nếu chuyển đến tab biểu đồ, gọi loadBieuDoSuKien()
        if (activeTab === 'bieudo') {
             loadBieuDoSuKien();
        }
    } else {
        // Đảm bảo mục 'sukien' vẫn là mặc định nếu không có tham số hợp lệ
        showSection(sectionSukien, btnSukien);
    }
    function loadSukienByLoai(maLoai) {
        const listDiv = $('#sukien-list'); 
        
        // Nếu không có mã loại (chọn "-- Chọn Danh mục --"), xóa danh sách và dừng

        listDiv.html('<p class="text-primary">Đang tải danh sách sự kiện...</p>');

        // 2. Gọi AJAX
        $.ajax({
            url: 'get_sk_by_loai.php', // Tên file PHP xử lý truy vấn
            type: 'GET',
            data: { maloai: maLoai },
            success: function(response) {
                // response là nội dung HTML/PHP đã được render từ file get_sk_by_loai.php
                listDiv.html(response); 
            },
            error: function(xhr, status, error) {
                console.error("Lỗi AJAX khi tải sự kiện theo loại:", status, error);
                listDiv.html('<p style="color: red;">Lỗi khi tải dữ liệu sự kiện. Vui lòng kiểm tra file get_sk_by_loai.php và Console.</p>');
            }
        });
}
    

    let allEventDetailData = []; 
    let uniqueEventsMap = {}; 
    let myChartDoanhThuVeNgay = null;

    window.myChartEventDetail = null;

    // --- 2. HÀM CHUYỂN SECTION ---

    

    function showSection(sectionToShow, clickedButton) {
                [sectionSukien, sectionDanhmuc, sectionNguoidung, sectionVe, sectionThongke, sectionBieuDo]
                .forEach(sec => sec.classList.add("hidden"));
                sectionToShow.classList.remove("hidden");
                [btnSukien, btnDanhmuc, btnNguoidung, btnVe, btnThongke, btnBieuDo]
                .forEach(btn => btn.classList.remove("active"));
                clickedButton.classList.add("active");
            }
    
    // --- 3. HÀM VẼ BIỂU ĐỒ (Đảm bảo logic AJAX gọi file 'bieudo_sukien.php') ---

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

                // Lấy nhãn và giá trị
                const labels = data.map(item => item.label);
                const values = data.map(item => item.value);

                // Tạo và vẽ biểu đồ
                const ctx = document.getElementById('bieuDoSuKienLoai');
                if (ctx) {
                    // Hủy biểu đồ cũ (nếu có)
                    if (window.myChartSuKien) {
                        window.myChartSuKien.destroy();
                    }

                    window.myChartSuKien = new Chart(ctx, {
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

    // Hàm mới để vẽ biểu đồ doanh thu
    function loadBieuDoDoanhThu() {
        $.ajax({
            url: 'bieudo_doanhthu.php', // Gọi file PHP mới
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
                    if (window.myChartDoanhThu) {
                        window.myChartDoanhThu.destroy();
                    }

                    // Vẽ Biểu đồ CỘT (Bar Chart)
                    window.myChartDoanhThu = new Chart(ctx, {
                        type: 'bar', 
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Doanh thu (Triệu VNĐ)',
                                data: values,
                                backgroundColor: '#1cc88a', // Màu xanh lá cây (tiền)
                                borderColor: '#18a571',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: 'y', // Biểu đồ cột ngang (để tên sự kiện dài không bị rối)
                            scales: {
                                x: { // Trục X hiện là giá trị Doanh Thu
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Doanh Thu (Triệu VNĐ)'
                                    },
                                    // Định dạng hiển thị số lớn (có dấu chấm phân cách hàng nghìn)
                                    ticks: {
                                        callback: function(value, index, values) {
                                        // BƯỚC 1: Chia giá trị gốc cho 1,000,000
                                            const valueInMillions = value / 1000000;
                                            
                                            // BƯỚC 2: Định dạng (thêm dấu chấm phân cách hàng nghìn cho phần triệu)
                                            if (valueInMillions >= 1000) {
                                                return valueInMillions.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                            }
                                            return valueInMillions;
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

    function loadBieuDoLuotTruyCap() {
        $.getJSON('get_luot_truy_cap.php', function(response) {
            if (response.success && response.data && response.data.length > 0) {
                const labels = response.data.map(item => item.label);
                const values = response.data.map(item => item.value);

                // Tìm kiếm thẻ canvas bằng ID
                const ctx = document.getElementById('bieuDoLuotTruyCap'); 
                
                // Nếu biểu đồ đã tồn tại, hủy đi để vẽ lại (quan trọng khi load lại dữ liệu)
                if (window.myChartLuotTruyCap) {
                    window.myChartLuotTruyCap.destroy();
                }

                if (ctx) {
                    window.myChartLuotTruyCap = new Chart(ctx, {
                        type: 'bar', // Chọn kiểu biểu đồ bar (cột)
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
                            maintainAspectRatio: false, // Để biểu đồ sử dụng height/width của thẻ div chứa nó
                            scales: {
                                y: {
                                    beginAtZero: true
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
        });
    }

    

    
    function loadBieuDoDoanhThuVeNgay(days = 10) { 
        // Rất quan trọng: Hủy biểu đồ CHI TIẾT sự kiện (nếu đang hiển thị)
        if (window.myChartEventDetail) {
            window.myChartEventDetail.destroy();
            window.myChartEventDetail = null;
        }

        $('#chart-title').text('Biểu đồ Tổng hợp Doanh thu và Vé (' + days + ' ngày gần nhất)'); 
        
        $.getJSON('bieudo_doanhthu_ngay.php', { days: days }, function(response) {
            if (response.success && response.data && response.data.length > 0) {
                
                const ctx = document.getElementById('bieuDoDoanhThuVeNgay');
                if (!ctx) return;
                
                // Hủy biểu đồ TỔNG HỢP cũ nếu có
                if (window.myChartDoanhThuVeNgay) {
                    window.myChartDoanhThuVeNgay.destroy();
                    window.myChartEventDetail = null;
                }

                const labels = response.data.map(item => item.Ngay);
                const revenueData = response.data.map(item => item.TongDoanhThu);
                const ticketData = response.data.map(item => item.TongSoVe);

                // Gán biểu đồ vào biến toàn cục để có thể hủy sau này
               myChartDoanhThuVeNgay = new Chart(ctx, {
                    type: 'bar', // Loại biểu đồ chính là Bar (Doanh thu)
                    data: {
                        labels: labels,
                        datasets: [{
                            type: 'bar',
                            label: 'Doanh thu (Triệu VNĐ)',
                            data: doanhThuValues,
                            backgroundColor: 'rgba(78, 115, 223, 0.7)', // Xanh dương nhạt
                            borderColor: 'rgba(78, 115, 223, 1)',
                            borderWidth: 1,
                            yAxisID: 'yDoanhThu'
                        }, {
                            type: 'line',
                            label: 'Số vé bán',
                            data: soVeValues,
                            backgroundColor: 'rgba(28, 200, 138, 0.9)', // Xanh lá đậm
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
                            title: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        const value = context.parsed.y;
                                        if (context.dataset.type === 'bar') {
                                            // Định dạng lại đơn vị triệu VNĐ cho tooltip
                                            return label + value.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' Triệu VNĐ';
                                        }
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
                                    callback: function(value) {
                                        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                    }
                                }
                            },
                            ySoVe: {
                                type: 'linear',
                                position: 'right', // Trục Y thứ 2 ở bên phải
                                title: {
                                    display: true,
                                    text: 'Số vé bán'
                                },
                                grid: {
                                    drawOnChartArea: false, // Chỉ vẽ grid cho trục bên trái
                                },
                                ticks: {
                                    beginAtZero: true,
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
            } else {
                $('#chart-title').text('Không có giao dịch tổng hợp trong ' + days + ' ngày qua.');
            }
        }).fail(function() {
            console.error("Lỗi khi tải dữ liệu tổng hợp doanh thu.");
        });
    }



function drawEventDetailChart(tenSK, chartData) {
        const ctx = document.getElementById('bieuDoDoanhThuVeNgay');
        if (!ctx) return; // Đảm bảo canvas tồn tại

        const labels = chartData.map(item => item.Ngay);
        const revenueData = chartData.map(item => item.TongDoanhThu);
        const ticketData = chartData.map(item => item.TongSoVe);

        const days = 10;
        // Hủy biểu đồ cũ nếu có
        if (window.myChartDoanhThuVeNgay) {
            window.myChartDoanhThuVeNgay.destroy();
        }
        if (window.myChartEventDetail) {
            window.myChartEventDetail.destroy();
        }


        // Cập nhật tiêu đề
        $('#chart-title').text('Biểu đồ Doanh thu và Vé cho sự kiện: ' + tenSK);

        window.myChartEventDetail = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Doanh Thu (VND)',
                        data: revenueData,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        yAxisID: 'y-doanhthu' // Trục Y cho Doanh thu
                    },
                    {
                        label: 'Số Lượng Vé',
                        data: ticketData,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        type: 'line', // Biểu đồ đường cho Số lượng vé
                        yAxisID: 'y-sove' // Trục Y cho Số vé
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    'y-doanhthu': { // Trục cho Doanh thu
                        type: 'linear', position: 'left',
                        title: { display: true, text: 'Doanh Thu (VND)' },
                        ticks: { callback: function(value) { return value.toLocaleString('vi-VN'); } }
                    },
                    'y-sove': { // Trục cho Số lượng vé
                        type: 'linear', position: 'right',
                        title: { display: true, text: 'Số Lượng Vé' },
                        grid: { drawOnChartArea: false }, min: 0
                    }
                }
            }
        });
    }


// --- Hàm Tải Dữ Liệu và Vẽ Biểu Đồ ---
    function loadEventDetailDataAndDraw(maSK, tenSK, days) {
        if (!maSK) return;
        days = 10;
        // Hiển thị loading khi đang tải
        $('#chart-title').text('Đang tải dữ liệu chi tiết cho: ' + tenSK + '...');
        if (window.myChartEventDetail) { window.myChartEventDetail.destroy(); }
    if (window.myChartDoanhThuVeNgay) { window.myChartDoanhThuVeNgay.destroy(); }

        $.ajax({
            url: 'get_event_detail_data.php', // Tệp PHP mới tạo
            type: 'GET',
            data: { maSK: maSK, days: days },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data && response.data.length > 0) {
                    // Vẽ biểu đồ với dữ liệu mới
                    drawEventDetailChart(TenSK, response.data);
                } else {
                    $('#chart-title').text('Không có dữ liệu giao dịch trong ' + days + ' ngày qua cho sự kiện: ' + tenSK);
                
                }
            },
            error: function() {
                $('#chart-title').text('Lỗi khi tải dữ liệu chi tiết sự kiện.');
               
            }
        });
    }

    

    function fetchAndPopulateEvents(days) {
        // Reset map trước khi fetch
        uniqueEventsMap = {}; 

        $.ajax({
            // THAY ĐỔI ĐƯỜNG DẪN Ở ĐÂY
            url: 'get_event_list.php', // <--- SỬA THÀNH TÊN FILE MỚI
            type: 'GET',
            data: { days: days },
            dataType: 'json', 
            success: function(response) {
                const selectElement = $('#select-event');
                // Đảm bảo chỉ reset dropdown, không xóa data-selected-maSK
                const currentMaSK = selectElement.data('selected-maSK'); 
                
                selectElement.html('<option value="">-- Chọn Sự kiện --</option>'); // Reset dropdown
                
                // KIỂM TRA ĐIỀU KIỆN RESPONSE VÀ CẤU TRÚC JSON
                if (response.success && response.events && response.events.length > 0) { 
                    response.events.forEach(function(event) {
                        // Cập nhật uniqueEventsMap
                        uniqueEventsMap[event.MaSK] = event.TenSK;
                        
                        // Thêm option vào dropdown
                        const isSelected = (event.MaSK == currentMaSK) ? 'selected' : '';
                        selectElement.append(`<option value="${event.MaSK}" ${isSelected}>${event.TenSK}</option>`);
                    });
                    // Nếu có MaSK được chọn trước đó, kích hoạt sự kiện change để load lại biểu đồ chi tiết
                    if (currentMaSK && uniqueEventsMap[currentMaSK]) {
                        // Sử dụng .trigger('change') nếu cần load lại dữ liệu ngay
                        // selectElement.trigger('change'); 
                        // Hoặc gọi trực tiếp hàm vẽ biểu đồ:
                        const tenSK = uniqueEventsMap[currentMaSK];
                        // Bạn sẽ cần đảm bảo biến allEventDetailData được cập nhật lại trước khi gọi hàm này
                        // drawEventDetailChart(currentMaSK, tenSK, allEventDetailData); 
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


    // --- 4. GÁN EVENT LISTENER CHO CÁC NÚT ---
    
    // Sử dụng if(btn) để đảm bảo nút tồn tại trước khi gán sự kiện
    btnSukien && btnSukien.addEventListener("click", () => showSection(sectionSukien, btnSukien));
    btnDanhmuc && btnDanhmuc.addEventListener("click", () => showSection(sectionDanhmuc, btnDanhmuc));
    btnNguoidung && btnNguoidung.addEventListener("click", () => showSection(sectionNguoidung, btnNguoidung));
    btnVe && btnVe.addEventListener("click", () => showSection(sectionVe, btnVe));
    
    // Nút Thống kê (hiển thị bảng)
    btnThongke && btnThongke.addEventListener("click", () => {
        showSection(sectionThongke, btnThongke);
    });

    // NÚT BIỂU ĐỒ MỚI (FIX LỖI BẤM KHÔNG ĂN)
    if (btnBieuDo) {
        btnBieuDo.addEventListener("click", (e) => {
            e.preventDefault(); // Ngăn hành vi mặc định của thẻ <a> (nếu nút là <a>)
            showSection(sectionBieuDo, btnBieuDo); // Chuyển sang section biểu đồ
            loadBieuDoSuKien(); // Tải và vẽ biểu đồ
            loadBieuDoDoanhThu();
            loadBieuDoDoanhThuVeNgay();
            loadBieuDoLuotTruyCap();
            const selectedDays = $('#select-days').val(); 
            fetchAndPopulateEvents(selectedDays);
        });
    }
    $('#select-event').off('change').on('change', function() {
        const maSK = $(this).val();
        if (maSK) {
            // Lưu lại MaSK
            $(this).data('selected-maSK', maSK); 
            const tenSK = uniqueEventsMap[maSK];
            
            // Lấy số ngày hiện tại
            const selectedDays = $('#select-days').val();
            
            // GỌI HÀM MỚI ĐỂ TẢI DỮ LIỆU VÀ VẼ
            loadEventDetailDataAndDraw(maSK, tenSK, selectedDays); 
            
        } else {
            // Xóa biểu đồ và tải lại biểu đồ Tổng hợp nếu cần
            $('#chart-title').text('Vui lòng chọn một Sự kiện để xem chi tiết');
            if (window.myChartEventDetail) {
                window.myChartEventDetail.destroy();
            }
            $('#chart-title').text('Biểu đồ Tổng hợp Doanh thu và Vé'); 
            loadBieuDoDoanhThuVeNgay(); 
        }
    });

    // Listener cho việc thay đổi KHOẢNG THỜI GIAN
   $('#select-days').off('change').on('change', function() {
        const selectedDays = $(this).val();
        
        const currentMaSK = $('#select-event').data('selected-maSK'); 
        const tenSK = $('#select-event option:selected').text(); 
        
        // 1. Tải lại danh sách sự kiện
        fetchAndPopulateEvents(selectedDays); 

        // 2. Nếu đã có sự kiện được chọn, tải lại biểu đồ chi tiết theo ngày mới
        if (currentMaSK) {
            loadEventDetailDataAndDraw(currentMaSK, tenSK, selectedDays); 
        } else {
            // Nếu chưa chọn sự kiện, tải lại biểu đồ TỔNG HỢP (nếu bạn có hàm đó)
            // loadBieuDoDoanhThuVeNgay(selectedDays); 
        }
    });
    
    
    
    

}); 
