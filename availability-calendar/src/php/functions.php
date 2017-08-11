<?php

function ac_add_meta_boxes() {
    if ( !ac_get_enabled() ) {
        return false;
    }
    
    $pts = ac_get_post_types();
    
    foreach( $pts as $ptid => $ptname ) {
        if ( ac_get_enabled('calendar') && ac_get_post_type_option($ptid, 'admin') ) {
            add_meta_box( 'ac_meta_box', __('Availability Calendar', AC_TEXTDOMAIN), 'ac_meta_box', $ptid );
        }
        
        if ( ac_get_enabled('price_table') && ac_get_post_type_option($ptid, 'price_table') ) {
            add_meta_box( 'ac_meta_box_price_table', __('Price Table', AC_TEXTDOMAIN), 'ac_meta_box_price_table', $ptid );
        }
    }
}

function ac_admin_enqueue_scripts() {
    wp_enqueue_script('jquery-ui-datepicker');
    
    wp_register_script( 'js-color', plugins_url('/media/js/jscolor/jscolor.js', AC_AVAILABILITY_CALENDAR_PLUGIN) );
    wp_enqueue_script( 'js-color');
    
    wp_register_script( 'ac-js', plugins_url('/media/js/ac.js', AC_AVAILABILITY_CALENDAR_PLUGIN), array('jquery', 'js-color', 'jquery-ui-datepicker') );
    wp_enqueue_script( 'ac-js' );
	//wp_enqueue_script( 'ac-js',plugins_url('/media/js/ac.js'),'', '', true );
    
    wp_register_style( 'ac-css', plugins_url( '/media/css/' . ac_get_theme(), AC_AVAILABILITY_CALENDAR_PLUGIN ) );
    wp_enqueue_style( 'ac-css' );
}

function ac_admin_menu() {
    $pages = array(
        'Settings' => 'ac_admin_settings',
        'Availability' => 'ac_admin_availability',
        'Seasons' => 'ac_admin_seasons',
        'Duration' => 'ac_admin_duration',
        'Post Types' => 'ac_admin_post_types'
    );
    
    add_menu_page(__('Availability Calendar', AC_TEXTDOMAIN), __('Availability Calendar', AC_TEXTDOMAIN), 'edit_pages', 'ac_admin_settings', 'ac_admin_settings', 'dashicons-calendar', 100);
    
    foreach($pages as $title => $slug) {
        ac_admin_menu_add_page($title, $slug);
    }
}

function ac_admin_menu_add_page($title, $slug) {
    add_submenu_page('ac_admin_settings', __($title, AC_TEXTDOMAIN), __($title, AC_TEXTDOMAIN), 'edit_pages', $slug, $slug);
}

function ac_admin_availability() {
    $vars = array();
    
    if (isset($_POST) && !empty($_POST)) {
        ac_admin_availability_process($_POST);
        $vars['message'] = __('Settings saved.', AC_TEXTDOMAIN);
    }
    
    ac_template('availability', $vars);
}

function ac_admin_availability_process($data) {
    $data = isset($data[AC_OPT_AVAILABILITY_OPTIONS]) ? $data[AC_OPT_AVAILABILITY_OPTIONS] : array();
    
    ac_admin_availability_process_files($data);
    
    $ai = isset($data['auto_increment']) ? $data['auto_increment'] : false;
    unset($data['auto_increment'], $data['submit']);
    
    $last_id = 0;
    $tmp = array();
    
    foreach($data as $aid => $ao) {
        if (!isset($ao['remove']) || !$ao['remove']) {
            if (!isset($ao['default'])) {
                $ao['default'] = 0;
            }
            
            $tmp[$aid] = $ao;
        }
        
        if ($aid > $last_id) {
            $last_id = $aid;
        }
    }
    
    $remove_image = isset($_POST['remove_image']) ? $_POST['remove_image'] : array();
    
    foreach($remove_image as $aid => $value) {
        $tmp[$aid]['image'] = '';
    }
    
    $data = $tmp;
    
    update_option(AC_OPT_AVAILABILITY_OPTIONS, $data);
    
    if (!$ai) {
        $ai = $last_id + 1;
    }
    
    update_option(AC_OPT_AVAILABILITY_OPTIONS_AUTO_INCREMENT, $ai);
}

function ac_admin_availability_process_files(&$data) {
    if (!is_dir(AC_UPLOAD_DIR)) {
        mkdir(AC_UPLOAD_DIR);
    }
    
    if (isset($_FILES) && !empty($_FILES)) {
        foreach($_FILES as $fid => $file) {
            if (!empty($file['name'])) {
                $destination = AC_UPLOAD_DIR . $file['name'];
                move_uploaded_file($file['tmp_name'], $destination);
                
                $aid = str_replace('image_', '', $fid);
                
                $data[$aid]['image'] = $file['name'];
            }
        }
    }
}

