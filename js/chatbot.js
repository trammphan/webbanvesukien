// === BẮT ĐẦU SCRIPT CUỐI CÙNG (ĐÃ SẠCH 100%) ===

// Dòng alert() đã bị xóa.

const chatBody = document.querySelector(".chat-body");
const messageInput = document.querySelector(".message-input");
const sendMessageButton = document.querySelector("#send-message");
const fileInput = document.querySelector("#file-input");
const fileUploadWrapper = document.querySelector(".file-upload-wrapper");
const fileCancelButton = document.querySelector("#file-cancel");
const chatbotToggler = document.querySelector("#chatbot-toggler");
const closeChatbot = document.querySelector("#close-chatbot");

// Biến này LƯU TRỮ tin nhắn và file của người dùng
const userData = {
  message: null,
  file: {
    data: null,
    mime_type: null,
  },
};

// Biến này là "mồi" ban đầu cho chatbot
const chatHistory = [
  {
    role: "model",
    parts: [
      {
        text: `Bạn là một trợ lý ảo chuyên nghiệp cho một website Vibe4. Nhiệm vụ của bạn là giúp đỡ người dùng tìm kiếm thông tin về các sự kiện, loại vé, giá cả và hỗ trợ họ trong quá trình đặt vé.

Cơ sở dữ liệu của bạn được tổ chức như sau:
- **sukien**: Bảng chính chứa thông tin sự kiện (Tên sự kiện, Ngày diễn ra, Mô tả, Địa điểm).
- **loaive**: Bảng chứa các loại vé và giá vé cho từng sự kiện.
- **diadiem**: Bảng chứa các địa điểm tổ chức (ví dụ: 'HCM' là 'Thành phố Hồ Chí Minh', 'HN' là 'Hà Nội', 'DL' là 'Đà Lạt').
- **loaisk**: Bảng chứa các loại hình sự kiện (ví dụ: 'LSK01' là 'Liveshow', 'LSK03' là 'Concert').
- **khachhang**: Bảng chứa thông tin khách hàng.
- **ve**: Bảng chứa thông tin từng chiếc vé với trạng thái (ví dụ: 'chưa thanh toan', 'Đã giữ chỗ').

**Nhiệm vụ của bạn:**
1.  **Trả lời thông tin:** Cung cấp thông tin chi tiết về các sự kiện khi người dùng hỏi (dựa trên tên, địa điểm, ngày tháng, loại sự kiện).
2.  **Báo giá vé:** Liệt kê các loại vé và giá vé tương ứng cho một sự kiện cụ thể.
3.  **Hỗ trợ đặt vé:** Hướng dẫn người dùng về các bước để đặt vé.
4.  **Giải đáp thắc mắc:** Trả lời các câu hỏi về chính sách, trạng thái vé, và các vấn đề liên quan.

**Dưới đây là một số dữ liệu sự kiện mẫu từ cơ sở dữ liệu (từ tệp qlysukien (5).sql):**

---

**Sự kiện 1 (MaSK: SK01):**
* **Tên:** LULULOLA SHOW VŨ CÁT TƯỜNG | NGÀY NÀY, NGƯỜI CON GÁI NÀY
* **Thời gian:** 2025-10-18
* **Địa điểm:** Đà Lạt (MaDD: DL)
* **Loại sự kiện:** Liveshow (MaLSK: LSK01)
* **Các loại vé:**
    * NHÁ NHEM (LV01): 500,000 VND
    * CHẬP CHOẠNG (LV02): 700,000 VND
    * CHẠNG VẠNG (LV03): 1,000,000 VND
    * CHIỀU TÀ (LV04): 1,300,000 VND
    * HOÀNG HÔN (LV05): 1,500,000 VND

**Sự kiện 2 (MaSK: SK03):**
* **Tên:** G-DRAGON 2025 WORLD TOUR [Übermensch] IN HANOI, PRESENTED BY VPBANK
* **Thời gian:** 2025-11-08
* **Địa điểm:** Hưng Yên (MaDD: HY)
* **Loại sự kiện:** Concert (MaLSK: LSK03)
* **Các loại vé:**
    * VIP-A (LV10): 7,300,000 VND
    * VIP-B (LV11): 7,300,000 VND
    * PREMIUM (LV12): 6,500,000 VND
    * CAT-1A (LV13): 6,000,000 VND

**Sự kiện 3 (MaSK: SK05):**
* **Tên:** Waterbomb Ho Chi Minh City 2025
* **Thời gian:** 2025-11-15
* **Địa điểm:** Thành phố Hồ Chí Minh (MaDD: HCM)
* **Loại sự kiện:** Festival (MaLSK: LSK02)
* **Các loại vé:**
    * EARLY BIRD - GA (LV17): 899,000 VND
    * DAY TIME CHECK-IN (GA) (LV18): 1,099,000 VND
    * 01 DAY PASS (NORMAL) - GA (LV19): 1,169,000 VND
    * 02 DAY PASS - GA (LV20): 2,099,000 VND

---

Hãy sử dụng thông tin này để trả lời các câu hỏi của người dùng một cách thân thiện và chính xác. Bắt đầu cuộc trò chuyện bằng cách chào hỏi và hỏi xem bạn có thể giúp gì cho họ.`,
      },
    ],
  },
];

