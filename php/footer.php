</main>
    <link rel="stylesheet" href="../css/footer.css">
    <footer>
        <div class="footer-container">
            
            <div class="footer-col footer-branding">
                <h3 class="footer-logo">Vibe4</h3>
                <p>Vibe4 – Nền tảng mua vé sự kiện đa dạng: liveshow, festival, concert và các hoạt động giải trí uy tín tại Việt Nam, lựa chọn hàng đầu cho những ai yêu thích văn hóa và giải trí.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-dribbble"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Liên kết Hữu ích</h4>
                <ul class="footer-links">
                    <li><a href="#home">Trang chủ</a></li>
                    <li><a href="#taosukien">Tạo sự kiện</a></li>
                    <li><a href="#vecuatoi">Vé của tôi</a></li>
                    <li><a href="#faq">FAQ</a></li>
                    <li><a href="#contact">Liên hệ</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Liên hệ</h4>
                <ul class="footer-contact">
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <span>+123 456 789</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span>support@vibe4.com</span>
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Khu II Đại học Cần Thơ, Đường 3/2, P Ninh Kiều, TP Cần Thơ</span>
                    </li>
                </ul>
            </div>

            <div class="footer-col footer-action">
                <h4 class="action-title">Tham gia cùng chúng tôi</h4>
                <button class="btn-download">Tải ứng dụng ngay</button>
            </div>

        </div>
        
        <div class="footer-bottom">
            <p>@2025 - All Rights Reserved by Vibe4 Platform • Phát triển bởi Nhóm 1-CT299-Phát Triển Hệ Thống Web</p>
        </div>
    </footer>
    
    <?php if (isset($additional_footer_scripts)) { echo $additional_footer_scripts; } ?>
    <button id="chatbot-toggler">
  <span class="material-symbols-rounded">mode_comment</span>
  <span class="material-symbols-rounded">close</span>
</button>

<div class="chatbot-popup">
  <div class="chat-header">
    <div class="header-info">
      <svg
        class="chatbot-logo"
        xmlns="http://www.w3.org/2000/svg"
        width="50"
        height="50"
        viewBox="0 0 1024 1024"
      >
        <path
          d="M738.3 287.6H285.7c-59 0-106.8 47.8-106.8 106.8v303.1c0 59 47.8 106.8 106.8 106.8h81.5v111.1c0 .7.8 1.1 1.4.7l166.9-110.6 41.8-.8h117.4l43.6-.4c59 0 106.8-47.8 106.8-106.8V394.5c0-59-47.8-106.9-106.8-106.9zM351.7 448.2c0-29.5 23.9-53.5 53.5-53.5s53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5-53.5-23.9-53.5-53.5zm157.9 267.1c-67.8 0-123.8-47.5-132.3-109h264.6c-8.6 61.5-64.5 109-132.3 109zm110-213.7c-29.5 0-53.5-23.9-53.5-53.5s23.9-53.5 53.5-53.5 53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5zM867.2 644.5V453.1h26.5c19.4 0 35.1 15.7 35.1 35.1v121.1c0 19.4-15.7 35.1-35.1 35.1h-26.5zM95.2 609.4V488.2c0-19.4 15.7-35.1 35.1-35.1h26.5v191.3h-26.5c-19.4 0-35.1-15.7-35.1-35.1zM561.5 149.6c0 23.4-15.6 43.3-36.9 49.7v44.9h-30v-44.9c-21.4-6.5-36.9-26.3-36.9-49.7 0-28.6 23.3-51.9 51.9-51.9s51.9 23.3 51.9 51.9z"
        />
      </svg>
      <h2 class="logo-text">Chatbot</h2>
    </div>
    <button id="close-chatbot" class="material-symbols-rounded">
      keyboard_arrow_down
    </button>
  </div>

  <div class="chat-body">
    <div class="message bot-message">
      <svg
        class="bot-avatar"
        xmlns="http://www.w3.org/2000/svg"
        width="50"
        height="50"
        viewBox="0 0 1024 1024"
      >
        <path
          d="M738.3 287.6H285.7c-59 0-106.8 47.8-106.8 106.8v303.1c0 59 47.8 106.8 106.8 106.8h81.5v111.1c0 .7.8 1.1 1.4.7l166.9-110.6 41.8-.8h117.4l43.6-.4c59 0 106.8-47.8 106.8-106.8V394.5c0-59-47.8-106.9-106.8-106.9zM351.7 448.2c0-29.5 23.9-53.5 53.5-53.5s53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5-53.5-23.9-53.5-53.5zm157.9 267.1c-67.8 0-123.8-47.5-132.3-109h264.6c-8.6 61.5-64.5 109-132.3 109zm110-213.7c-29.5 0-53.5-23.9-53.5-53.5s23.9-53.5 53.5-53.5 53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5zM867.2 644.5V453.1h26.5c19.4 0 35.1 15.7 35.1 35.1v121.1c0 19.4-15.7 35.1-35.1 35.1h-26.5zM95.2 609.4V488.2c0-19.4 15.7-35.1 35.1-35.1h26.5v191.3h-26.5c-19.4 0-35.1-15.7-35.1-35.1zM561.5 149.6c0 23.4-15.6 43.3-36.9 49.7v44.9h-30v-44.9c-21.4-6.5-36.9-26.3-36.9-49.7 0-28.6 23.3-51.9 51.9-51.9s51.9 23.3 51.9 51.9z"
        />
      </svg>
      <div class="message-text"> Xin chào 👋<br /> Tôi có thể giúp gì cho bạn hôm nay? </div>
    </div>
  </div>

  <div class="chat-footer">
    <form action="#" class="chat-form">
      <textarea
        placeholder="Message..."
        class="message-input"
        required
      ></textarea>
      <div class="chat-controls">
        <button
          type="button"
          id="emoji-picker"
          class="material-symbols-outlined"
        >
          sentiment_satisfied
        </button>
        <div class="file-upload-wrapper">
          <input type="file" id="file-input" hidden />
          <img src="#" />
          <button
            type="button"
            id="file-upload"
            class="material-symbols-rounded"
          >
            attach_file
          </button>
          <button
            type="button"
            id="file-cancel"
            class="material-symbols-rounded"
          >
            close
          </button>
        </div>
        <button
          type="submit"
          id="send-message"
          class="material-symbols-rounded"
        >
          arrow_upward
        </button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js"></script>

<script src="../js/chatbot.js"></script>
    </body>
    </html>
