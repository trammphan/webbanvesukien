// --- BẮT ĐẦU FILE thanhtoan.js ---
document.addEventListener("DOMContentLoaded", () => {
  const quantityDisplay = document.getElementById("quantity-display-checkout");
  const totalPriceDisplay = document.getElementById("total-price");
  const plusBtn = document.querySelector(".plus-btn-checkout");
  const minusBtn = document.querySelector(".minus-btn-checkout");
  const form = document.getElementById("billing-form");

  // Lấy các element chứa thông tin vé
  const ticketNameDisplay = document.getElementById("ticket-name");

  // Lấy giá vé cơ bản (được PHP chèn vào)
  const basePrice = TICKET_BASE_PRICE;
  let currentQuantity = parseInt(quantityDisplay.textContent);

  function updateTotal() {
    const total = basePrice * currentQuantity;
    quantityDisplay.textContent = currentQuantity;
    totalPriceDisplay.textContent = total.toLocaleString("vi-VN");
  }

  plusBtn.addEventListener("click", () => {
    currentQuantity++;
    updateTotal();
  });

  minusBtn.addEventListener("click", () => {
    if (currentQuantity > 1) {
      currentQuantity--;
      updateTotal();
    }
  });

  // --- BẮT ĐẦU PHẦN CẬP NHẬT ---
  form.addEventListener("submit", async (event) => {
    // 1. Ngăn form gửi đi theo cách truyền thống
    event.preventDefault();

    // Lấy nút submit và đổi text (ví dụ: "Đang xử lý...")
    const submitButton = form.querySelector(".submit-button");
    submitButton.textContent = "Đang xử lý...";
    submitButton.disabled = true;

    // 2. Thu thập dữ liệu từ form
    const customerData = {
      name: document.getElementById("name").value,
      email: document.getElementById("email").value,
      phone: document.getElementById("phone").value,
      paymentMethod: document.querySelector('input[name="payment"]:checked')
        .value,
    };

    // 3. Thu thập dữ liệu đơn hàng
    const orderData = {
      ticketName: ticketNameDisplay.textContent,
      quantity: currentQuantity,
      // Chuyển tổng tiền về dạng số (bỏ dấu chấm)
      totalPrice: parseInt(totalPriceDisplay.textContent.replace(/\./g, "")),
    };

    // 4. Gửi TẤT CẢ dữ liệu đến file process_payment.php
    try {
      const response = await fetch("process_payment.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        // Gộp 2 object dữ liệu lại và gửi đi
        body: JSON.stringify({ ...customerData, ...orderData }),
      });

      const result = await response.json();

      // 5. Xử lý kết quả
      if (result.success) {
        // THÀNH CÔNG! Chuyển hướng đến trang Cảm ơn
        window.location.href = "cam-on.php";
      } else {
        // THẤT BẠI! Hiển thị lỗi
        alert(
          "Thanh toán thất bại: " + (result.message || "Lỗi không xác định")
        );
        submitButton.textContent = "Xác nhận Thanh toán";
        submitButton.disabled = false;
      }
    } catch (error) {
      // Lỗi mạng hoặc server
      alert("Đã xảy ra lỗi kết nối. Vui lòng thử lại.");
      submitButton.textContent = "Xác nhận Thanh toán";
      submitButton.disabled = false;
    }
  });
  // --- KẾT THÚC PHẦN CẬP NHẬT ---
});
// --- KẾT THÚC FILE thanhtoan.js ---
