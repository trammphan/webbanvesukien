document.addEventListener('DOMContentLoaded', function() {
          // Lấy form bằng ID
          const formDK = document.getElementById('form_dk');
          
          // Nếu form tồn tại, gắn trình xử lý sự kiện
          if (formDK) {
              formDK.addEventListener('dk_submit', validateRegistrationForm);
          }
      });

      /**
       * Hàm kiểm tra xác thực dữ liệu form đăng ký
       * @param {Event} e Sự kiện submit
       */
      function validateRegistrationForm(e) {
          // Ngăn chặn việc gửi form mặc định để thực hiện xác thực
          e.preventDefault();

          // Lấy giá trị từ các trường input
          const userNameInput = document.getElementById('user_name');
          const telInput = document.getElementById('tel');
          const emailInput = document.getElementById('email');
          const passwordInput = document.getElementById('password');
          // Lưu ý: Form dangky.php hiện tại không có password_again. 
          // Nếu muốn kiểm tra, bạn cần thêm <input type="password" id="password_again" ...> vào form HTML.
          
          // Lấy giá trị
          const user_name = userNameInput.value.trim();
          const tel = telInput.value.trim();
          const email = emailInput.value.trim();
          const password = passwordInput.value;

          let isValid = true; // Cờ theo dõi trạng thái hợp lệ

          // --- 1. Kiểm tra các ô không được để trống (Required Fields) ---
          if (user_name === "") {
              alert("Họ và tên không được để trống!");
              userNameInput.focus();
              isValid = false;
          } else if (tel === "") {
              alert("Số điện thoại không được để trống!");
              telInput.focus();
              isValid = false;
          } else if (email === "") {
              alert("Email đăng nhập không được để trống!");
              emailInput.focus();
              isValid = false;
          } else if (password === "") {
              alert("Mật khẩu không được để trống!");
              passwordInput.focus();
              isValid = false;
          }
          
          if (!isValid) return; // Dừng lại nếu có trường rỗng

          // --- 2. Kiểm tra định dạng số điện thoại (10 hoặc 11 số) ---
          // Regex đơn giản cho 10-11 chữ số
          const telRegex = /^\d{10,11}$/; 
          if (!telRegex.test(tel)) {
              alert("Số điện thoại không hợp lệ. Vui lòng nhập 10 hoặc 11 chữ số.");
              telInput.focus();
              return;
          }


          // --- 3. Kiểm tra định dạng mật khẩu (Tối thiểu 5 ký tự)
          if (password.length < 5) {
            alert(
                "Mật khẩu phải có tối thiểu 5 ký tự."
            );
              passwordInput.focus();
              return;
          }


          // Nếu tất cả các kiểm tra đều PASS
          // Gửi form đi
          this.submit(); 
          // Lưu ý: Việc kiểm tra email đã tồn tại PHẢI được thực hiện trên máy chủ (luuthongtin.php)
          // vì dữ liệu LocalStorage không còn được sử dụng.
}  

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el))