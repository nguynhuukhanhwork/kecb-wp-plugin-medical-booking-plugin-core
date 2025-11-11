<div class="container-fluid">
    <label for="tour-name-search-input" style="display:none">Tìm kiếm Tour</label>
    <div class="search-box" style="position:relative; display:inline-block; width:100%; max-width:500px;">
        <input
            type="search"
            id="tour-name-search-input"
            name="tour_name_search"
            placeholder="Nhập tên tour..."
            value=""
            autocomplete="off"
            style="width:100%; padding:10px; font-size:16px; border:1px solid #ccc; border-radius:4px;"
        >
        <div id="results"
             style="position:absolute; top:100%; left:0; right:0; background:white; border:1px solid #ddd; max-height:300px; overflow-y:auto; display:none; z-index:999;">
        </div>
    </div>
</div>

<script>
    let timeout;
    const input = document.getElementById('tour-name-search-input');
    const results = document.getElementById('results');

    // Hàm xử lý tìm kiếm
    function handleSearch() {
        clearTimeout(timeout);
        const query = input.value.trim();

        // Ẩn nếu rỗng
        if (!query) {
            results.style.display = 'none';
            return;
        }

        // Debounce 300ms
        timeout = setTimeout(() => {
            fetch(`/wp-json/travel-booking/v1/tours/search?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    results.innerHTML = '';
                    if (Object.keys(data).length === 0) {
                        results.innerHTML = '<div style="padding:12px; color:#999;">Không tìm thấy tour nào</div>';
                    } else {
                        for (const [name, url] of Object.entries(data)) {
                            const a = document.createElement('a');
                            a.href = url;
                            a.textContent = name;
                            a.target = '_blank';
                            a.style.display = 'block';
                            a.style.padding = '10px 12px';
                            a.style.textDecoration = 'none';
                            a.style.color = '#333';
                            a.style.borderBottom = '1px solid #eee';
                            a.onmouseover = () => a.style.backgroundColor = '#f5f5f5';
                            a.onmouseout = () => a.style.backgroundColor = 'white';
                            results.appendChild(a);
                        }
                    }
                    results.style.display = 'block';
                })
                .catch(err => {
                    results.innerHTML = '<div style="padding:12px; color:red;">Lỗi kết nối</div>';
                    results.style.display = 'block';
                });
        }, 300);
    }

    // Gắn sự kiện
    input.addEventListener('input', handleSearch);

    // Đóng khi click ngoài
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !results.contains(e.target)) {
            results.style.display = 'none';
        }
    });

    // Mở lại khi focus vào input
    input.addEventListener('focus', handleSearch);
</script>