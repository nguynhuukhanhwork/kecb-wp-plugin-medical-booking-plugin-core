<?php
add_filter('get_search_form', function($form) {
$form = '
<form role="search" method="get" class="custom-search" action="' . esc_url(home_url('/')) . '">
    <input type="search" name="s" placeholder="Tìm bài viết..." value="' . get_search_query() . '" />
    <button type="submit"><i class="fa fa-search"></i></button>
</form>';
return $form;
});
