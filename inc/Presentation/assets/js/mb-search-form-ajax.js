/* global MB_Search */

/**
 * @typedef {Object} MBSearchConfig
 * @property {string} restUrl - REST API endpoint cho tìm kiếm
 */
jQuery(document).ready(function($) {
    let timer;
    /** @type {MBSearchConfig} */
    const endpoint = MB_Search.restUrl;

    // Auto complete khi gõ chữ
    $(document).on('input', '.mb-search-form input[name="keyword"]', function() {
        const keyword = $(this).val().trim();
        const suggestionsBox = $(this).closest('.mb-search-container').find('.mb-autocomplete-results');

        clearTimeout(timer);

        if (keyword.length < 2) {
            suggestionsBox.empty().hide();
            return;
        }

        // Debounce: chờ 300ms sau khi ngừng gõ
        timer = setTimeout(function() {
            $.ajax({
                url: endpoint,
                method: 'GET',
                data: {
                    keyword: keyword,
                    post_type: 'doctor',
                    limit: 5,
                    page: 1
                },
                success: function(response) {
                    if (response.success && response.results.length > 0) {
                        let html = '<ul>';
                        response.results.forEach(item => {
                            html += `
                                <li>
                                    <a href="${item.link}">
                                        ${item.thumbnail ? `<img src="${item.thumbnail}" alt="${item.title}">` : ''}
                                        ${item.title}
                                    </a>
                                </li>`;
                        });
                        html += '</ul>';
                        suggestionsBox.html(html).show();
                    }
                },
                error: function() {
                    suggestionsBox.hide();
                }
            });
        }, 100);
    });

    // Ẩn khi click ra ngoài
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.mb-search-form').length) {
            $('.mb-autocomplete-results').hide();
        }
    });
});
