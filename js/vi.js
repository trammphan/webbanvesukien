document.addEventListener('DOMContentLoaded', function() {
    const formDK = document.getElementById('form_dk');
    const submitButton = document.getElementById('dk_submit');
    
    // Tạo container để hiển thị lỗi Server-side ngay trên form
    const errorMessageContainer = document.createElement('div');
    errorMessageContainer.id = 'server-error-messages';
    errorMessageContainer.style.color = '#dc3545'; // Màu đỏ cho lỗi
    errorMessageContainer.style.marginBottom = '15px';
    errorMessageContainer.style.textAlign = 'center';
    
    if (formDK) {
        // Thêm div hiển thị lỗi ngay trên form
        formDK.prepend(errorMessageContainer);
        formDK.addEventListener('submit', validateAndSubmitForm);
    }

    async function validateAndSubmitForm(e) {
        e.preventDefault(); 
        
        // Xóa các thông báo lỗi cũ
        errorMessageContainer.innerHTML = '';
        submitButton.disabled = true; // Vô hiệu hóa nút

        const userNameInput = document.getElementById('user_name');
        const telInput = document.getElementById('tel');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        
        const user_name = userNameInput.value.trim();
        const tel = telInput.value.trim();
        const email = emailInput.value.trim();
        const password = passwordInput.value;
        
        const clientErrors = [];
        
        // --- 1. Kiểm tra các ô không được để trống ---
        if (user_name === "" || tel === "" || email === "" || password === "") {
            clientErrors.push("Vui lòng điền đầy đủ tất cả các trường bắt buộc.");
        } 
        
        // --- 2. Kiểm tra định dạng số điện thoại (10 hoặc 11 số) ---
        const telRegex = /^\d{10,11}$/; 
        if (tel && !telRegex.test(tel)) {
            clientErrors.push("Số điện thoại không hợp lệ. Vui lòng nhập 10 hoặc 11 chữ số.");
        }
        
        // --- 3. Kiểm tra độ dài mật khẩu (Tối thiểu 5 ký tự)
        if (password && password.length < 5) {
            clientErrors.push("Mật khẩu phải có tối thiểu 5 ký tự.");
        }

        if (clientErrors.length > 0) {
            const uniqueErrors = [...new Set(clientErrors)]; 
            errorMessageContainer.innerHTML = '🔴 Đăng ký thất bại!<ul>' + uniqueErrors.map(err => `<li>${err}</li>`).join('') + '</ul>';
            submitButton.disabled = false;
            return; 
        }

        // --- 4. Gửi form qua AJAX/Fetch ---
        try {
            const formData = new FormData(formDK);
            
            const response = await fetch(formDK.action, {
                method: 'POST',
                body: formData,
            });
            
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.includes("application/json")) {
                const result = await response.json();
                
                if (result.success) {
                    window.location.href = result.redirect_url;
                } else {
                    errorMessageContainer.innerHTML = '🔴 Đăng ký thất bại!<ul>' + result.errors.map(err => `<li>${err}</li>`).join('') + '</ul>';
                    submitButton.disabled = false;
                }
            } else {
                // Xử lý lỗi khi Server trả về non-JSON (HTML/Text)
                errorMessageContainer.innerHTML = '🔴 Lỗi máy chủ không xác định. Vui lòng kiểm tra console.';
                console.error("Server returned non-JSON response:", await response.text());
                submitButton.disabled = false;
            }

        } catch (error) {
            // Lỗi kết nối mạng thực sự (request không đến được server)
            console.error('Lỗi khi gửi form:', error);
            errorMessageContainer.innerHTML = '🔴 Lỗi kết nối. Vui lòng thử lại sau.';
            submitButton.disabled = false; 
        }
    }

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el))
});