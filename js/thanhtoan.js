/* --- BẮT ĐẦU FILE thanhtoan.js (Đã sửa) --- */

document.addEventListener("DOMContentLoaded", () => {
  // Lấy các element hiển thị cho người dùng
  const quantityDisplay = document.getElementById("quantity-display-checkout");
  const totalPriceDisplay = document.getElementById("total-price");
  const plusBtn = document.querySelector(".plus-btn-checkout");
  const minusBtn = document.querySelector(".minus-btn-checkout");

  // *** QUAN TRỌNG: Lấy các trường input ẨN để gửi form ***
  const hiddenQuantityInput = document.getElementById("hidden-quantity");
  const hiddenTotalInput = document.getElementById("hidden-total");

  // Lấy giá vé cơ bản (được PHP chèn vào)
  // TICKET_BASE_PRICE được định nghĩa trong thẻ <script> ở tệp .php
  const basePrice = TICKET_BASE_PRICE;
  let currentQuantity = parseInt(quantityDisplay.textContent);

  // Hàm cập nhật MỌI THỨ
  function updateTotal() {
    // 1. Tính toán
    const total = basePrice * currentQuantity;

    // 2. Cập nhật phần HIỂN THỊ cho người dùng
    quantityDisplay.textContent = currentQuantity;
    totalPriceDisplay.textContent = total.toLocaleString("vi-VN");

    // 3. Cập nhật các trường input ẨN để chuẩn bị gửi form
    hiddenQuantityInput.value = currentQuantity;
    hiddenTotalInput.value = total;
  }

  // --- Gắn sự kiện ---

  plusBtn.addEventListener("click", () => {
    currentQuantity++;
    updateTotal();
  });

  minusBtn.addEventListener("click", () => {
    if (currentQuantity > 1) {
      // Không cho phép giảm dưới 1
      currentQuantity--;
      updateTotal();
    }
  });

  // Chúng ta KHÔNG cần 'form.addEventListener("submit")'
  // vì chúng ta muốn form được gửi đi theo cách HTML tiêu chuẩn.
  // Các trường input ẩn đã được cập nhật sẵn sàng.
});

/* --- KẾT THÚC FILE thanhtoan.js --- */
