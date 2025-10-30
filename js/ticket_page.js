document.addEventListener("DOMContentLoaded", () => {
  const seatMapSection = document.getElementById("tickets");
  if (!seatMapSection) return; // Lấy tất cả các element

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

  const descriptionContainer = seatMapSection.querySelector(
    "#ticket-description-container"
  ); // Khai báo biến

  let selectedZoneId = null;
  let currentQuantity = 1;
  let basePrice = 0; // === CÁC HÀM TRỢ GIÚP === // --- SỬA ĐỔI 1: SỬA HÀM HIỂN THỊ ĐỂ GIỐNG NHƯ HÌNH ẢNH --- // Hàm hiển thị thông tin vé (Tên và Số vé còn lại)

  function showDescription(name, remaining) {
    if (descriptionContainer) {
      // Tạo HTML giống như hình ảnh bạn gửi
      descriptionContainer.innerHTML = `
        <h4 style="color: #00e0ff;">${name}</h4>
        <p style="margin-top: 15px; color: #fff;">
            <strong> Số vé còn lại: ${remaining} </strong>
        </p>
      `;
      descriptionContainer.style.display = "block"; // Hiển thị khung
    }
  } // Hàm ẩn mô tả

  function resetSelection() {
    selectedZoneId = null;
    basePrice = 0;
    currentQuantity = 1;
    quantityDisplay.textContent = "1";
    totalPriceDisplay.textContent = "0";

    zones.forEach((z) => z.classList.remove("selected"));
    priceItems.forEach((item) => item.classList.remove("selected-row"));

    calculator.classList.remove("visible"); // Sửa: Dùng class "visible"
    hideDescription(); // Ẩn mô tả

    if (nextButton) {
      nextButton.classList.remove("active");
      nextButton.textContent = "Vui lòng chọn vé";
      nextButton.style.cursor = "not-allowed";
      nextButton.style.backgroundColor = ""; // Reset màu nút
    }
  } // Hàm cập nhật tổng tiền (Giữ nguyên)

  function updateTotal() {
    const total = basePrice * currentQuantity;
    totalPriceDisplay.textContent = total.toLocaleString("vi-VN");
  } // --- SỬA ĐỔI 2: CẬP NHẬT LẠI HOÀN TOÀN LOGIC CLICK ---

  zones.forEach((zone) => {
    zone.addEventListener("click", () => {
      // 1. Đọc dữ liệu từ 'data-' (Giả định bạn đã thêm data-remaining vào PHP)
      const currentZoneId = zone.dataset.id;
      const ticketName = zone.dataset.name; // Lấy từ data-name
      const remaining = parseInt(zone.dataset.remaining); // Lấy từ data-remaining // 2. KIỂM TRA HẾT VÉ (SOLD OUT) NGAY LẬP TỨC

      if (zone.classList.contains("sold-out") || remaining === 0) {
        // Hiển thị thông báo hết vé
        descriptionContainer.innerHTML = `
          <h4 style="color: #e6007a;">${ticketName}</h4>
          <p style="color: #fff; font-weight: bold;">Loại vé này đã bán hết.</p>
          <p style="color: #ccc;">Số vé còn lại: 0</p>
        `;
        descriptionContainer.style.display = "block"; // Reset mọi thứ
        resetSelection();
        calculator.classList.remove("visible"); // Ẩn khung số lượng
        return; // Dừng, không làm gì thêm
      } // 3. XỬ LÝ KHI CLICK VÉ CÒN HÀNG

      if (zone.classList.contains("selected")) {
        // Nếu click vào vé đang chọn -> Bỏ chọn
        resetSelection();
      } else {
        // Nếu click vào vé mới
        zones.forEach((z) => z.classList.remove("selected"));
        zone.classList.add("selected");

        priceItems.forEach((item) => item.classList.remove("selected-row"));
        const matchingPriceItem = seatMapSection.querySelector(
          `.price-item[data-id="${currentZoneId}"]`
        );
        if (matchingPriceItem) {
          matchingPriceItem.classList.add("selected-row");
        }

        selectedZoneId = currentZoneId;
        basePrice = ticketData[selectedZoneId].price; // Lấy giá từ object ticketData // Lấy số vé còn lại từ 'ticketData' (cách của bạn cũng OK)

        const ticketsRemaining = ticketData[selectedZoneId].remaining;
        if (currentQuantity > ticketsRemaining) {
          currentQuantity = 1;
        }
        if (currentQuantity === 0 && ticketsRemaining > 0) {
          currentQuantity = 1;
        }

        quantityDisplay.textContent = currentQuantity;
        updateTotal();

        calculator.classList.add("visible"); // Gọi hàm showDescription VỚI THAM SỐ ĐÃ SỬA // (Dùng 'remaining' thay vì 'description')
        showDescription(ticketName, remaining); // Kích hoạt nút "Thanh Toán"

        if (nextButton) {
          nextButton.classList.add("active");
          nextButton.textContent = "Thanh Toán";
          nextButton.style.cursor = "pointer";
          nextButton.style.backgroundColor = "";
        }
      }
    });
  }); // === SỰ KIỆN NÚT + (CỘNG) === (Giữ nguyên logic của bạn)

  plusBtn.addEventListener("click", () => {
    if (selectedZoneId) {
      const ticketsRemaining = ticketData[selectedZoneId].remaining;
      if (currentQuantity < ticketsRemaining) {
        currentQuantity++;
        quantityDisplay.textContent = currentQuantity;
        updateTotal();
      }
    } else {
      // (Phần này có thể bỏ qua, vì không nên cho cộng khi chưa chọn vé)
      // currentQuantity++;
      // quantityDisplay.textContent = currentQuantity;
      // updateTotal();
    }
  }); // Sự kiện nút - (Trừ) (Giữ nguyên)

  minusBtn.addEventListener("click", () => {
    if (currentQuantity > 1) {
      currentQuantity--;
      quantityDisplay.textContent = currentQuantity;
      updateTotal();
    }
  }); // === SỰ KIỆN NÚT "THANH TOÁN" === (Giữ nguyên logic của bạn)

  if (nextButton) {
    nextButton.addEventListener("click", (event) => {
      const urlParams = new URLSearchParams(window.location.search);
      const maSK = urlParams.get("MaSK");

      if (!selectedZoneId) {
        nextButton.textContent = "Vui lòng chọn loại vé!";
        setTimeout(() => {
          nextButton.textContent = "Vui lòng chọn vé";
        }, 2000);
        return;
      }

      const zone = selectedZoneId;
      const qty = currentQuantity;
      const ticketsRemaining = ticketData[zone].remaining;

      if (qty > ticketsRemaining) {
        nextButton.style.backgroundColor = "#e74c3c";
        nextButton.textContent = `Chỉ còn ${ticketsRemaining} vé!`;
        setTimeout(() => {
          nextButton.style.backgroundColor = "";
          nextButton.textContent = "Thanh Toán";
        }, 3000);
        return;
      }

      console.log("Còn vé! Chuyển sang trang thanh toán...");
      window.location.href = `thanhtoan.php?MaSK=${maSK}&zone=${zone}&qty=${qty}`;
    });
  }
});