const initialInputHeight = messageInput.scrollHeight;

// Tạo một tin nhắn
const createMessageElement = (content, ...classes) => {
  const div = document.createElement("div");
  div.classList.add("message", ...classes);
  div.innerHTML = content;
  return div;
};

// Gọi API (từ chat.php)
const generateBotResponse = async (incomingMessageDiv) => {
  const messageElement = incomingMessageDiv.querySelector(".message-text");

  chatHistory.push({
    role: "user",
    parts: [
      { text: userData.message },
      ...(userData.file.data ? [{ inline_data: userData.file }] : []),
    ],
  });

  const requestOptions = {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      contents: chatHistory,
    }),
  };

  try {
    // Gọi tệp chat.php của bạn
    const response = await fetch("chat.php", requestOptions);
    const data = await response.json();

    if (data.error) {
      throw new Error(
        data.error + (data.details ? ` - ${JSON.stringify(data.details)}` : "")
      );
    }

    const apiResponseText = data.candidates[0].content.parts[0].text
      .replace(/\*\*(.*?)\*\*/g, "$1")
      .trim();
    messageElement.innerText = apiResponseText;
    chatHistory.push({
      role: "model",
      parts: [{ text: apiResponseText }],
    });
  } catch (error) {
    messageElement.innerText = error.message;
    messageElement.style.color = "#ff0000";
  } finally {
    userData.file = {};
    incomingMessageDiv.classList.remove("thinking");
    chatBody.scrollTo({ behavior: "smooth", top: chatBody.scrollHeight });
  }
};

// Xử lý tin nhắn gửi đi
const handleOutgoingMessage = (e) => {
  e.preventDefault();
  userData.message = messageInput.value.trim();

  // Thêm kiểm tra: nếu không có tin nhắn thì không làm gì cả
  if (!userData.message) return;

  messageInput.value = "";
  fileUploadWrapper.classList.remove("file-uploaded");
  messageInput.dispatchEvent(new Event("input"));

  const messageContent = `<div class="message-text"></div>
                            ${
                              userData.file.data
                                ? `<img src="data:${userData.file.mime_type};base64,${userData.file.data}" class="attachment" />`
                                : ""
                            }`;

  const outgoingMessageDiv = createMessageElement(
    messageContent,
    "user-message"
  );
  outgoingMessageDiv.querySelector(".message-text").innerText =
    userData.message;
  chatBody.appendChild(outgoingMessageDiv);
  chatBody.scrollTop = chatBody.scrollHeight;

  // Hiển thị "đang gõ..."
  setTimeout(() => {
    const messageContent = `<svg class="bot-avatar" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 1024 1024">
                    <path d="M738.3 287.6H285.7c-59 0-106.8 47.8-106.8 106.8v303.1c0 59 47.8 106.8 106.8 106.8h81.5v111.1c0 .7.8 1.1 1.4.7l166.9-110.6 41.8-.8h117.4l43.6-.4c59 0 106.8-47.8 106.8-106.8V394.5c0-59-47.8-106.9-106.8-106.9zM351.7 448.2c0-29.5 23.9-53.5 53.5-53.5s53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5-53.5-23.9-53.5-53.5zm157.9 267.1c-67.8 0-123.8-47.5-132.3-109h264.6c-8.6 61.5-64.5 109-132.3 109zm110-213.7c-29.5 0-53.5-23.9-53.5-53.5s23.9-53.5 53.5-53.5 53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5zM867.2 644.5V453.1h26.5c19.4 0 35.1 15.7 35.1 35.1v121.1c0 19.4-15.7 35.1-35.1 35.1h-26.5zM95.2 609.4V488.2c0-19.4 15.7-35.1 35.1-35.1h26.5v191.3h-26.5c-19.4 0-35.1-15.7-35.1-35.1zM561.5 149.6c0 23.4-15.6 43.3-36.9 49.7v44.9h-30v-44.9c-21.4-6.5-36.9-26.3-36.9-49.7 0-28.6 23.3-51.9 51.9-51.9s51.9 23.3 51.9 51.9z"></path>
                </svg>
                <div class="message-text">
                    <div class="thinking-indicator">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                </div>`;

    const incomingMessageDiv = createMessageElement(
      messageContent,
      "bot-message",
      "thinking"
    );
    chatBody.appendChild(incomingMessageDiv);
    chatBody.scrollTo({ behavior: "smooth", top: chatBody.scrollHeight });
    generateBotResponse(incomingMessageDiv);
  }, 600);
};

