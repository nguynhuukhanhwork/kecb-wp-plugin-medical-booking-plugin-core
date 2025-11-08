/**
 * @typedef {Object} TravelBookingSearchTour
 * @property {string} api_url - REST API endpoint
 * @property {string} nonce   - WP REST nonce
 */

jQuery(document).ready(function($) {
    const input = $('#travel-tour-search');
    const button = $('#travel-tour-search-btn');
    const results = $('#travel-tour-results');

    function travelBookingDoSearch() {
        const keyword = input.val().trim();
        if (!keyword) {
            results.html('<p>Vui lòng nhập từ khóa.</p>');
            return;
        }

        results.html('<p>Đang tìm kiếm...</p>');

        $.ajax({
            url: travelBookingSearchTour.api_url,
            method: 'GET',
            data: { keyword }, // ✅ Đổi từ q -> keyword
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', travelBookingSearchTour.nonce);
            },
            success: function(response) {
                if (response.success && response.data.length) {
                    const html = response.data.map(name => `<li>${name}</li>`).join('');
                    results.html(`<ul>${html}</ul>`);
                } else {
                    results.html('<p>Không tìm thấy kết quả.</p>');
                }
            },
            error: function(xhr) {
                console.error(xhr);
                results.html('<p>Có lỗi xảy ra khi tìm kiếm.</p>');
            }
        });
    }

    button.on('click', travelBookingDoSearch);
    input.on('keypress', function(e) {
        if (e.which === 13) doSearch();
    });
});
