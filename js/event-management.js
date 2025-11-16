// Hàm hiển thị/ẩn menu quản lý
function toggleManageMenu(button) {
    // Tìm dropdown menu tương ứng
    const dropdown = button.nextElementSibling;
    
    // Nếu dropdown đang mở, đóng tất cả dropdown khác trước
    if (!dropdown.classList.contains('show')) {
        // Đóng tất cả các dropdown đang mở
        document.querySelectorAll('.manage-dropdown').forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    }
    
    // Toggle dropdown hiện tại
    dropdown.classList.toggle('show');
}

// Đóng dropdown khi click ra ngoài
document.addEventListener('click', function(event) {
    // Nếu click không phải vào nút quản lý hoặc dropdown
    if (!event.target.closest('.manage-btn') && !event.target.closest('.manage-dropdown')) {
        // Đóng tất cả các dropdown
        document.querySelectorAll('.manage-dropdown').forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    }
});

// Xử lý sự kiện khi click vào các mục trong dropdown
document.addEventListener('click', function(event) {
    const dropdownItem = event.target.closest('.manage-dropdown a');
    if (dropdownItem) {
        // Lấy URL từ thuộc tính href
        const url = dropdownItem.getAttribute('href');
        if (url) {
            // Chuyển hướng đến URL tương ứng
            window.location.href = url;
        }
    }
});

// Thêm sự kiện khi chuyển tab
const tabs = document.querySelectorAll('.tab');
tabs.forEach(tab => {
    tab.addEventListener('click', function() {
        // Xóa class active khỏi tất cả các tab
        tabs.forEach(t => t.classList.remove('active'));
        // Thêm class active cho tab được chọn
        this.classList.add('active');
        
        // Ẩn tất cả các danh sách sự kiện
        document.querySelectorAll('.qly-list').forEach(list => {
            list.classList.add('hidden');
        });
        
        // Hiển thị danh sách sự kiện tương ứng
        const target = this.getAttribute('data-status');
        const targetList = document.querySelector(`.qly-list-${target}`);
        if (targetList) {
            targetList.classList.remove('hidden');
        }
    });
});