function ac_admin_duration() {
    $vars = array();
    
    if (isset($_POST) && !empty($_POST)) {
        ac_admin_duration_process($_POST);
        $vars['message'] = __('Settings saved.', AC_TEXTDOMAIN);
    }
    
    ac_template('duration', $vars);
}

function ac_admin_duration_process($data) {
    $data = isset($data[AC_OPT_DURATION_OPTIONS]) ? $data[AC_OPT_DURATION_OPTIONS] : array();
    
    $ai = isset($data['auto_increment']) ? $data['auto_increment'] : false;
    unset($data['auto_increment'], $data['submit']);
    
    $last_id = 0;
    $tmp = array();
    
    foreach($data as $aid => $ao) {
        if (!isset($ao['remove'])) {
            $tmp[$aid] = $ao;
        }
        
        if ($aid > $last_id) {
            $last_id = $aid;
        }
    }
    
    $data = $tmp;
    
    update_option(AC_OPT_DURATION_OPTIONS, $data);
    
    if (!$ai) {
        $ai = $last_id + 1;
    }
    
    update_option(AC_OPT_DURATION_OPTIONS_AUTO_INCREMENT, $ai);
}

function ac_admin_post_types() {
    $vars = array();
    
    if (isset($_POST) && !empty($_POST)) {
        ac_admin_post_types_process($_POST);
        $vars['message'] = __('Settings saved.', AC_TEXTDOMAIN);
    }
    
    ac_template('post_types', $vars);
}

function ac_admin_post_types_process($data) {
    $apts = isset($data[AC_OPT_POST_TYPE_OPTIONS]) ? $data[AC_OPT_POST_TYPE_OPTIONS] : array();
    update_option(AC_OPT_POST_TYPE_OPTIONS, $apts);
}

function ac_admin_seasons() {
    $vars = array();
    
    if (isset($_POST) && !empty($_POST)) {
        ac_admin_seasons_process($_POST);
        $vars['message'] = __('Settings saved.', AC_TEXTDOMAIN);
    }
    
    ac_template('seasons', $vars);
}

function ac_admin_seasons_process($data) {
    $data = isset($data[AC_OPT_SEASONS_OPTIONS]) ? $data[AC_OPT_SEASONS_OPTIONS] : array();
    
    $ai = isset($data['auto_increment']) ? $data['auto_increment'] : false;
    unset($data['auto_increment'], $data['submit']);
    
    $last_id = 0;
    $tmp = array();
    
    foreach($data as $aid => $ao) {
        if (!isset($ao['remove'])) {
            $tmp[$aid] = $ao;
        }
        
        if ($aid > $last_id) {
            $last_id = $aid;
        }
    }
    
    $data = $tmp;
    
    update_option(AC_OPT_SEASONS_OPTIONS, $data);
    
    if (!$ai) {
        $ai = $last_id + 1;
    }
    
    update_option(AC_OPT_SEASONS_OPTIONS_AUTO_INCREMENT, $ai);
}

function ac_admin_settings() {
    $vars = array();
    
    if (isset($_POST) && !empty($_POST)) {
        ac_admin_settings_process($_POST);
        $vars['message'] = __('Settings saved.', AC_TEXTDOMAIN);
    }
    
    ac_template('settings', $vars);
}

function ac_admin_settings_process($data) {
    $enabled = isset($data[AC_OPT_ENABLED]) ? (bool)$data[AC_OPT_ENABLED] : false;
    update_option(AC_OPT_ENABLED, $enabled);
    
    $enabled = isset($data[AC_OPT_ENABLED_CALENDAR]) ? (bool)$data[AC_OPT_ENABLED_CALENDAR] : false;
    update_option(AC_OPT_ENABLED_CALENDAR, $enabled);
    
    $enabled = isset($data[AC_OPT_ENABLED_PRICE_TABLE]) ? (bool)$data[AC_OPT_ENABLED_PRICE_TABLE] : false;
    update_option(AC_OPT_ENABLED_PRICE_TABLE, $enabled);
    
    $theme = isset($data[AC_OPT_THEME]) ? $data[AC_OPT_THEME] : ac_get_default_theme();
    update_option(AC_OPT_THEME, $theme);
    
    $theme = isset($data[AC_OPT_CURRENCY]) ? $data[AC_OPT_CURRENCY] : ac_get_default_currency();
    update_option(AC_OPT_CURRENCY, $theme);
}

