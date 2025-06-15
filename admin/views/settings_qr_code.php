<?php
echo "<h1>" . esc_html(get_admin_page_title()) . "</h1>";
echo "<p>" . esc_html__('This is the content qr code page.', 'wp-utility-plug') . "</p>";

$qr_code_options = get_option('qr_code_config', array(
    'containerId' => 'qr-code-container',
    'download' => true,
    'type' => 'svg', // "svg" or "canvas"
    'shape' => 'circle', // "square" or "circle"
    'width' => 250,
    'height' => 250,
    'data' => home_url(),
    'margin' => 40,
    'logo' => '',
    'dotsOptions' => array(
        'type' => 'square', // "square", "rounded", "dots", "classy", "classy-rounded", "extra-rounded"
        'color' => '#df349e',
        'roundSize' => true,
        'gradient' => array(
            'type' => 'linear', // "linear" or "radial",
            'rotation' => 0,
            'colorStops' => array(
                array(
                    'offset' => 0,
                    'color' => '#6a1a4c',
                ),
                array(
                    'offset' => 1,
                    'color' => 'green',
                ),
            ),
        ),
    ),
    'cornersSquareOptions' => array(
        'type' => 'dot', // "none" or "dot" or "square" or "extra-rounded"
        'color' => '#000000',
        'gradient' => array(
            'type' => 'linear',
            'rotation' => 0,
            'colorStops' => array(
                array(
                    'offset' => 0,
                    'color' => '#000000',
                ),
                array(
                    'offset' => 1,
                    'color' => '#a61717',
                ),
            ),
        ),
    ),
    'cornersDotOptions' => array(
        'type' => 'dot', // "none" or "dot" or "square" or "extra-rounded"
        'color' => '#000000',
    ),
    'backgroundOptions' => array(
        'color' => '#ffffff',
    ),
    'border' => array(
        'color' => '#000000',
        'thickness' => 40,
        'borderInnerColor' => '#000000',
        'borderOuterColor' => '#000000',
        'direction' => array(
            'top' => array(
                'text' => 'Read me on other devices',
                'color' => '#D5B882;',
            ),
            'bottom' => array(
                'text' => 'SCAN ME',
                'color' => '#D5B882;',
            ),
        ),
    ),
));
?>

<form method="post" action="<?php echo esc_url(admin_url('admin.php?page=wp-utility-plug-qr-code')); ?>">
    <table class="form-table" style="width: 100%;">
        <tr style="width: 80%;">
            <td>
                <table>
                    <tr>
                        <th scope="row">
                            <label for="qr_code_type"><?php esc_html_e('Type', 'wp-utility-plug'); ?></label>
                        </th>
                        <td>
                            <!-- <input type="text" id="qr_code_text" name="qr_code_text"
                                value="<?php echo esc_attr(get_option('qr_code_text')); ?>" /> -->
                            <select id="qr_code_type" name="qr_code_type">
                                <option value="svg" <?php selected($qr_code_options['type'], 'svg'); ?>>
                                    <?php esc_html_e('SVG', 'wp-utility-plug'); ?></option>
                                <option value="canvas" <?php selected($qr_code_options['type'], 'canvas'); ?>>
                                    <?php esc_html_e('CANVAS', 'wp-utility-plug'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="qr_code_shape"><?php esc_html_e('Shape', 'wp-utility-plug'); ?></label>
                        </th>
                        <td>
                            <select id="qr_code_shape" name="qr_code_shape">
                                <option value="circle" <?php selected($qr_code_options['shape'], 'circle'); ?>>
                                    <?php esc_html_e('CIRCLE', 'wp-utility-plug'); ?></option>
                                <option value="square" <?php selected($qr_code_options['shape'], 'square'); ?>>
                                    <?php esc_html_e('SQUARE', 'wp-utility-plug'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="qr_code_width"><?php esc_html_e('Width', 'wp-utility-plug'); ?></label>
                        </th>
                        <td>
                            <input type="number" id="qr_code_width" name="qr_code_width"
                                value="<?php echo esc_attr($qr_code_options['width']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="qr_code_height"><?php esc_html_e('Height', 'wp-utility-plug'); ?></label>
                        </th>
                        <td>
                            <input type="number" id="qr_code_height" name="qr_code_height"
                                value="<?php echo esc_attr($qr_code_options['height']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="qr_code_margin"><?php esc_html_e('Margin', 'wp-utility-plug'); ?></label>
                        </th>
                        <td>
                            <input type="number" id="qr_code_margin" name="qr_code_margin"
                                value="<?php echo esc_attr($qr_code_options['margin']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="qr_code_logo"><?php esc_html_e('Logo', 'wp-utility-plug'); ?></label>
                        </th>
                        <td>
                            <input type="file" id="qr_code_logo" name="qr_code_logo"
                                value="<?php echo esc_attr($qr_code_options['logo']); ?>" />
                        </td>
                    </tr>
                </table>
            </td>
            <td></td>
        </tr>
    </table>
    <?php
    settings_fields('wp_plug_qr_code');
    do_settings_sections('wp_plug_qr_code');
    submit_button();
    ?>
</form>