document.addEventListener("DOMContentLoaded", () => {
  // --- LẤY CÁC ELEMENT TỪ HTML ---
  const quantityDisplay = document.getElementById("quantity-display");
  const plusBtn = document.getElementById("plus-btn");
  const minusBtn = document.getElementById("minus-btn");
  const mainTotalPriceDisplay = document.getElementById("total-price");
  const hiddenQuantityInput = document.getElementById("hidden-quantity");
  const hiddenTotalInput = document.getElementById("hidden-total");

  // Lấy giá và số lượng
  const basePrice = TICKET_BASE_PRICE;
  const maxQuantity = MAX_AVAILABLE_TICKETS;
  let initialQuantity = parseInt(hiddenQuantityInput.value);

  // Ràng buộc số lượng ban đầu
  if (initialQuantity > maxQuantity) {
    initialQuantity = maxQuantity;
  }
  if (initialQuantity === 0 && maxQuantity > 0) {
    initialQuantity = 1;
  }
  let currentQuantity = initialQuantity;

  // Hàm cập nhật tổng
  function updateTotal() {
    const total = basePrice * currentQuantity;
    quantityDisplay.textContent = currentQuantity;
    mainTotalPriceDisplay.textContent = total.toLocaleString("vi-VN");
    hiddenQuantityInput.value = currentQuantity;
    hiddenTotalInput.value = total;
  }

  // Sự kiện nút
  plusBtn.addEventListener("click", () => {
    if (currentQuantity < maxQuantity) {
      currentQuantity++;
      updateTotal();
    } else {
      console.warn("Đã đạt số lượng vé tối đa có thể mua.");
    }
  });

  minusBtn.addEventListener("click", () => {
    if (currentQuantity > 1) {
      currentQuantity--;
      updateTotal();
    }
  });
  updateTotal();

  const paymentForm = document.getElementById("billing-form");
  const phoneErrorDisplay = document.getElementById("phone-error");

  const nameInput = document.getElementById("name");
  const phoneInput = document.getElementById("phone");
  const errorColor = "#D9534F";

  function isValidPhone(phone) {
    const re = /^0\d{9}$/;
    return re.test(String(phone));
  }

  phoneInput.addEventListener("blur", () => {
    const phone = phoneInput.value.trim();
    if (phone === "") {
      phoneInput.style.borderColor = errorColor;
      if (phoneErrorDisplay)
        phoneErrorDisplay.textContent = "Vui lòng nhập Số điện thoại.";
    } else if (!isValidPhone(phone)) {
      phoneInput.style.borderColor = errorColor;
      if (phoneErrorDisplay)
        phoneErrorDisplay.textContent =
          "Số điện thoại không hợp lệ (Yêu cầu 10 số, bắt đầu bằng 0).";
    } else {
      if (phoneErrorDisplay) phoneErrorDisplay.textContent = "";
    }
  });
  phoneInput.addEventListener("focus", () => {
    phoneInput.style.borderColor = "";
    if (phoneErrorDisplay) phoneErrorDisplay.textContent = "";
  });

  paymentForm.addEventListener("submit", (event) => {
    event.preventDefault();
    if (phoneErrorDisplay) phoneErrorDisplay.textContent = "";
    // Xóa lỗi thẻ
    document
      .querySelectorAll(".form-error-text")
      .forEach((el) => (el.textContent = ""));

    // 2. Lấy giá trị
    const phone = phoneInput.value.trim();

    let isValid = true;
    if (phone === "") {
      if (phoneErrorDisplay)
        phoneErrorDisplay.textContent = "Vui lòng nhập Số điện thoại.";
      phoneInput.style.borderColor = errorColor;
      isValid = false;
    } else if (!isValidPhone(phone)) {
      if (phoneErrorDisplay)
        phoneErrorDisplay.textContent =
          "Số điện thoại không hợp lệ (Yêu cầu 10 số, bắt đầu bằng 0).";
      phoneInput.style.borderColor = errorColor;
      isValid = false;
    }

    const selectedMethod = document.querySelector(
      'input[name="payment_method"]:checked'
    ).value;

    if (selectedMethod === "card") {
      const cardName = document
        .getElementById("customer_card_name")
        .value.trim();
      const cardNum = document
        .getElementById("customer_card_number")
        .value.trim();
      const cardExpiry = document
        .getElementById("customer_card_expiry")
        .value.trim();
      const cardCvv = document.getElementById("customer_card_cvv").value.trim();

      if (cardName === "") {
        document.getElementById("card-name-error").textContent =
          "Vui lòng nhập tên trên thẻ.";
        isValid = false;
      }
      if (cardNum === "") {
        // Thêm kiểm tra regex 16 số...
        document.getElementById("card-number-error").textContent =
          "Vui lòng nhập số thẻ.";
        isValid = false;
      }
      if (cardExpiry === "") {
        // Thêm kiểm tra regex MM/YY...
        document.getElementById("card-expiry-error").textContent =
          "Vui lòng nhập ngày hết hạn.";
        isValid = false;
      }
      if (cardCvv === "") {
        // Thêm kiểm tra regex 3-4 số...
        document.getElementById("card-cvv-error").textContent =
          "Vui lòng nhập CVV.";
        isValid = false;
      }
    }

    if (selectedMethod === "bank") {
      const bankRef = document.getElementById("customer_bank_ref").value.trim();
    }

    // 4. Xử lý kết quả
    if (isValid) {
      // Nếu không có lỗi, gửi form đi
      console.log("Form hợp lệ, đang tiến hành gửi...");
      paymentForm.submit();
    } else {
      console.warn("Form có lỗi, đã chặn gửi đi.");
    }
  });

  // Lấy các element
  const paymentRadios = document.querySelectorAll(
    'input[name="payment_method"]'
  );
  const momoInfo = document.getElementById("momo-payment-info");
  const cardInfo = document.getElementById("card-payment-info");
  const bankInfo = document.getElementById("bank-payment-info");
  const allInfoBoxes = [momoInfo, cardInfo, bankInfo];

  // Hàm cập nhật hiển thị
  function updatePaymentDetails() {
    // Lấy giá trị của radio đang được chọn
    const selectedMethod = document.querySelector(
      'input[name="payment_method"]:checked'
    ).value;

    // 1. Ẩn tất cả các hộp thông tin
    allInfoBoxes.forEach((box) => {
      if (box) box.style.display = "none";
    });

    // 2. Xóa lỗi cũ trong các trường (nếu có)
    document
      .querySelectorAll(".form-error-text")
      .forEach((el) => (el.textContent = ""));

    // 3. Hiển thị hộp thông tin tương ứng
    if (selectedMethod === "momo" && momoInfo) {
      momoInfo.style.display = "block";
    } else if (selectedMethod === "card" && cardInfo) {
      cardInfo.style.display = "block";
    } else if (selectedMethod === "bank" && bankInfo) {
      bankInfo.style.display = "block";
    }
  }

  paymentRadios.forEach((radio) => {
    radio.addEventListener("change", updatePaymentDetails);
  });

  updatePaymentDetails();
});