function ac_calendar($atts) {
    global $post;
    
    $html = null;
    
    extract(shortcode_atts(array(
        'month' => date('m'),
        'year' => date('Y'),
        'post_id' => $post->ID,
        'lang' => defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'en',
        'months' => '',
    ), $atts));
    
    $_post = get_post($post->ID);
    $ptid = $_post->post_type;
    
    if (empty($months)) {
        $months = ac_get_post_type_option($ptid, 'front');
    }
    
    if (!ac_get_enabled('calendar') || empty($months)) {
        return $html;
    }
    
    $pto = $months;
    $script = null;
    
    for($i = 0; $i < $pto; $i++) {
        $script .= 'ac_calendar("calendars-' . $post_id . '", ' . $year . ', ' . $month . ', false);' . PHP_EOL;
        
        if ($month < 12) {
            $month++;
        } else {
            $month -= 11;
            $year++;
        }
    }
    
    $price_table_meta = get_post_meta($post_id, AC_META_PRICE_TABLE, true);
    $price_table = array();
    
    $durations = ac_get_duration_options();
    
    if (is_array($price_table_meta) && count($price_table_meta) > 0) {
        foreach($price_table_meta as $row) {
            if (substr_count($row['start'], '/')) {
                list($day, $month, $year) = explode('/', $row['start']);
                $row['start'] = $year . '-' . $month . '-' . $day;
            }
            
            if (substr_count($row['end'], '/')) {
                list($day, $month, $year) = explode('/', $row['end']);
                $row['end'] = $year . '-' . $month . '-' . $day;
            }
            
            $price_table[] = array(
                'start' => strtotime($row['start'] . '-') * 1000,
                'end' => strtotime($row['end']) * 1000,
                'currency' => ac_get_currency_symbol($row['currency']),
                'duration' => $row['duration']
            );
        }
    }
    
    $script = 'jQuery(document).ready(function() { ' . PHP_EOL . $script . '});' . PHP_EOL;
    
    $html = '<div id="calendars-wrapper-' . $post_id . '" class="calendars-wrapper" data-id="' . $post_id . '"><div id="calendar-prev-' . $post_id . '" class="calendar-prev" data-id="' . $post_id . '"><img src="'.plugins_url('/images/but-prev.png', __FILE__).'"></div><div id="calendars-' . $post_id . '" class="calendars" data-id="' . $post_id . '"></div><div id="calendar-next-' . $post_id . '" class="calendar-next" data-id="' . $post_id . '"><img src="'.plugins_url('/images/but-next.png', __FILE__).'"></div><br style="clear: both" /></div>';
    $html .= '<script type="text/javascript">' . PHP_EOL 
            . 'var AC_ADMIN = false;' . PHP_EOL 
            . 'var AC_VIEW = ' . $pto . ';' . PHP_EOL 
            . 'var AC_YEAR = ' . date('Y') . ';' . PHP_EOL 
            . 'var AC_MONTH = ' . date('m') . ';' . PHP_EOL 
            . 'var AC_LANG = "' . $lang . '";' . PHP_EOL 
            . 'var AC_PRICE_TABLE = ' . json_encode($price_table) . ';' . PHP_EOL
            . 'var AC_DURATIONS = ' . json_encode($durations) . ';' . PHP_EOL
            . $script . ac_get_script($ptid) . '</script>';
    
    $html .= '<style type="text/css">' . ac_get_styles($ptid) . '</style>';
    
    $meta = get_post_meta($post_id, AC_META_CALENDAR, array());
    
    $html .= '<input type="hidden" id="ac_data-' . $post_id . '" class="ac_data" data-id="' . $post_id . '" name="ac_data" value="' . esc_attr(json_encode($meta)) . '" />';
    
    return $html;
}

function ac_get_availability_options() {
    $opts = get_option(AC_OPT_AVAILABILITY_OPTIONS, false);
    
    if (!$opts) {
        $opts = ac_get_default_availability_options();
    }
    
    return $opts;
}

function ac_get_availability_options_auto_increment() {
    $ai = get_option(AC_OPT_AVAILABILITY_OPTIONS_AUTO_INCREMENT);
    
    if (!$ai) {
        $ai = count(ac_get_availability_options()) + 1;
    }
    
    return $ai;
}

function ac_get_currency() {
    $currency = get_option(AC_OPT_CURRENCY, false);
    
    if (!$currency) {
        $currency = ac_get_default_currency();
    }
    
    return $currency;
}

function ac_get_currency_options() {
    return array(
        'euro' => '&euro; - Euro',
        'pound' => '&pound; - British Pound',
        'usd' => '$ - US Dollar'
    );
}

