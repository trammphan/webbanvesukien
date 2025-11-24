document.addEventListener("DOMContentLoaded", function () {
  // 1. Lấy tất cả các 'order-header'
  const orderHeaders = document.querySelectorAll(".order-header"); // 2. Thêm sự kiện click cho mỗi cái

  orderHeaders.forEach((header) => {
    header.addEventListener("click", function () {
      // 3. Tìm 'order-item' cha gần nhất
      const orderItem = this.closest(".order-item"); // 4. Bật/tắt class 'active' trên 'order-item' cha đó //    Đây là logic chính để mở và đóng khi click lại
      const statusEl = orderItem.querySelector(".status");
      const statusText = statusEl ? statusEl.textContent.trim() : "";

      // Nếu trạng thái là "Đã hoàn vé" hoặc "Đã hoàn tiền" hoặc "Đã hủy" thì KHÔNG toggle
      if (statusText === "Đã hoàn vé" || statusText === "Đã hủy") {
        return; // dừng, không mở body
      }

      // Ngược lại thì toggle như bình thường
      orderItem.classList.toggle("active");
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const refundForms = document.querySelectorAll("form[action='hoanve.php']");
  const popup = document.getElementById("refund-popup");
  const confirmBtn = document.getElementById("popup-confirm");
  const cancelBtn = document.getElementById("popup-cancel");
  let currentForm = null;

  //Ngăn show nội dung khi nhấn nút hoàn vé
  refundForms.forEach((form) => {
    form.addEventListener("click", function (e) {
      e.stopPropagation(); // chặn sự kiện click lan ra header
    });

    form.addEventListener("submit", function (e) {
      e.preventDefault(); // chặn submit mặc định
      currentForm = form;
      popup.style.display = "flex"; // hiện popup
    });

    confirmBtn.addEventListener("click", function () {
      if (currentForm) currentForm.submit(); // gửi form thật sự
      popup.style.display = "none";
    });

    cancelBtn.addEventListener("click", function () {
      popup.style.display = "none"; // đóng popup
    });
  });
});
