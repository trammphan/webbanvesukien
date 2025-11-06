document.addEventListener("DOMContentLoaded", function () {
  // 1. Lấy tất cả các 'order-header'
  const orderHeaders = document.querySelectorAll(".order-header");

  // 2. Thêm sự kiện click cho mỗi cái
  orderHeaders.forEach((header) => {
    header.addEventListener("click", function () {
      // 3. Tìm 'order-item' cha gần nhất
      const orderItem = this.closest(".order-item");

      // 4. Bật/tắt class 'active' trên 'order-item' cha đó
      orderItem.classList.toggle("active");

      // 5. (Tùy chọn) Đóng tất cả các cái khác
      orderHeaders.forEach((otherHeader) => {
        const otherItem = otherHeader.closest(".order-item");
        if (otherItem !== orderItem && otherItem.classList.contains("active")) {
          otherItem.classList.remove("active");
        }
      });
    });
  });
});