function ac_get_currency_symbol($currency) {
    switch($currency) {
        case 'euro':
            $symbol = '&euro;';
            break;
        case 'pound':
            $symbol = '&pound;';
            break;
        case 'usd':
            $symbol = '$';
            break;
        default:
            if (substr_count($currency, ' - ')) {
                list($symbol) = explode(' - ', $currency);
                $symbol = trim($symbol);
            } else {
                $symbol = '?';
            }
            break;
    }
    
    return $symbol;
}

function ac_get_default_availability_options() {
    return array(
        1 => array(
            'name' => __('Available', AC_TEXTDOMAIN),
            'color_bg' => 'ADFFB0',
            'color_text' => '000000',
            'image' => null
        ),
        2 => array(
            'name' => __('Not available', AC_TEXTDOMAIN),
            'color_bg' => 'FF8A8A',
            'color_text' => 'B84848',
            'image' => null
        ),
        3 => array(
            'name' => __('Start date', AC_TEXTDOMAIN),
            'color_bg' => 'FFFDC4',
            'color_text' => '000000',
            'image' => null
        ),
        4 => array(
            'name' => __('End date', AC_TEXTDOMAIN),
            'color_bg' => 'FFFDC4',
            'color_text' => '000000',
            'image' => null
        )
    );
}

function ac_get_default_currency() {
    return 'euro';
}

function ac_get_default_duration_options() {
    return array(
        1 => array(
            'name' => __('Night', AC_TEXTDOMAIN),
            'minimal_stay' => true
        ),
        2 => array(
            'name' => __('Weekend', AC_TEXTDOMAIN),
            'minimal_stay' => true
        ),
        3 => array(
            'name' => __('Week', AC_TEXTDOMAIN),
            'minimal_stay' => true
        ),
        4 => array(
            'name' => __('Month', AC_TEXTDOMAIN),
            'minimal_stay' => true
        )
    );
}

function ac_get_default_seasons_options() {
    return array(
       1 => array(
            'name' => __('Low Season', AC_TEXTDOMAIN)
        ),
        2 => array(
            'name' => __('Mid Season', AC_TEXTDOMAIN)
        ),
        3 => array(
            'name' => __('High Season', AC_TEXTDOMAIN)
        ) 
    );
}

function ac_get_display_options() {
    static $display_options = array();
    
    if (empty($display_options)) {
        $display_options[0] = __('Disabled', AC_TEXTDOMAIN);
        
        for($i = 1; $i <= 12; $i++) {
            if ($i == 1) {
                $title = __('1 month', AC_TEXTDOMAIN);
            } else if ($i == 12) {
                $title = __('1 year', AC_TEXTDOMAIN);
            } else {
                $title = sprintf(__('%d months', AC_TEXTDOMAIN), $i);
            }
            
            $display_options[$i] = $title;
        }
    }
    
    return $display_options;
}

function ac_get_default_theme() {
    return 'default.css';
}

function ac_get_duration_options() {
    $opts = get_option(AC_OPT_DURATION_OPTIONS, false);
    
    if (!$opts) {
        $opts = ac_get_default_duration_options();
    }
    
    return $opts;
}

function ac_get_duration_options_auto_increment() {
    $ai = get_option(AC_OPT_DURATION_OPTIONS_AUTO_INCREMENT);
    
    if (!$ai) {
        $ai = count(ac_get_duration_options()) + 1;
    }
    
    return $ai;
}

function ac_get_enabled($feature = null) {
    $enabled = get_option(AC_OPT_ENABLED, false);
    
    if ($feature == 'calendar') {
        $enabled = $enabled && get_option(AC_OPT_ENABLED_CALENDAR, false);
    } else if ($feature == 'price_table') {
        $enabled = $enabled && get_option(AC_OPT_ENABLED_PRICE_TABLE, false);
    }
    
    return $enabled;
}

function ac_get_enabled_calendar() {
    static $enabled = null;
    
    if (is_null($enabled)) {
        $enabled = get_option(AC_OPT_ENABLED_CALENDAR, false);
    }
    
    return $enabled;
}

function ac_get_enabled_price_table() {
    static $enabled = null;
    
    if (is_null($enabled)) {
        $enabled = get_option(AC_OPT_ENABLED_PRICE_TABLE, false);
    }
    
    return $enabled;
}

function ac_get_enabled_options() {
    return array(0 => __('Disabled', AC_TEXTDOMAIN), 1 => __('Enabled', AC_TEXTDOMAIN));
}