// Lắng nghe phím Enter
messageInput.addEventListener("keydown", (e) => {
  const userMessage = e.target.value.trim();
  // Đã xóa điều kiện `innerWidth`
  if (e.key === "Enter" && userMessage && !e.shiftKey) {
    handleOutgoingMessage(e);
  }
});

// Tự động thay đổi chiều cao ô nhập
messageInput.addEventListener("input", (e) => {
  messageInput.style.height = `${initialInputHeight}px`;
  messageInput.style.height = `${messageInput.scrollHeight}px`;
  document.querySelector(".chat-form").style.boderRadius =
    messageInput.scrollHeight > initialInputHeight ? "15px" : "32px";
});

// Hủy file
fileCancelButton.addEventListener("click", (e) => {
  userData.file = {};
  fileUploadWrapper.classList.remove("file-uploaded");
});

// Bộ chọn Emoji (ĐÃ SỬA)
const picker = new EmojiMart.Picker({
  theme: "light", // Đổi sang theme "light"
  showSkinTones: "none",
  previewPosition: "none",
  onEmojiSelect: (emoji) => {
    const { selectionStart: start, selectionEnd: end } = messageInput;
    messageInput.setRangeText(emoji.native, start, end, "end");
    messageInput.focus();
  },
  // Sửa lại logic: nếu click bên ngoài, LUÔN LUÔN đóng
  onClickOutside: () => {
    document
      .querySelector(".chatbot-popup")
      .classList.remove("show-emoji-picker");
  },
});

// Gắn bộ chọn vào footer (CHỈ GẮN 1 LẦN)
document.querySelector(".chat-footer").appendChild(picker);

// Thêm logic MỞ (bị thiếu trước đây)
document.querySelector("#emoji-picker").addEventListener("click", (e) => {
  e.stopPropagation(); // Ngăn sự kiện click này lan ra ngoài
  document
    .querySelector(".chatbot-popup")
    .classList.toggle("show-emoji-picker");
});

// Xử lý file (kiểm tra loại file) - CHỈ CÓ 1 KHỐI NÀY
fileInput.addEventListener("change", async (e) => {
  const file = e.target.files[0];
  if (!file) return;
  const validImageTypes = [
    "image/jpeg",
    "image/png",
    "image/gif",
    "image/webp",
  ];
  if (!validImageTypes.includes(file.type)) {
    await Swal.fire({
      icon: "error",
      title: "Lỗi",
      text: "Chỉ chấp nhận file ảnh (JPEG, PNG, GIF, WEBP)",
      confirmButtonText: "OK",
    });
    resetFileInput();
    return;
  }
  const reader = new FileReader();
  reader.onload = (e) => {
    fileUploadWrapper.querySelector("img").src = e.target.result;
    fileUploadWrapper.classList.add("file-uploaded");
    const base64String = e.target.result.split(",")[1];
    userData.file = {
      data: base64String,
      mime_type: file.type,
    };
  };
  reader.readAsDataURL(file);
});

// Đặt lại file input
function resetFileInput() {
  fileInput.value = "";
  fileUploadWrapper.classList.remove("file-uploaded");
  fileUploadWrapper.querySelector("img").src = "#";
  userData.file = { data: null, mime_type: null };
  document.querySelector(".chat-form").reset();
}

// Lắng nghe các nút
sendMessageButton.addEventListener("click", (e) => handleOutgoingMessage(e));
document
  .querySelector("#file-upload")
  .addEventListener("click", (e) => fileInput.click());
chatbotToggler.addEventListener("click", () =>
  document.body.classList.toggle("show-chatbot")
);
closeChatbot.addEventListener("click", () =>
  document.body.classList.remove("show-chatbot")
);

// === HẾT SCRIPT SẠCH ===
