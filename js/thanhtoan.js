// === Mã hoàn chỉnh cho thanhtoan.js (ĐÃ GỘP) ===
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

  // Chạy cập nhật lần đầu
  updateTotal();

  // --- LOGIC VALIDATE FORM (ĐÃ GỘP) ---
  const paymentForm = document.getElementById("billing-form");

  // *** THAY ĐỔI: Lấy CÁC element hiển thị lỗi CỤ THỂ ***
  // (Chúng ta không dùng 'form-errors' chung nữa)
  const nameErrorDisplay = document.getElementById("name-error");
  const emailErrorDisplay = document.getElementById("email-error");
  const phoneErrorDisplay = document.getElementById("phone-error");

  // *** BỔ SUNG: Lấy các trường input để validate TRỰC TIẾP (live) ***
  const nameInput = document.getElementById("name");
  const emailInput = document.getElementById("email");
  const phoneInput = document.getElementById("phone");
  const errorColor = "#D9534F"; // Màu đỏ báo lỗi

  // --- Các hàm kiểm tra ---
  function isValidEmail(email) {
    const re =
      /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
  }

  function isValidPhone(phone) {
    // Regex kiểm tra SĐT Việt Nam: 10 số, bắt đầu bằng 0
    const re = /^0\d{9}$/;
    return re.test(String(phone));
  }

  // --- BỔ SUNG: VALIDATE TRỰC TIẾP (LIVE VALIDATION) ---

  // Validate Tên (trên 'blur' - khi người dùng click ra ngoài)
  nameInput.addEventListener("blur", () => {
    if (nameInput.value.trim() === "") {
      nameInput.style.borderColor = errorColor;
      // Cập nhật: Hiển thị lỗi cụ thể
      if (nameErrorDisplay)
        nameErrorDisplay.textContent = "Vui lòng nhập Họ và tên.";
    } else {
      // Cập nhật: Xóa lỗi nếu đã hợp lệ
      if (nameErrorDisplay) nameErrorDisplay.textContent = "";
    }
  });
  // Xóa viền đỏ khi người dùng click lại vào
  nameInput.addEventListener("focus", () => {
    nameInput.style.borderColor = ""; // Xóa viền đỏ
    // Cập nhật: Xóa thông báo lỗi
    if (nameErrorDisplay) nameErrorDisplay.textContent = "";
  });

  // Validate Email (trên 'blur')
  emailInput.addEventListener("blur", () => {
    const email = emailInput.value.trim();
    if (email === "") {
      emailInput.style.borderColor = errorColor;
      if (emailErrorDisplay)
        emailErrorDisplay.textContent = "Vui lòng nhập Email.";
    } else if (!isValidEmail(email)) {
      emailInput.style.borderColor = errorColor;
      if (emailErrorDisplay)
        emailErrorDisplay.textContent = "Định dạng Email không hợp lệ.";
    } else {
      if (emailErrorDisplay) emailErrorDisplay.textContent = "";
    }
  });
  // Xóa viền đỏ khi click lại
  emailInput.addEventListener("focus", () => {
    emailInput.style.borderColor = "";
    if (emailErrorDisplay) emailErrorDisplay.textContent = "";
  });

  // Validate Phone (trên 'blur')
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
  // Xóa viền đỏ khi click lại
  phoneInput.addEventListener("focus", () => {
    phoneInput.style.borderColor = "";
    if (phoneErrorDisplay) phoneErrorDisplay.textContent = "";
  });
  // --- Kết thúc phần bổ sung validation trực tiếp ---

  // --- Logic kiểm tra TỔNG THỂ khi SUBMIT (ĐÃ SỬA) ---
  paymentForm.addEventListener("submit", (event) => {
    // 1. Luôn chặn hành vi gửi form mặc định
    event.preventDefault();

    // --- XÓA LỖI CŨ (NẾU CÓ) ---
    if (nameErrorDisplay) nameErrorDisplay.textContent = "";
    if (emailErrorDisplay) emailErrorDisplay.textContent = "";
    if (phoneErrorDisplay) phoneErrorDisplay.textContent = "";

    // 2. Lấy giá trị
    const name = nameInput.value.trim();
    const email = emailInput.value.trim();
    const phone = phoneInput.value.trim();

    let isValid = true; // Biến cờ

    // 3. Kiểm tra (Đây là lần kiểm tra cuối cùng)
    if (name === "") {
      if (nameErrorDisplay)
        nameErrorDisplay.textContent = "Vui lòng nhập Họ và tên.";
      nameInput.style.borderColor = errorColor;
      isValid = false;
    }
    if (email === "") {
      if (emailErrorDisplay)
        emailErrorDisplay.textContent = "Vui lòng nhập Email.";
      emailInput.style.borderColor = errorColor;
      isValid = false;
    } else if (!isValidEmail(email)) {
      if (emailErrorDisplay)
        emailErrorDisplay.textContent = "Định dạng Email không hợp lệ.";
      emailInput.style.borderColor = errorColor;
      isValid = false;
    }
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

    // 4. Xử lý kết quả
    if (isValid) {
      // Nếu không có lỗi, gửi form đi
      console.log("Form hợp lệ, đang tiến hành gửi...");
      paymentForm.submit();
    } else {
      console.warn("Form có lỗi, đã chặn gửi đi.");
      // Lỗi đã được hiển thị ở từng trường cụ thể
    }
  });
});