function ac_get_post_type_option($ptid, $type, $return = 0) {
    $ptos = ac_get_post_type_options();
    
    if (isset($ptos[$ptid]) && isset($ptos[$ptid][$type])) {
        $return = $ptos[$ptid][$type];
    }
    
    return $return;
}

function ac_get_post_type_options() {
    static $options = null;
    
    if (is_null($options)) {
        $options = get_option(AC_OPT_POST_TYPE_OPTIONS, array());
    }
    
    return $options;
}

function ac_get_post_types() {
    global $wp_post_types;
    static $pts = array();
    
    if (empty($pts)) {
        $builtin = array('post', 'page');

        foreach($wp_post_types as $ptid => $pt) {
            if ( ( in_array($ptid, $builtin) && $pt->_builtin ) || ( !$pt->_builtin && $pt->public ) ) {
                $pts[$ptid] = $pt->labels->singular_name;
            }
        }
    }
    
    return $pts;
}

function ac_get_seasons_options() {
    $opts = get_option(AC_OPT_SEASONS_OPTIONS, false);
    
    if (!$opts) {
        $opts = ac_get_default_seasons_options();
    }
    
    return $opts;
}

function ac_get_SEASONS_options_auto_increment() {
    $ai = get_option(AC_OPT_SEASONS_OPTIONS_AUTO_INCREMENT);
    
    if (!$ai) {
        $ai = count(ac_get_seasons_options()) + 1;
    }
    
    return $ai;
}

function ac_get_script($ptid) {
    $aos = ac_get_availability_options();
    $default_aid = ac_get_post_type_option($ptid, 'default');
    
    $default = null;
    $script = 'var AC_AVAILABILITY_OPTIONS = new Array();' . PHP_EOL;
    $script .= 'var AC_TITLES = {};' . PHP_EOL;
    
    foreach($aos as $aid => $ao) {
        if (ac_get_post_type_option($ptid, $aid)) {
            $script .= 'AC_AVAILABILITY_OPTIONS.push(' . $aid . ');' . PHP_EOL;
            $script .= 'AC_TITLES[' . $aid . '] = "' . $ao['name'] . '";' . PHP_EOL;
        }
        
        if ($default_aid == $aid) {
            $default = 'var AC_DEFAULT_AVAILABILITY = ' . $aid . ';' . PHP_EOL;
        }
    }
    
    $script = $default . $script;
    
    $script .= 'jQuery(document).ready(function() { ac_init_colors(); ac_colorize(); });';
    
    return $script;
}

function ac_get_styles($ptid) {
    $aos = ac_get_availability_options();
    $default_aid = ac_get_post_type_option($ptid, 'default');
    
    $default = null;
    $styles = null;
    
    foreach($aos as $aid => $ao) {
        if (isset($ao['image']) && !empty($ao['image'])) {
            $image = 'url(' . AC_UPLOAD_URL . $ao['image'] . ') no-repeat center center';
        } else {
            $image = null;
        }
        
        $style = '{ background: #' . $ao['color_bg'] . ' ' . $image . '; color: #' . $ao['color_text'] . '; }';
        
        if ($aid == $default_aid) {
            $default = '.ac-day ' . $style . PHP_EOL;
        }
        
        $styles .= '.ac-availability-' . $aid . ' ' . $style . PHP_EOL;
    }
    
    $styles = $default . $styles;
    
    return $styles;
}

function ac_get_theme() {
    $theme = get_option(AC_OPT_THEME, false);
    
    if (!$theme) {
        $theme = ac_get_default_theme();
    }
    
    return $theme;
}

function ac_get_theme_options() {
    return array(
        'default.css' => 'Default',
        'light.css' => 'Light',
        'dark.css' => 'Dark',
        'colorful.css' => 'Colorful',
        'modern.css' => 'Modern'
    );
}

function ac_image_preview($image, $aid) {
    $src = AC_UPLOAD_URL . $image;
    
    echo 'Image preview: <img src="' . $src . '" class="ac-image" alt="" /><br />';
    echo '<input type="checkbox" name="remove_image[' . $aid . ']" value="1" /> Remove Image';
}

