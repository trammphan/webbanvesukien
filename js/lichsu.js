document.addEventListener("DOMContentLoaded", function () {
  // 1. Lấy tất cả các 'order-header'
  const orderHeaders = document.querySelectorAll(".order-header"); // 2. Thêm sự kiện click cho mỗi cái

  orderHeaders.forEach((header) => {
    header.addEventListener("click", function () {
      // 3. Tìm 'order-item' cha gần nhất
      const orderItem = this.closest(".order-item"); // 4. Bật/tắt class 'active' trên 'order-item' cha đó //    Đây là logic chính để mở và đóng khi click lại

      orderItem.classList.toggle(
        "active"
      ); /* // 5. (PHẦN THAY ĐỔI) //    Phần code tự động đóng các mục khác đã được xóa bỏ theo yêu cầu. */
    });
  });
});
