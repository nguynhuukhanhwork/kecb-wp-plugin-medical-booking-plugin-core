jQuery(document).ready(function($) {
    let currentPage = 1;
    let currentKeyword = '';
    let endpoint = MB_Search.restUrl;

    // Xử lý submit form
    $(document).on('submit', '.mb-search-form', function(e) {
        e.preventDefault();
        currentKeyword = $(this).find('input[name=\"keyword\"]').val();
        currentPage = 1;
        performSearch();
    });

    // Xử lý phân trang
    $(document).on('click', '.mb-pagination button', function() {
        const action = $(this).data('action');
        if (action === 'prev') {
            currentPage--;
        } else if (action === 'next') {
            currentPage++;
        }
        performSearch();
    });

    // Hàm thực hiện search
    function performSearch() {
        const resultsContainer = $('.mb-search-results');
        const loadingDiv = $('.mb-search-loading');

        loadingDiv.show();
        resultsContainer.html('');

        $.ajax({
            url: endpoint,
            method: 'GET',
            data: {
                keyword: currentKeyword,
                post_type: 'doctor',
                limit: 10,
                page: currentPage
            },
            success: function(response) {
                loadingDiv.hide();
                console.log('Response:', response); // Debug

                if (response.success && response.results.length > 0) {
                    let html = '<div class=\"mb-search-info\">';
                    html += '<strong>Tìm thấy ' + response.total + ' kết quả</strong>';
                    html += ' (Trang ' + response.current_page + '/' + response.pages + ')';
                    html += '</div>';

                    response.results.forEach(function(item) {
                        html += '<div class=\"mb-search-item\">';

                        if (item.thumbnail) {
                            html += '<img src=\"' + item.thumbnail + '\" alt=\"' + item.title + '\" />';
                        }

                        html += '<div class=\"mb-search-item-content\">';
                        html += '<h3><a href=\"' + item.link + '\">' + item.title + '</a></h3>';
                        html += '<p>' + item.excerpt + '</p>';
                        html += '<div class=\"mb-search-item-date\">' + item.date + '</div>';
                        html += '</div>';
                        html += '</div>';
                    });

                    // Thêm pagination
                    if (response.pages > 1) {
                        html += '<div class=\"mb-pagination\">';
                        html += '<button data-action=\"prev\" ' + (currentPage <= 1 ? 'disabled' : '') + '>« Trước</button>';
                        html += '<span class=\"current-page\">Trang ' + currentPage + '/' + response.pages + '</span>';
                        html += '<button data-action=\"next\" ' + (currentPage >= response.pages ? 'disabled' : '') + '>Sau »</button>';
                        html += '</div>';
                    }

                    resultsContainer.html(html);
                } else {
                    resultsContainer.html('<div class=\"mb-no-results\"><p>Không tìm thấy kết quả nào.</p></div>');
                }
            },
            error: function(xhr, status, error) {
                loadingDiv.hide();
                console.error('Error:', xhr.responseText); // Debug chi tiết
                resultsContainer.html('<div class=\"mb-no-results\"><p>Có lỗi xảy ra: ' + error + '</p></div>');
            }
        });
    }
});