function ac_meta_box() {
    global $post;
    
    $lang = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'en';
    $pid = $post->ID;
    $ptid = $post->post_type;
    $month = date('m');
    $year = date('Y');
    
    $pto = ac_get_post_type_option($ptid, 'admin');
    $script = null;
    
    for($i = 0; $i < $pto; $i++) {
        $script .= 'ac_calendar("calendars-' . $pid . '", ' . $year . ', ' . $month . ', true);' . PHP_EOL;
        
        if ($month < 12) {
            $month++;
        } else {
            $month -= 11;
            $year++;
        }
    }
    
    $script = 'jQuery(document).ready(function() { ' . PHP_EOL . $script . '});' . PHP_EOL;
    
    echo '<div id="calendars-wrapper-' . $pid . '" class="calendars-wrapper" data-id="' . $pid . '"><div id="calendar-prev-' . $pid . '" class="calendar-prev" data-id="' . $pid . '"><img src="'.plugins_url('/images/but-prev.png', __FILE__).'"></div><div id="calendars-' . $pid . '" class="calendars" data-id="' . $pid . '"></div><div id="calendar-next-' . $pid . '" class="calendar-next" data-id="' . $pid . '"><img src="'.plugins_url('/images/but-next.png', __FILE__).'"></div><br style="clear: both" /></div>';

    echo '<script type="text/javascript">' . PHP_EOL 
            . 'var AC_ADMIN = true;' . PHP_EOL 
            . 'var AC_VIEW = ' . $pto . ';' . PHP_EOL 
            . 'var AC_YEAR = ' . date('Y') . ';' . PHP_EOL 
            . 'var AC_MONTH = ' . date('m') . ';' . PHP_EOL 
            . 'var AC_LANG = "' . $lang . '";' . PHP_EOL 
            . $script . ac_get_script($ptid) . '</script>';
    
    echo '<style type="text/css">' . ac_get_styles($ptid) . '</style>';
    
    wp_nonce_field('ac_meta_box', 'ac_meta_box_nonce');
    
    $meta = get_post_meta($pid, AC_META_CALENDAR, true);
    
    if (!$meta) {
        $meta = array();
    }
    
    echo '<input type="hidden" id="ac_data-' . $pid . '" class="ac_data" data-id="' . $pid . '" name="ac_data" value="' . esc_attr(str_replace(array('[', ']'), array('', ''), json_encode($meta))) . '" />';
}

