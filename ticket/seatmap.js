/* --- BẮT ĐẦU FILE seatmap.js --- */

// Code này chạy sau khi trang đã tải
// Nó sẽ đọc biến 'ticketData' đã được file .php định nghĩa
document.addEventListener("DOMContentLoaded", () => {
  const seatMapSection = document.getElementById("tickets");
  if (!seatMapSection) return;

  // Lấy tất cả các element
  const zones = seatMapSection.querySelectorAll(".seat-zone");
  const priceItems = seatMapSection.querySelectorAll(".price-item");
  const nextButton = seatMapSection.querySelector("#next-btn");
  const calculator = seatMapSection.querySelector("#ticket-calculator");
  const quantityDisplay = seatMapSection.querySelector("#quantity-display");
  const totalPriceDisplay = seatMapSection.querySelector(
    "#total-price-display"
  );
  const plusBtn = seatMapSection.querySelector("#plus-btn");
  const minusBtn = seatMapSection.querySelector("#minus-btn");

  // Khai báo biến
  let selectedZoneId = null;
  let currentQuantity = 1;
  let basePrice = 0;

  // Hàm cập nhật tổng tiền
  function updateTotal() {
    const total = basePrice * currentQuantity;
    totalPriceDisplay.textContent = total.toLocaleString("vi-VN");
  }

  // Sự kiện click vào khu vực
  zones.forEach((zone) => {
    zone.addEventListener("click", () => {
      const currentZoneId = zone.dataset.id;
      selectedZoneId = currentZoneId;

      // Cập nhật giao diện
      zones.forEach((z) => z.classList.remove("selected"));
      zone.classList.add("selected");
      priceItems.forEach((item) => item.classList.remove("selected-row"));
      const matchingPriceItem = seatMapSection.querySelector(
        `.price-item[data-id="${currentZoneId}"]`
      );
      if (matchingPriceItem) {
        matchingPriceItem.classList.add("selected-row");
      }

      // Cập nhật tính toán
      // 'ticketData' được lấy từ file .php
      basePrice = ticketData[selectedZoneId].price;
      currentQuantity = 1;
      quantityDisplay.textContent = currentQuantity;
      updateTotal();
      calculator.classList.add("visible");

      // Cập nhật nút
      if (nextButton) {
        nextButton.classList.add("active");
        nextButton.textContent = "Thanh Toán";
        nextButton.style.cursor = "pointer";
      }
    });
  });

  // Sự kiện nút +
  plusBtn.addEventListener("click", () => {
    currentQuantity++;
    quantityDisplay.textContent = currentQuantity;
    updateTotal();
  });

  // Sự kiện nút -
  minusBtn.addEventListener("click", () => {
    if (currentQuantity > 1) {
      currentQuantity--;
      quantityDisplay.textContent = currentQuantity;
      updateTotal();
    }
  });

  // Sự kiện nút "Tiếp theo"
  if (nextButton) {
    nextButton.addEventListener("click", () => {
      if (selectedZoneId) {
        const zone = selectedZoneId;
        const qty = currentQuantity;
        // Chuyển trang
        window.location.href = `thanhtoan.php?zone=${zone}&qty=${qty}`;
      } else {
        alert("Vui lòng chọn một khu vực trước.");
      }
    });
  }
});

