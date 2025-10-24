/* --- BẮT ĐẦU FILE seatmap.js (ĐÃ CẬP NHẬT) --- */

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

  // === THÊM MỚI: Lấy element chứa mô tả ===
  const descriptionContainer = seatMapSection.querySelector(
    "#ticket-description-container"
  );

  // Khai báo biến
  let selectedZoneId = null;
  let currentQuantity = 1;
  let basePrice = 0;

  // === CÁC HÀM TRỢ GIÚP (ĐÃ CẬP NHẬT) ===

  // Hàm hiển thị mô tả
  function showDescription(name, description) {
    if (descriptionContainer) {
      descriptionContainer.innerHTML = `<h4>${name}</h4><p>${description}</p>`;
      descriptionContainer.style.display = "block"; // Hiển thị khung
    }
  }

  // Hàm ẩn mô tả
  function hideDescription() {
    if (descriptionContainer) {
      descriptionContainer.innerHTML = "";
      descriptionContainer.style.display = "none"; // Ẩn khung
    }
  }

  // Hàm reset toàn bộ lựa chọn
  function resetSelection() {
    selectedZoneId = null;
    basePrice = 0;
    currentQuantity = 1;
    quantityDisplay.textContent = "1";
    totalPriceDisplay.textContent = "0";

    zones.forEach((z) => z.classList.remove("selected"));
    priceItems.forEach((item) => item.classList.remove("selected-row"));

    // Sử dụng class .visible của bạn
    calculator.classList.remove("visible");
    hideDescription(); // Ẩn mô tả

    if (nextButton) {
      nextButton.classList.remove("active");
      nextButton.textContent = "Vui lòng chọn vé";
      nextButton.style.cursor = "not-allowed";
    }
  }

  // Hàm cập nhật tổng tiền (Giữ nguyên của bạn)
  function updateTotal() {
    const total = basePrice * currentQuantity;
    totalPriceDisplay.textContent = total.toLocaleString("vi-VN");
  }

  // === VIẾT LẠI SỰ KIỆN CLICK VÀO KHU VỰC VÉ ===
  zones.forEach((zone) => {
    zone.addEventListener("click", () => {
      const currentZoneId = zone.dataset.id;

      // Lấy thông tin mô tả và tên vé
      const description = zone.dataset.description;
      const ticketName = ticketData[currentZoneId].name;

      // TRƯỜNG HỢP 1: Bấm vào vé đang được chọn (để HỦY)
      if (zone.classList.contains("selected")) {
        resetSelection();
      }
      // TRƯỜNG HỢP 2: Bấm vào vé mới
      else {
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
        selectedZoneId = currentZoneId; // Đặt ID đã chọn
        basePrice = ticketData[selectedZoneId].price;
        currentQuantity = 1;
        quantityDisplay.textContent = currentQuantity;
        updateTotal();

        // Hiển thị mọi thứ
        calculator.classList.add("visible"); // Dùng class của bạn
        showDescription(ticketName, description); // HIỂN THỊ MÔ TẢ

        // Cập nhật nút
        if (nextButton) {
          nextButton.classList.add("active");
          nextButton.textContent = "Thanh Toán";
          nextButton.style.cursor = "pointer";
        }
      }
    });
  });

  // Sự kiện nút + (Giữ nguyên của bạn)
  plusBtn.addEventListener("click", () => {
    currentQuantity++;
    quantityDisplay.textContent = currentQuantity;
    updateTotal();
  });

  // Sự kiện nút - (Giữ nguyên của bạn)
  minusBtn.addEventListener("click", () => {
    if (currentQuantity > 1) {
      currentQuantity--;
      quantityDisplay.textContent = currentQuantity;
      updateTotal();
    }
  });

  // Sự kiện nút "Tiếp theo" (Giữ nguyên của bạn)
  if (nextButton) {
    nextButton.addEventListener("click", () => {
      if (selectedZoneId) {
        const zone = selectedZoneId;
        const qty = currentQuantity;
        // Chuyển trang
        // Quan trọng: Phải truyền cả MaSK
        const urlParams = new URLSearchParams(window.location.search);
        const maSK = urlParams.get("MaSK");

        window.location.href = `thanhtoan.php?MaSK=${maSK}&zone=${zone}&qty=${qty}`;
      } else {
        alert("Vui lòng chọn một khu vực trước.");
      }
    });
  }
});
/* --- KẾT THÚC FILE seatmap.js --- */