function ac_meta_box_price_table() {
    global $post;
    
    $lang = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'en';
    $pid = $post->ID;
    $ptid = $post->post_type;
    
    $ds = ac_get_duration_options();
    $ss = ac_get_seasons_options();
    $cs = ac_get_currency_options();
    
    $c = ac_get_currency();
    
    $tds = ac_get_post_type_option($ptid, 'duration');
    $tss = ac_get_post_type_option($ptid, 'season');
    $tms = ac_get_post_type_option($ptid, 'minimal_stay');
    
    $meta = get_post_meta($pid, AC_META_PRICE_TABLE, true);

    if (!$meta) {
        $meta = array();
    }
    
    $count = 5 + count($ds);
    
    echo '<div id="pricetable-wrapper"><table id="ac-pricetable">';
    
    echo '<thead><tr>';
    echo '<th>' . __('Delete', AC_TEXTDOMAIN) . '</th>';
    echo '<th>' . __('Season', AC_TEXTDOMAIN) . '</th>';
    echo '<th width="100px">' . __('Start', AC_TEXTDOMAIN) . '</th>';
    echo '<th width="100px">' . __('End', AC_TEXTDOMAIN) . '</th>';
    
    foreach($ds as $did => $d) {
        if (isset($tds[$did])) {
            echo '<th width="100px">' . __($d['name'], AC_TEXTDOMAIN) . '</th>';
        }
    }
    
    echo '<th width="100px">' . __('Minimal Stay', AC_TEXTDOMAIN) . '</th>';
    echo '<th width="100px">' . __('Currency', AC_TEXTDOMAIN) . '</th>';
    echo '</tr></thead>';
    
    echo '<tbody>';
    
    foreach($meta as $mid => $row) {
    
        echo '<tr><td><input type="checkbox" name="price_table[' . $mid . '][delete]" value="1" /> Yes</td>';
        echo '<td><select name="price_table[' . $mid . '][season]" id="">';

        foreach($ss as $sid => $s) {
            if (isset($tss[$sid])) {
                $sn = $s['name'];
                $selected = $sid == $row['season'] ? ' selected="selected"' : null;
                echo '<option value="' . $sid . '"' . $selected . '>' . __($sn, AC_TEXTDOMAIN) . '</option>';
            }
        }

        echo '</select></td>';
        echo '<td><input type="text" name="price_table[' . $mid . '][start]" id="" class="date-picker" value="' . $row['start'] . '" /></td>';
        echo '<td><input type="text" name="price_table[' . $mid . '][end]" id="" class="date-picker" value="' . $row['end'] . '" /></td>';

        foreach($ds as $did => $d) {
            if (isset($tds[$did])) {
                $value = isset($row['duration']) && isset($row['duration'][$did]) ? $row['duration'][$did] : null;
                echo '<td><input type="text" name="price_table[' . $mid . '][duration][' . $did . ']" id="" class="" value="' . $value . '" onkeypress="return ac_is_number(event)" /></td>';
            }
        }

        echo '<td><select name="price_table[' . $mid . '][minimal_stay]">';
        
        $msv = isset($row['minimal_stay']) ? $row['minimal_stay'] : $tms;
        
        foreach($ds as $did => $d) {
            if (isset($d['minimal_stay']) && $d['minimal_stay']) {
                $selected = $msv == $did ? ' selected="selected"' : null;
                echo '<option value="' . $did . '"' . $selected . '>' . __($d['name'], AC_TEXTDOMAIN) . '</option>';
            }
        }

        echo '</select></td>';
        
        echo '<td><select name="price_table[' . $mid . '][currency]">';
        
        $cv = isset($row['currency']) ? $row['currency'] : $c;
        
        foreach($cs as $cid => $cname) {
            $selected = $cv == $cid ? ' selected="selected"' : null;
            echo '<option value="' . $cid . '"' . $selected . '>' . __($cname, AC_TEXTDOMAIN) . '</option>';
        }

        echo '</select></tr>';
    }
    
    echo '<tr id="ac_table_last_tr"><td colspan="' . $count . '"><a class="add-new-pt" href="#">Add New</a></td></tr>';
    
    echo '</tbody>';
    
    echo '</table></div>';
    
    $seasons = '<select name="price_table[%d][season]" id="">';

    foreach($ss as $sid => $s) {
        if (isset($tss[$sid])) {
            $sn = $s['name'];
            $seasons .= '<option value="' . $sid . '">' . __($sn, AC_TEXTDOMAIN) . '</option>';
        }
    }

    $seasons .= '</select>';
    
    $durations = null;
    
    foreach($ds as $did => $d) {
        if (isset($tds[$did])) {
            $durations .= '<td><input type="text" name="price_table[%d][duration][' . $did . ']" id="" class="" value="" onkeypress="return ac_is_number(event)" /></td>';
        }
    }

    $minimal_stays = '<select name="price_table[%d][minimal_stay]">';
    
    foreach($ds as $did => $d) {
        if (isset($d['minimal_stay']) && $d['minimal_stay']) {
            $selected = $tms == $did ? ' selected="selected"' : null;
            $minimal_stays .= '<option value="' . $did . '"' . $selected . '>' . __($d['name'], AC_TEXTDOMAIN) . '</option>';
        }
    }

    $minimal_stays .= '</select>';
    
    $currencies = '<select name="price_table[%d][currency]">';
    
    foreach($cs as $cid => $cname) {
        $selected = $c == $cid ? ' selected="selected"' : null;
        $currencies .= '<option value="' . $cid . '"' . $selected . '>' . __($cname, AC_TEXTDOMAIN) . '</option>';
    }

    $currencies .= '</select>';
    
    echo '<script type="text/javascript">';
    echo 'AC_PRICE_TABLE_ROWS = ' . count($meta) . ';';
    echo 'AC_PRICE_TABLE_SEASONS = "' . str_replace('"', '\"', $seasons) . '";';
    echo 'AC_PRICE_TABLE_DURATIONS = "' . str_replace('"', '\"', $durations) . '";';
    echo 'AC_PRICE_TABLE_MINIMAL_STAYS = "' . str_replace('"', '\"', $minimal_stays) . '";';
    echo 'AC_PRICE_TABLE_CURRENCIES = "' . str_replace('"', '\"', $currencies) . '";';
    echo '</script>';
}

function ac_pre_post_update($pid) {
    global $post;
    
    if (!isset( $_POST['ac_meta_box_nonce'])) {
        return $pid;
    }
    
    $nonce = $_POST['ac_meta_box_nonce'];

    if (!wp_verify_nonce($nonce, 'ac_meta_box')) {
        return $pid;
    }

    if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
        return $pid;
    }
    
    if ('page' == $_POST['post_type'] && !current_user_can('edit_page', $pid)) {
        return $pid;
    } else if (!current_user_can('edit_post', $pid)) {
        return $pid;
    }

    $pid = $post->ID;
    $ptid = $post->post_type;
    $default_aid = ac_get_post_type_option($ptid, 'default');
    $tmp = array();
    
    if (isset($_POST['ac_data'])) {
        $data = str_replace('\"', '"', $_POST['ac_data']);
        $data = json_decode($data);
        $data = (array)$data;
        
        if (is_array($data) && !empty($data)) {
            foreach($data as $date => $aid) {
                if ($aid == $default_aid) {
                    continue;
                }

                $tmp[$date] = $aid;
            }
        }
        
        if ( !count( $tmp ) ) {
            delete_post_meta($pid, AC_META_CALENDAR);
        } else {
            update_post_meta($pid, AC_META_CALENDAR, $tmp);
        }
    }
    
    if (isset($_POST['price_table'])) {
        $tmp = array();
        
        foreach($_POST['price_table'] as $id => $row) {
            if (isset($row['delete']) && $row['delete']) {
                continue;
            }
            
            $tmp[$id] = $row;
        }

        if ( !count( $tmp ) ) {
            delete_post_meta($pid, AC_META_PRICE_TABLE);
        } else {
            update_post_meta($pid, AC_META_PRICE_TABLE, $tmp);
        }
    }
}

