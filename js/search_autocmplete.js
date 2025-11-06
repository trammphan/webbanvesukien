$(document).ready(function() {
    // 1. Lắng nghe sự kiện gõ phím trên ô input tìm kiếm
    $('#search-input').on('keyup', function() {
        var searchTerm = $(this).val().trim();
        var resultsContainer = $('#search-results-container');
        
        // 2. Chỉ gửi yêu cầu nếu từ khóa có 2 ký tự trở lên
        if (searchTerm.length > 1) {
            $.ajax({
                url: 'search_autocomplete.php', // Tệp PHP sẽ xử lý truy vấn
                method: 'GET',
                data: { query: searchTerm },
                success: function(response) {
                    // 3. Hiển thị kết quả nhận được từ PHP
                    resultsContainer.html(response).show();
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: " + status + error);
                    resultsContainer.hide();
                }
            });
        } else {
            // Ẩn box gợi ý nếu từ khóa quá ngắn hoặc rỗng
            resultsContainer.hide().empty();
        }
    });

    // 4. Xử lý khi click vào một gợi ý
    $(document).on('click', '.suggestion-item', function() {
        var selectedTerm = $(this).text();
        $('#search-input').val(selectedTerm); // Điền từ khóa vào input
        $('#search-results-container').hide().empty(); // Ẩn box gợi ý
        
        // Tự động gửi form tìm kiếm
        $(this).closest('form').submit(); 
    });

    // 5. Ẩn box gợi ý khi click ra ngoài
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.header-search').length) {
            $('#search-results-container').hide().empty();
        }
    });
});