<?php

if (!defined('ABSPATH')) {
    exit;
}

$config_shortcodes = [
    [
        'name' => '[ecb_shortcode_test]',
        'des' => 'Form hiển thị',
        'attrs' => [
            'title: Title của Form',
            'des: Mô tả thêm của Form',
        ],
    ],
    [
        'name' => '[ecb_shortcode_test]',
        'des' => 'Form hiển thị',
        'attrs' => [
            'title: Title của Form',
            'des: Mô tả thêm của Form',
        ],
    ],
];

$file = __DIR__.'/doc1.txt';
if (file_exists($file)) {
    $content = file_get_contents($file);
} else {
    $content = 'File config not found';
}

?>

<pre>
<h2>Bảng Shortcodes</h2>
<table class="wp-list-table widefat fixed striped posts" role="presentation">
    <thead>
        <tr>
            <th scope="col" width="20%">Shortcode Name</th>
            <th scope="col" width="30%">Mô tả</th>
            <th scope="col" width="50%">Attrs</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php foreach ($config_shortcodes as $shotcode) {
                echo '<tr>';
                echo '<td>'.$shotcode['name'].'</td>';
                echo '<td>'.$shotcode['des'].'</td>';
                echo '<td>';
                foreach ($shotcode['attrs'] as $attr) {
                    echo "<span>$attr</span>";
                    echo '<br>';
                }
                echo '</td>';
                echo '<tr>';
            }
?>
            
        </tr>
    </tbody>

</table>
</pre>

<h2>Notice</h2>
<pre>
    <p><?php echo nl2br(htmlspecialchars($content)); ?></p>
</pre>