function ac_price_table($atts) {
    global $post;
    
    $html = null;
    
    extract(shortcode_atts(array(
        'post_id' => $post->ID,
        'lang' => defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'en'
    ), $atts));
    
    if (!ac_get_enabled('price_table')) {
        return $html;
    }
   
    $meta = get_post_meta($post_id, AC_META_PRICE_TABLE, true);
    
    if (!is_array($meta) || empty($meta)) {
        return $html;
    }
    
    $_post = get_post($post_id);
    $ptid = $_post->post_type;
    
    $ds = ac_get_duration_options();
    $ss = ac_get_seasons_options();
    
    $tds = ac_get_post_type_option($ptid, 'duration');
    
    $html = '<div id="ac-price-table-wrapper-' . $post_id . '" class="ac-price-table-wrapper" data-id="' . $post_id . '"><table id="ac-price-table-' . $post_id . '" class="ac-price-table" data-id="' . $post_id . '"><thead><tr>';
    
    $html .= '<th>' . __('Season', AC_TEXTDOMAIN) . '</th>';
    $html .= '<th>' . __('Start', AC_TEXTDOMAIN) . '</th>';
    $html .= '<th>' . __('End', AC_TEXTDOMAIN) . '</th>';
    
    foreach($ds as $did => $d) {
        if (isset($tds[$did]) && $tds[$did]) {
            $html .= '<th>' . __($d['name'], AC_TEXTDOMAIN) . '</th>';
        }
    }
    
    $html .= '<th>' . __('Minimal Stay', AC_TEXTDOMAIN) . '</th>';
    
    $html .= '</tr></thead><tbody>';
    
    foreach($meta as $k => $row) {
        $html .= '<tr>';
        
        $html .= '<td>' . (isset($ss[$row['season']]) ? $ss[$row['season']]['name'] : null) . '</td>';
        
        if (substr_count($row['start'], '-')) {
            list($year, $month, $day) = explode('-', $row['start']);
            $row['start'] = $day . '/' . $month . '/' . $year;
        }
        
        if (substr_count($row['end'], '-')) {
            list($year, $month, $day) = explode('-', $row['end']);
            $row['end'] = $day . '/' . $month . '/' . $year;
        }
        
        $html .= '<td>' . $row['start'] . '</td>';
        $html .= '<td>' . $row['end'] . '</td>';
        
        foreach($ds as $did => $d) {
            if (isset($tds[$did])) {
                $html .= '<td>' . ac_get_currency_symbol($row['currency']) . '' . $row['duration'][$did] . '</td>';
            }
        }
    
        $html .= '<td>' . (isset($ds[$row['minimal_stay']]) ? $ds[$row['minimal_stay']]['name'] : null) . '</td>';
        
        $html .= '</tr>';
    }
    
    // data
    
    $html .= '</tbody></table></div>';
    
    return $html;
}

function ac_template($template, $vars = array()) {
    $__template = AC_SRC_PHP_TPL . $template . '.php';
    
    extract($vars);
    ob_start();
    
    if (!is_readable($__template)) {
        printf(__('Template file "%s not found.', AC_TEXTDOMAIN), $__template);
    } else {
        include $__template;
    }
    
    echo ob_get_clean();
}

function ac_wp_enqueue_scripts() {
    wp_enqueue_script('jquery-ui-datepicker');
    
    wp_register_script('ac-js', plugins_url('/media/js/ac.js', AC_AVAILABILITY_CALENDAR_PLUGIN), array('jquery'));
    wp_enqueue_script('ac-js');
	//wp_enqueue_script( 'ac-js',plugins_url('/media/js/ac.js'),'', '', true );
    
    wp_register_style('ac-css', plugins_url( '/media/css/' . ac_get_theme(), AC_AVAILABILITY_CALENDAR_PLUGIN ));
    wp_enqueue_style('ac-css');
}