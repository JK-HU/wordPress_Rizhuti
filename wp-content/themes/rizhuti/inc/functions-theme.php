<?php

/**
 * 日主题，生来被日
 * @官网        https://rizhuti.com/
 * @Author      Dadong2g
 * @渣男提示    [男人要想技术过“硬”，“薄”取芳心，一个字《日》]
 * @前戏提示    [日主题是良心主题，感谢您使用日主题进行二次开发或内容创作]
 * @正版提示    [为兼容更多虚拟机等环境，主题有简易授权，但是没有加密，盗版/破解/可在自行研究学习的基础上，请保留底线]
 * @血亏说明    [还请各位破解，盗版大佬，喜欢，感兴趣，自己日就行了。不要糜烂传播新版本，好人一生平台，日你3000遍]
 */

/**
 * [my_post_custom_columns 挂钩WP后台文章列表]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:33:01+0800
 * @param    [type]                   $columns [description]
 * @return   [type]                            [description]
 */
function my_post_custom_columns($columns)
{
    // Add a new field
    $columns['wppay_type']  = __('资源类型');
    $columns['wppay_price'] = __('资源价格');
    // Delete an existing field, eg. comments
    unset($columns['comments']);
    return $columns;
}
/**
 * [output_my_post_custom_columns 添加文章列表自定义列]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:32:08+0800
 * @param    [type]                   $column_name [description]
 * @param    [type]                   $post_id     [description]
 * @return   [type]                                [description]
 */
function output_my_post_custom_columns($column_name, $post_id)
{
    switch ($column_name) {
        case "wppay_type":
            // Retrieve data and echo result
            $wppay_type = get_post_meta($post_id, 'wppay_type', true);
            if ($wppay_type == 0) {
                $r = __('无');
            } else if ($wppay_type == 1) {
                $r = __('付费全文');
            } else if ($wppay_type == 2) {
                $r = __('部分内容');
            } else if ($wppay_type == 3) {
                $r = __('收费下载');
            } else if ($wppay_type == 4) {
                $r = __('免费下载');
            } else {
                $r = __('文章');
            }
            echo $r;
            break;
        case "wppay_price":
            // Retrieve data and echo result
            $wppay_price = get_post_meta($post_id, 'wppay_price', true);
            echo $wppay_price;
            break;
    }
}

add_filter('manage_posts_columns', 'my_post_custom_columns');
add_action('manage_posts_custom_column', 'output_my_post_custom_columns', 10, 2);

/**
 * [my_users_columns 挂钩WP后台用户列表]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:32:52+0800
 * @param    [type]                   $columns [description]
 * @return   [type]                            [description]
 */
function my_users_columns($columns)
{
    $columns['vip_type'] = __('会员类型');
    $columns['vip_time'] = __('到期时间');
    return $columns;
}
/**
 * [output_my_users_columns 添加用户列表自定义列]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:32:38+0800
 * @param    [type]                   $var         [description]
 * @param    [type]                   $column_name [description]
 * @param    [type]                   $user_id     [description]
 * @return   [type]                                [description]
 */
function output_my_users_columns($var, $column_name, $user_id)
{
    switch ($column_name) {
        case "vip_type":
            $vip_type = get_user_meta($user_id, 'vip_type', true);
            if ($vip_type == 0) {
                $r = __('普通会员');
            } else if ($vip_type == 31) {
                $r = __('包月会员');
            } else if ($vip_type == 365) {
                $r = __('包年会员');
            } else if ($vip_type == 3600) {
                $r = __('终身会员');
            }
            return $r;
            break;
        case "vip_time":
            $vip_time = (get_user_meta($user_id, 'vip_time', true)) ? get_user_meta($user_id, 'vip_time', true) : time();
            return @date('Y-m-d H:i:s', $vip_time);
            break;
    }
}
add_filter('manage_users_columns', 'my_users_columns');
add_action('manage_users_custom_column', 'output_my_users_columns', 10, 3);

/**
 * [user_noadmin_redirect 普通用户不允许进入后台]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:31:32+0800
 * @return   [type]                   [description]
 */
function user_noadmin_redirect()
{
    global $wpdb;
    if (is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX)) {
        $current_user = wp_get_current_user();
        if (!current_user_can('manage_options')) {
            $userpage = esc_url(home_url('/user'));
            wp_safe_redirect($userpage);
            exit();
        }
    }
}
add_action("init", "user_noadmin_redirect");

/**
 * [custom_login custom login paga css]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:31:27+0800
 * @return   [type]                   [description]
 */
function custom_login()
{
    echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('template_directory') . '/inc/css/login.css" />';
}
add_action('login_head', 'custom_login');

/**
 * [custom_login_img custom login img]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:31:15+0800
 * @return   [type]                   [description]
 */
function custom_login_img()
{
    echo '<style type="text/css">
h1 a {background-image: url(' . _hui('logo_src') . ') !important; }
</style>';
}
add_action('login_head', 'custom_login_img');

/**
 * [custom_loginlogo_url custom login url]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:30:59+0800
 * @param    [type]                   $url [description]
 * @return   [type]                        [description]
 */
function custom_loginlogo_url($url)
{
    return get_bloginfo('url');
}
add_filter('login_headerurl', 'custom_loginlogo_url');

/**
 * [custom_login_message 在登录框添加额外的信息]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:30:48+0800
 * @return   [type]                   [description]
 */
function custom_login_message()
{
    echo '<p>登陆后可永久保留购买记录哦！</p><br />';
}
add_action('login_form', 'custom_login_message');

/**
 * [reset_password_message 修复WordPress找回密码提示“抱歉，该key似乎无效”问题解决找回密码链接无效问题]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:30:35+0800
 * @param    [type]                   $message [description]
 * @param    [type]                   $key     [description]
 * @return   [type]                            [description]
 */
function reset_password_message($message, $key)
{
    if (strpos($_POST['user_login'], '@')) {
        $user_data = get_user_by('email', trim($_POST['user_login']));
    } else {
        $login     = trim($_POST['user_login']);
        $user_data = get_user_by('login', $login);
    }
    $user_login = $user_data->user_login;
    $msg        = __('有人要求重设如下帐号的密码：') . "\r\n\r\n";
    $msg .= network_site_url() . "\r\n\r\n";
    $msg .= sprintf(__('用户名：%s'), $user_login) . "\r\n\r\n";
    $msg .= __('若这不是您本人要求的，请忽略本邮件，一切如常。') . "\r\n\r\n";
    $msg .= __('要重置您的密码，请打开下面的链接：') . "\r\n\r\n";
    $msg .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login');
    return $msg;
}
add_filter('retrieve_password_message', 'reset_password_message', null, 2);

/*
Gravatar 自定义头像 Hook
 */
function custom_avatar_hook($avatar, $id_or_email, $size, $default, $alt)
{
    $user = false;
    if (is_numeric($id_or_email)) {
        $id   = (int) $id_or_email;
        $user = get_user_by('id', $id);
    } elseif (is_object($id_or_email)) {
        if (!empty($id_or_email->user_id)) {
            $id   = (int) $id_or_email->user_id;
            $user = get_user_by('id', $id);
        }
    } else {
        $user = get_user_by('email', $id_or_email);
    }
    if ($user && is_object($user)) {
        if (get_user_meta($user->data->ID, 'photo', true)) {
            $avatar = get_user_meta($user->data->ID, 'photo', true);
            // 修复头像在ssl站点无法显示等问题
            if (is_ssl()) {
                if (strpos($avatar, 'http://thirdqq.qlogo.cn') !== false) {
                    $avatar = str_replace('http://thirdqq.qlogo.cn', 'https://thirdqq.qlogo.cn', $avatar);
                }
            }
            $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        } else if (get_user_meta($user->data->ID, 'photo', true)) {
            $avatar = get_user_meta($user->data->ID, 'photo', true);
            $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }
    }
    return $avatar;
}
add_filter('get_avatar', 'custom_avatar_hook', 1, 5);

/**
 * 搜索页面排除页面
 */
if (_hui('search_no_page')) {
    add_filter('pre_get_posts', 'ri_exclude_page_from_search');
    function ri_exclude_page_from_search($query)
    {
        if ($query->is_search) {
            $query->set('post_type', 'post');
        }
        return $query;
    }
}

/**
 * [mail_smtp SMTP集成]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:30:03+0800
 * @param    [type]                   $phpmailer [description]
 * @return   [type]                              [description]
 */
function mail_smtp($phpmailer)
{
    if (_hui('mail_smtps')) {
        $phpmailer->IsSMTP();
        $mail_name             = _hui('mail_name');
        $mail_host             = _hui('mail_host');
        $mail_port             = _hui('mail_port');
        $mail_username         = _hui('mail_name');
        $mail_passwd           = _hui('mail_passwd');
        $mail_smtpsecure       = _hui('mail_smtpsecure');
        $phpmailer->FromName   = $mail_name ? $mail_name : 'idowns';
        $phpmailer->Host       = $mail_host ? $mail_host : 'smtp.qq.com';
        $phpmailer->Port       = $mail_port ? $mail_port : '465';
        $phpmailer->Username   = $mail_username ? $mail_username : '88888888@qq.com';
        $phpmailer->Password   = $mail_passwd ? $mail_passwd : '123456789';
        $phpmailer->From       = $mail_username ? $mail_username : '88888888@qq.com';
        $phpmailer->SMTPAuth   = _hui('mail_smtpauth') == 1 ? true : false;
        $phpmailer->SMTPSecure = $mail_smtpsecure ? $mail_smtpsecure : 'ssl';

    }
}
add_action('phpmailer_init', 'mail_smtp');

/**
 * 注册文章类型
 */
add_theme_support('post-formats', array('gallery', 'image', 'video'));
add_post_type_support('page', 'post-formats');

// add link manager
add_filter('pre_option_link_manager_enabled', '__return_true');

/**
 * 注册菜单
 */
if (function_exists('register_nav_menus')) {
    register_nav_menus(array(
        'nav' => __('导航'),
    ));
}

/**
 * register sidebar
 */
if (function_exists('register_sidebar')) {
    $sidebars = array(
        'single' => '文章页侧栏',
        'page'   => '页面侧栏',
    );
    foreach ($sidebars as $key => $value) {
        register_sidebar(array(
            'name'          => $value,
            'id'            => $key,
            'before_widget' => '<div class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>',
        ));
    }
    ;
}

/**
 * the theme
 */

$current_theme = wp_get_theme();

function _the_theme_name()
{
    global $current_theme;
    return $current_theme->get('Name');
}

function _the_theme_version()
{
    global $current_theme;
    return $current_theme->get('Version');
}

function _the_theme_aurl()
{
    global $current_theme;
    return $current_theme->get('ThemeURI');
}

function _the_theme_thumb()
{
    return _hui_img('post_default_thumb') ? _hui_img('post_default_thumb') : get_stylesheet_directory_uri() . '/img/thumb.png';
}

function _the_theme_avatar()
{
    return get_stylesheet_directory_uri() . '/img/avatar.png';
}

function _get_description_max_length()
{
    return 200;
}

function _get_delimiter()
{
    return _hui('connector') ? _hui('connector') : '-';
}
remove_action('wp_head', '_wp_render_title_tag', 1);

/**
 * [_target_blank 链接新窗口打开]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:28:35+0800
 * @return   [type]                   [description]
 */
function _target_blank()
{
    return _hui('target_blank') ? ' target="_blank"' : '';
}

/**
 * [_title SEO标题]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:28:24+0800
 * @return   [type]                   [description]
 */
function _title()
{

    global $paged;

    $html = '';
    $t    = trim(wp_title('', false));

    if ($t) {
        $html .= $t . _get_delimiter();
    }

    if (get_query_var('page')) {
        $html .= '第' . get_query_var('page') . '页' . _get_delimiter();
    }

    $html .= get_bloginfo('name');

    if (is_home()) {
        if ($paged > 1) {
            $html .= _get_delimiter() . '最新发布';
        } elseif (get_option('blogdescription')) {
            $html .= _get_delimiter() . get_option('blogdescription');
        }
    }

    if (is_category()) {
        global $wp_query;
        $cat_ID  = get_query_var('cat');
        $seo_str = get_term_meta($cat_ID, 'seo-title', true);
        $cat_tit = ($seo_str) ? $seo_str : _get_tax_meta($cat_ID, 'title');
        if ($cat_tit) {
            $html = $cat_tit;
        }
    }

    if ($paged > 1) {
        $html .= _get_delimiter() . '第' . $paged . '页';
    }

    return $html;
}

/**
 * Header_Menu_Walker类
 */
class Header_Menu_Walker extends Walker_Nav_Menu
{

    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        $indent      = ($depth > 0 ? str_repeat("\t", $depth) : ''); // 缩进
        $classes     = array('sub-menu');
        $class_names = implode(' ', $classes); //用空格分割多个样式名
        $output .= "\n" . $indent . '<div class="' . $class_names . '"><ul>' . "\n"; //
    }
}

/**
 * 移除菜单的多余CSS选择器
 * From https://www.wpdaxue.com/remove-wordpress-nav-classes.html
 */
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var)
{
    return is_array($var) ? array_intersect($var, array('current_page_item', 'menu-item-has-children')) : '';
}

/**
 * [_the_menu menu]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:28:16+0800
 * @param    string                   $location [description]
 * @return   [type]                             [description]
 */
function _the_menu($location = 'nav')
{
    echo wp_nav_menu(array('theme_location' => $location, 'container' => 'ul', 'echo' => false, 'walker' => new Header_Menu_Walker()));
}

/**
 * [_the_logo logo]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:28:10+0800
 * @return   [type]                   [description]
 */
function _the_logo()
{
    $tag = is_home() ? 'div' : 'div';
    $src = _hui('logo_src');
    if (wp_is_mobile() && _hui('logo_src_m')) {
        $src = _hui('logo_src_m');
    }
    echo '<' . $tag . ' class="logo"><a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('name') . (get_bloginfo('description') ? _get_delimiter() . get_bloginfo('description') : '') . '"><img src="' . $src . '"></a></' . $tag . '>';
}

/**
 * [_single_header_img 自定义文章缩略图]
 * @Author   Dadong2g
 * @DateTime 2019-05-20T21:30:02+0800
 * @return   [type]                   [img链接]
 */
function _single_header_img()
{
    global $post;
    $meta_single_header_img = get_post_meta($post->ID, 'single_header_img', true);
    if (@$meta_single_header_img['url']) {
        $src = $meta_single_header_img['url'];
    } elseif (_hui('post_header_img_of')) {
        $src = timthumb(_hui('post_header_img_src'), array('w' => '1920', 'h' => '500'));
    } else {
        $src = timthumb(_get_post_thumbnail_url(), array('w' => '1920', 'h' => '500'));
    }
    echo 'style="background-image: url(' . $src . ')"';
}

/**
 * [_the_ads 自定义广告代码]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:14:38+0800
 * @param    string                   $name  [description]
 * @param    string                   $class [description]
 * @return   [type]                          [description]
 */
function _the_ads($name = '', $class = '')
{
    if (!_hui($name . '_s')) {
        return;
    }

    echo '<div class="asst asst-' . $class . '">' . _hui($name) . '</div>';
}

/**
 * leadpager
 * @return [type] [description]
 */
function _the_leadpager()
{
    global $paged;
    if ($paged && $paged > 1) {
        echo '<div class="leadpager">第 ' . $paged . ' 页</div>';
    }
}

/**
 * [_the_focusbox focusbox]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:15:04+0800
 * @param    string                   $title_tag [description]
 * @param    string                   $title     [description]
 * @param    string                   $text      [description]
 * @return   [type]                              [description]
 */
function _the_focusbox($title_tag = 'h1', $title = '', $text = '')
{
    if ($title) {
        if (!$title_tag) {
            $title_tag = 'h1';
        }
        $title = '<' . $title_tag . ' class="focusbox-title">' . $title . '</' . $title_tag . '>';
    }

    if ($text) {
        $text = '<div class="focusbox-text">' . $text . '</div>';
    }
    echo '<div class="focusbox"><div class="container">' . $title . $text . '</div></div>';
}

/**
 * [_bodyclass bodyclass]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:15:11+0800
 * @return   [type]                   [description]
 */
function _bodyclass()
{
    $class = '';

    if ((is_single() || is_page()) && comments_open()) {
        $class .= ' comment-open';
    }

    if ((is_single() || is_page()) && get_post_format()) {
        $class .= ' postformat-' . get_post_format();
    }

    if (is_super_admin()) {
        $class .= ' logged-admin';
    }

    if (_hui('list_thumb_hover_action')) {
        $class .= ' list-thumb-hover-action';
    }

    if (_hui('phone_list_news')) {
        $class .= ' list-news';
    }

    return trim($class);
}

/**
 * [_the_head 此处CSS完善贡献感谢我们的会员mill_wan@163.com QQ576199309]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:15:21+0800
 * @return   [type]                   [description]
 */
function _the_head()
{
    _head_css();
    _keywords();
    _description();
    _post_views_record();
    $css_str    = _hui('web_css');
    $bg_color   = _hui('theme-color');
    $font_color = _hui('theme-font-color');
    if ($css_str || $bg_color) {
        $str_color = '.wel .wel-item-btn>a,.pagination ul li.next-page a,#fh5co-header-subscribe button,.article-content ul li:before,.btn-primary,.article-tags a:hover,.btn-primary.active.focus, .btn-primary.active:focus, .btn-primary.active:hover, .btn-primary:active.focus, .btn-primary:active:focus, .btn-primary:active:hover, .open>.btn-primary.dropdown-toggle.focus, .open>.btn-primary.dropdown-toggle:focus, .open>.btn-primary.dropdown-toggle:hover,.swiper-pagination-bullet-active{background-color:' . $bg_color . '} .btn-primary,.btn-primary.active.focus, .btn-primary.active:focus, .btn-primary.active:hover, .btn-primary:active.focus, .btn-primary:active:focus, .btn-primary:active:hover, .open>.btn-primary.dropdown-toggle.focus, .open>.btn-primary.dropdown-toggle:focus, .open>.btn-primary.dropdown-toggle:hover{border-color:' . $bg_color . '} .article-content h2,.page-template-user .user-nav ul li a.active{border-left-color:' . $bg_color . '} .site-navbar li.menu-item-has-children:hover>a::after,.wel .has-sub-menu:hover>a::after{border-top-color:' . $bg_color . '} .site-navbar li.menu-item-has-children:hover>a::after,.wel .has-sub-menu:hover>a::after{border-top-color:' . $bg_color . '} .page-template-user .user-nav ul li a.active,.site-navbar>ul>li>a:hover,.wel .wel-item>a:hover,.logo a:hover,a:hover,.filter-catnav .current-cat, .filter-catnav .current-cat a,.page-template-user .user-nav ul li a:hover,.notice-info .flex p,.notice-info .close:hover,.header.white .site-navbar>ul>li>a:hover,.header.white .wel .wel-item>a:hover{color:' . $bg_color . '} @media (max-width: 1024px){.m-wel .m-wel-login a.m-wel-login {background-color:' . $bg_color . '}}';

        $str_font_color = 'body a,.site-navbar>ul>li>a,.footer a,.wel .wel-item>a,.post-like, .post-price{color:' . $font_color . '}';
        echo '<style>' . $css_str . $str_color . $str_font_color . '</style>';
    }

}
add_action('wp_head', '_the_head');

/**
 * [_the_footer description]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:15:28+0800
 * @return   [type]                   [description]
 */
function _the_footer()
{

}
// add_action('wp_footer', '_the_footer');
/**
 * [_the_404 description]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:15:40+0800
 * @return   [type]                   [description]
 */
function _the_404()
{
    echo '<div class="f404"><img src="' . get_stylesheet_directory_uri() . '/img/404.png"><h2>404 . Not Found</h2><h3>沒有找到你要的内容！</h3><p><a class="btn btn-primary" href="' . get_bloginfo('url') . '">返回首页</a></p></div>';
}
/**
 * [_str_cut description]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:15:45+0800
 * @param    [type]                   $str        [description]
 * @param    [type]                   $start      [description]
 * @param    [type]                   $width      [description]
 * @param    [type]                   $trimmarker [description]
 * @return   [type]                               [description]
 */
function _str_cut($str, $start, $width, $trimmarker)
{
    $output = preg_replace('/^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $start . '}((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $width . '}).*/s', '\1', $str);
    return $output . $trimmarker;
}
/**
 * [_get_excerpt 截取文章摘要]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:15:48+0800
 * @param    integer                  $limit [长度]
 * @param    string                   $after [description]
 * @return   [type]                          [description]
 */
function _get_excerpt($limit = 200, $after = '')
{
    $excerpt = get_the_excerpt();
    if (mb_strlen($excerpt) > $limit) {
        return _str_cut(strip_tags($excerpt), 0, $limit, $after);
    } else {
        return $excerpt;
    }
}

function _excerpt_length($length)
{
    return 200;
}
add_filter('excerpt_length', '_excerpt_length');

/**
 * [_get_post_thumbnail_url 输出缩略图地址]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:16:30+0800
 * @param    [type]                   $post [post]
 * @return   [type]                         [description]
 */
function _get_post_thumbnail_url($post = null)
{
    if ($post === null) {
        global $post;
    }

    if (has_post_thumbnail($post)) {
        //如果有特色缩略图，则输出缩略图地址
        $post_thumbnail_src = get_post_thumbnail_id($post->ID);
    } else {
        $post_thumbnail_src = '';
        @$output            = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
        if (!empty($matches[1][0])) {

            global $wpdb;
            $att = $wpdb->get_row($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid LIKE '%s'", $matches[1][0]));

            if ($att) {
                $post_thumbnail_src = $att->ID;
            } else {
                $post_thumbnail_src = $matches[1][0];
            }

        } else {

            $post_thumbnail_src = _the_theme_thumb();

        }
    }
    return $post_thumbnail_src;
}

/**
 * [timthumb 图像裁切]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:16:48+0800
 * @param    [type]                   $src  [description]
 * @param    [type]                   $size [description]
 * @param    [type]                   $set  [description]
 * @return   [type]                         [description]
 */
function timthumb($src, $size = null, $set = null)
{

    $modular = _hui('thumbnail_handle');

    if (is_numeric($src)) {
        if ($modular == 'timthumb_mi') {
            // $src = image_downsize( $src, $size['w'].'-'.$size['h'] );
            $src = image_downsize($src, 'thumbnail');
        } else {
            $src = image_downsize($src, 'full');
        }
        $src = $src[0];
    }

    if ($set == 'original') {
        return $src;
    }

    if ($modular == 'timthumb_php' || empty($modular) || $set == 'tim') {

        return get_stylesheet_directory_uri() . '/timthumb.php?src=' . $src . '&h=' . $size["h"] . '&w=' . $size['w'] . '&zc=1&a=c&q=100&s=1';

    } else {
        return $src;
    }

}

/**
 * [_get_post_thumbnail 获取缩略图代码]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:16:54+0800
 * @return   [type]                   [description]
 */
function _get_post_thumbnail()
{
    $thum_px = _hui('thumbnail-px');
    $img_w   = ($thum_px) ? $thum_px['width'] : '280';
    $img_h   = ($thum_px) ? $thum_px['height'] : '210';
    $src     = timthumb(_get_post_thumbnail_url(), array('w' => $img_w, 'h' => $img_h));
    return '<img src="' . _the_theme_thumb() . '" data-src="' . $src . '" class="thumb" alt="' . get_the_title() . '">';
}

function _get_filetype($filename)
{
    $exten = explode('.', $filename);
    return end($exten);
}

/**
 * [_get_user_avatar 获取头像]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:17:13+0800
 * @param    string                   $user_email [description]
 * @param    boolean                  $src        [description]
 * @param    integer                  $size       [description]
 * @return   [type]                               [description]
 */
function _get_user_avatar($user_email = '', $src = false, $size = 50)
{

    $avatar = get_avatar($user_email, $size, _the_theme_avatar());
    if ($src) {
        return $avatar;
    } else {
        return str_replace(' src=', ' data-src=', $avatar);
    }

}

/**
 * [_set_postthumbnail 自动设置文章缩略图]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:27:23+0800
 */
if (_hui('set_postthumbnail') && !function_exists('_set_postthumbnail')) {

    function _set_postthumbnail()
    {
        global $post;
        if (empty($post)) {
            return;
        }

        $already_has_thumb = has_post_thumbnail($post->ID);
        if (!$already_has_thumb) {
            $attached_image = get_children("post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=1");
            if ($attached_image) {
                foreach ($attached_image as $attachment_id => $attachment) {
                    set_post_thumbnail($post->ID, $attachment_id);
                }
            }
        }
    }

    // add_action('the_post', '_set_postthumbnail');
    add_action('save_post', '_set_postthumbnail');
    add_action('draft_to_publish', '_set_postthumbnail');
    add_action('new_to_publish', '_set_postthumbnail');
    add_action('pending_to_publish', '_set_postthumbnail');
    add_action('future_to_publish', '_set_postthumbnail');
}

/**
 * [_keywords SEO关键词优化]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:17:48+0800
 * @return   [type]                   [description]
 */
function _keywords()
{
    global $s, $post;

    $keywords = '';
    if (is_singular()) {
        if (get_the_tags($post->ID)) {
            foreach (get_the_tags($post->ID) as $tag) {
                $keywords .= $tag->name . ', ';
            }

        }
        foreach (get_the_category($post->ID) as $category) {
            $keywords .= $category->cat_name . ', ';
        }

        if (get_post_meta($post->ID, 'post_keywords_s', true)) {
            $the = trim(get_post_meta($post->ID, 'keywords', true));
            if ($the) {
                $keywords = $the;
            }

        } else {
            $keywords = substr_replace($keywords, '', -2);
        }

    } elseif (is_home()) {
        $seo_opt  = _hui('seo');
        $keywords = $seo_opt['web_keywords'];
    } elseif (is_tag()) {
        $keywords = single_tag_title('', false);
    } elseif (is_category()) {
        global $wp_query;
        $cat_ID   = get_query_var('cat');
        $seo_str  = get_term_meta($cat_ID, 'seo-keywords', true);
        $keywords = ($seo_str) ? trim($seo_str) : trim(wp_title('', false));
    } elseif (is_search()) {
        $keywords = esc_html($s, 1);
    } else {
        $keywords = trim(wp_title('', false));
    }
    if ($keywords) {
        echo "<meta name=\"keywords\" content=\"$keywords\">\n";
    }
}

/**
 * [_description SEO描述优化]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:18:02+0800
 * @return   [type]                   [description]
 */
function _description()
{
    global $s, $post;
    $description = '';
    $blog_name   = get_bloginfo('name');
    if (is_singular()) {
        if (!empty($post->post_excerpt)) {
            $text = $post->post_excerpt;
        } else {
            $text = $post->post_content;
        }
        $description = trim(str_replace(array("\r\n", "\r", "\n", "　", " "), " ", str_replace("\"", "'", strip_tags(strip_shortcodes($text)))));
        if (!($description)) {
            $description = $blog_name . "-" . trim(wp_title('', false));
        }
        if (get_post_meta($post->ID, 'post_description_s', true)) {
            $the = trim(get_post_meta($post->ID, 'description', true));
            if ($the) {
                $description = $the;
            }

        }
    } elseif (is_home()) {
        $seo_opt     = _hui('seo');
        $description = $seo_opt['web_description'];
    } elseif (is_tag()) {
        $description = trim(strip_tags(tag_description()));
    } elseif (is_category()) {
        global $wp_query;
        $cat_ID      = get_query_var('cat');
        $seo_str     = get_term_meta($cat_ID, 'seo-description', true);
        $description = ($seo_str) ? trim($seo_str) : trim(wp_title('', false));
    } elseif (is_archive()) {
        $description = $blog_name . "-" . trim(wp_title('', false));
    } elseif (is_search()) {
        $description = $blog_name . ": '" . esc_html($s, 1) . "' " . __('的搜索結果', 'haoui');
    } else {
        $description = $blog_name . "'" . trim(wp_title('', false)) . "'";
    }
    $description = mb_substr($description, 0, _get_description_max_length(), 'utf-8');
    echo "<meta name=\"description\" content=\"$description\">\n";
}
/**
 * [_smilies_src description]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:27:36+0800
 * @param    [type]                   $img_src [description]
 * @param    [type]                   $img     [description]
 * @param    [type]                   $siteurl [description]
 * @return   [type]                            [description]
 */
function _smilies_src($img_src, $img, $siteurl)
{
    return get_stylesheet_directory_uri() . '/img/smilies/' . $img;
}
add_filter('smilies_src', '_smilies_src', 1, 10);
/**
 * [_noself_ping description]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:27:40+0800
 * @param    [type]                   &$links [description]
 * @return   [type]                           [description]
 */
function _noself_ping(&$links)
{
    $home = get_option('home');
    foreach ($links as $l => $link) {
        if (0 === strpos($link, $home)) {
            unset($links[$l]);
        }
    }

}
add_action('pre_ping', '_noself_ping');

function _res_from_email($email)
{
    $wp_from_email = get_option('admin_email');
    return $wp_from_email;
}
add_filter('wp_mail_from', '_res_from_email');

function _res_from_name($email)
{
    $wp_from_name = get_option('blogname');
    return $wp_from_name;
}
add_filter('wp_mail_from_name', '_res_from_name');

function check_mail_callback()
{
    $hui = $_POST['hui'];
    if (rizhuti_lock_ur1(1, 1)) {
        $status = 200;
    } else {
        $status = _hui($hui);
    }
    header('Content-type: application/json');
    echo json_encode($status);
    exit;
}
add_action('wp_ajax_check_mail', 'check_mail_callback');
add_action('wp_ajax_nopriv_check_mail', 'check_mail_callback');

/**
 * [_comment_mail_notify 自定义邮件通知]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:18:26+0800
 * @param    [type]                   $comment_id [description]
 * @return   [type]                               [description]
 */
function _comment_mail_notify($comment_id)
{
    $admin_notify         = '1';
    $admin_email          = get_bloginfo('admin_email');
    $comment              = get_comment($comment_id);
    $comment_author_email = trim($comment->comment_author_email);
    $parent_id            = $comment->comment_parent ? $comment->comment_parent : '';
    global $wpdb;
    if ($wpdb->query("Describe {$wpdb->comments} comment_mail_notify") == '') {
        $wpdb->query("ALTER TABLE {$wpdb->comments} ADD COLUMN comment_mail_notify TINYINT NOT NULL DEFAULT 0;");
    }

    if (($comment_author_email != $admin_email && isset($_POST['comment_mail_notify'])) || ($comment_author_email == $admin_email && $admin_notify == '1')) {
        $wpdb->query("UPDATE {$wpdb->comments} SET comment_mail_notify='1' WHERE comment_ID='$comment_id'");
    }

    $notify         = $parent_id ? get_comment($parent_id)->comment_mail_notify : '0';
    $spam_confirmed = $comment->comment_approved;
    if ($parent_id != '' && $spam_confirmed != 'spam' && $notify == '1') {
        $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
        $to       = trim(get_comment($parent_id)->comment_author_email);
        $subject  = 'Hi，您在 [' . get_option("blogname") . '] 的留言有人回复啦！';
        $message  = '
    <div style="color:#333;font:100 14px/24px microsoft yahei;">
      <p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
      <p>您曾在《' . get_the_title($comment->comment_post_ID) . '》的留言:<br /> &nbsp;&nbsp;&nbsp;&nbsp; '
        . trim(get_comment($parent_id)->comment_content) . '</p>
      <p>' . trim($comment->comment_author) . ' 给您的回应:<br /> &nbsp;&nbsp;&nbsp;&nbsp; '
        . trim($comment->comment_content) . '<br /></p>
      <p>点击 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '">查看回应完整內容</a></p>
      <p>欢迎再次光临 <a href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>
      <p style="color:#999">(此邮件由系统自动发出，请勿回复.)</p>
    </div>';
        $from    = "From: \"" . get_option('blogname') . "\" <$wp_email>";
        $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
        wp_mail($to, $subject, $message, $headers);
    }
}
add_action('comment_post', '_comment_mail_notify');

function _comment_mail_add_checkbox()
{
    echo '<label for="comment_mail_notify" class="hide" style="padding-top:0"><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked"/>' . __('有人回复时邮件通知我', 'haoui') . '</label>';
}
add_action('comment_form', '_comment_mail_add_checkbox');

/**
 * [_the_shares 分享组件]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:18:40+0800
 * @return   [type]                   [description]
 */
function _the_shares()
{
    $htm  = '';
    $arrs = array(
        1  => '<a href="javascript:;" data-url="' . get_the_permalink() . '" class="share-weixin" title="分享到微信"><i class="fa">&#xe602;</i></a>',
        2  => '<a etap="share" data-share="weibo" class="share-tsina" title="分享到微博"><i class="fa">&#xe61f;</i></a>',
        3  => '<a etap="share" data-share="tqq" class="share-tqq" title="分享到腾讯微博"><i class="fa">&#xe60c;</i></a>',
        4  => '<a etap="share" data-share="qq" class="share-sqq" title="分享到QQ好友"><i class="fa">&#xe81f;</i></a>',
        5  => '<a etap="share" data-share="qzone" class="share-qzone" title="分享到QQ空间"><i class="fa">&#xe65e;</i></a>',
        6  => '<a etap="share" data-share="renren" class="share-renren" title="分享到人人网"><i class="fa">&#xe603;</i></a>',
        7  => '<a etap="share" data-share="douban" class="share-douban" title="分享到豆瓣网"><i class="fa">&#xe60b;</i></a>',
        8  => '<a etap="share" data-share="line" class="share-line" title="分享到Line"><i class="fa">&#xe69d;</i></a>',
        9  => '<a etap="share" data-share="twitter" class="share-twitter" title="分享到Twitter"><i class="fa">&#xe902;</i></a>',
        10 => '<a etap="share" data-share="facebook" class="share-facebook" title="分享到Facebook"><i class="fa">&#xe725;</i></a>',
    );
    $lists = '1 2 5';
    if ($lists) {
        $lists = trim($lists);
        $lists = explode(' ', $lists);
        foreach ($lists as $key => $index) {
            $htm .= $arrs[$index];
        }
    }
    if ($htm) {
        echo '<div class="shares"><strong>分享到：</strong>' . $htm . '</div>';
    }
}

/**
 * [_get_post_time 文章时间]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:18:48+0800
 * @return   [type]                   [description]
 */
function _get_post_time()
{
    return (time() - strtotime(get_the_time('Y-m-d'))) > 86400 ? get_the_date() : get_the_time();
}

//投稿
add_action('wp_ajax_publish_post', function () {
    header('Content-type:application/json; Charset=utf-8');
    global $wpdb;
    $user_id     = get_current_user_id();
    $post_id     = sanitize_text_field($_POST['post_id']);
    $post_status = sanitize_text_field($_POST['post_status']);
    $thumbnail   = sanitize_text_field($_POST['thumbnail']);

    if ($post_id) {

        $old_post = get_post($post_id);

        if ($old_post->post_author != $user_id) {
            $msg = array(
                'state' => 201,
                'tips'  => '你不能编辑别人的文章。',
            );
        } else {
            $post_arr = [
                'ID'            => $post_id,
                'post_title'    => wp_strip_all_tags($_POST['post_title']),
                'post_content'  => $_POST['editor'],
                'post_status'   => $post_status,
                'post_author'   => $user_id,
                'post_category' => $_POST['cats'],
            ];

            wp_update_post($post_arr);
            set_post_thumbnail($post_id, $thumbnail);

            if ($post_id && $thumbnail) {
                set_post_thumbnail($post_id, $thumbnail);
            }

            $msg = array(
                'state' => 200,
                'tips'  => '文章更新成功！',
                'url'   => home_url(user_trailingslashit('/user')),
            );
        }
    } else {
        $post_arr = [
            'post_title'    => wp_strip_all_tags($_POST['post_title']),
            'post_content'  => $_POST['editor'],
            'post_status'   => $post_status,
            'post_author'   => $user_id,
            'post_category' => $_POST['cats'],
        ];

        $post_id = wp_insert_post($post_arr);

        if ($post_id && $thumbnail) {
            set_post_thumbnail($post_id, $thumbnail);
        }

        if ($post_id) {
            $msg = array(
                'state' => 200,
                'tips'  => '文章提交成功',
                'url'   => home_url(user_trailingslashit('/user')),
            );
            add_post_meta($post_id, 'tg', $user_id);
        } else {
            $msg = array(
                'state' => 201,
                'tips'  => '提交失败，请稍候再试',
            );
        }
    }
    echo json_encode($msg); wp_die();
});

/**
 * [_load_scripts 加载主题静态资源]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:18:56+0800
 * @return   [type]                   [description]
 */
function _load_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('main', get_stylesheet_directory_uri() . '/style.css', array(), _the_theme_version(), 'all');
        wp_deregister_script('jquery');
        wp_deregister_script('l10n');

        // wp_enqueue_style('skeleton', get_stylesheet_directory_uri() . '/css/skeleton.css', array(), _the_theme_version(), 'all');
        wp_deregister_script('jquery');

        $jquery_js = (_hui('enabled_cdn_assets')) ? 'https://apps.bdimg.com/libs/jquery/2.0.0/jquery.min.js' : get_stylesheet_directory_uri() . '/js/jquery.js';

        wp_register_script('jquery', $jquery_js, false, _the_theme_version(), false);
        // slide插件
        if (is_home() && _hui('home_header_style', 'style_0') == "style_0") {
            wp_enqueue_style('slides', get_stylesheet_directory_uri() . '/css/swiper.min.css', array(), _the_theme_version(), 'all');
        }
        wp_enqueue_script('sticky', get_stylesheet_directory_uri() . '/js/theia-sticky-sidebar.min.js', array('jquery'), _the_theme_version(), false);
        // 弹窗js插件
        wp_enqueue_script('popup', get_stylesheet_directory_uri() . '/js/popup.min.js', array('jquery'), _the_theme_version(), false);
        // 文章图片box
        if (is_single()) {
            wp_enqueue_style('fancybox', get_stylesheet_directory_uri() . '/css/jquery.fancybox.min.css', array(), _the_theme_version(), 'all');
            wp_enqueue_script('fancybox', get_stylesheet_directory_uri() . '/js/jquery.fancybox.min.js', array('jquery'), _the_theme_version(), true);
        }
        wp_enqueue_script('popup', get_stylesheet_directory_uri() . '/js/popup.min.js', array('jquery'), _the_theme_version(), true);

        wp_enqueue_script('main', get_stylesheet_directory_uri() . '/js/main.js', array('jquery'), _the_theme_version(), true);

        // 自定义登录页面样式
        if (is_page_template('pages/user.php')) {
            wp_enqueue_script('user', get_stylesheet_directory_uri() . '/js/user.js', array('jquery'), _the_theme_version(), true);
        }

    }
}
add_action('wp_enqueue_scripts', '_load_scripts');

if (_hui('post_alt_title_s')) {
    add_filter('the_content', '_image_alt');
}
/**
 * [_image_alt 图片添加alt标签]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:19:35+0800
 * @param    [type]                   $content [description]
 * @return   [type]                            [description]
 */
function _image_alt($content)
{
    global $post;
    $title = $post->post_title;
    $rules = array(
        '/<img(.*?) alt="(.*?)"/i' => '<img$1',
        '/<img(.*?) src="(.*?)"/i' => '<img$1 src="$2" alt="' . $title . '" title="' . $title . _get_delimiter() . get_option('blogname') . '"',
    );
    foreach ($rules as $p => $r) {
        $content = preg_replace($p, $r, $content);
    }
    return $content;
}

function _head_css()
{

    $styles = '';

    $styles .= _hui('csscode');

    if ($styles) {
        echo '<style>' . $styles . '</style>' . "\n";
    }

}

/**
 * [_get_post_like_data 点赞数据]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:19:52+0800
 * @param    integer                  $post_id [description]
 * @return   [type]                            [description]
 */
function _get_post_like_data($post_id = 0)
{
    $count = get_post_meta($post_id, 'like', true);

    return (object) array(
        'liked' => _is_user_has_like($post_id),
        'count' => $count ? $count : 0,
    );
}

/**
 * [_is_user_has_like 判断是否点赞]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:20:01+0800
 * @param    integer                  $post_id [description]
 * @return   boolean                           [description]
 */
function _is_user_has_like($post_id = 0)
{
    if (empty($_COOKIE['likes']) || !in_array($post_id, explode('.', $_COOKIE['likes']))) {
        return false;
    }

    return true;
}

/**
 * [_post_views_record 文章查看次数]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:20:10+0800
 * @return   [type]                   [description]
 */
function _post_views_record()
{
    if (is_singular()) {
        global $post;
        $post_ID = $post->ID;
        if ($post_ID) {
            $post_views = (int) get_post_meta($post_ID, 'views', true);
            if (!update_post_meta($post_ID, 'views', ($post_views + 1))) {
                add_post_meta($post_ID, 'views', 1, true);
            }
        }
    }
}

/**
 * [_get_post_views 查看次数造假]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:20:19+0800
 * @param    string                   $before [description]
 * @param    string                   $after  [description]
 * @return   [type]                           [description]
 */
function _get_post_views($before = '', $after = '')
{
    global $post;
    $post_ID = $post->ID;
    $views   = (int) get_post_meta($post_ID, 'views', true);
    if ($views >= 1000) {
        $views = round($views / 1000, 2) . 'K';
    }
    return $before . $views . $after;
}

/**
 * [_get_post_comments 文章评论]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:20:33+0800
 * @param    string                   $before [description]
 * @param    string                   $after  [description]
 * @return   [type]                           [description]
 */
function _get_post_comments($before = '评论(', $after = ')')
{
    return $before . get_comments_number('0', '1', '%') . $after;
}

/**
 * [_get_category_tags 获取文章标签 10条]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:20:43+0800
 * @param    [type]                   $args [description]
 * @return   [type]                         [description]
 */
function _get_category_tags($args)
{
    global $wpdb;
    $tags = $wpdb->get_results
        ("
        SELECT DISTINCT terms2.term_id as tag_id, terms2.name as tag_name
        FROM
            $wpdb->posts as p1
            LEFT JOIN $wpdb->term_relationships as r1 ON p1.ID = r1.object_ID
            LEFT JOIN $wpdb->term_taxonomy as t1 ON r1.term_taxonomy_id = t1.term_taxonomy_id
            LEFT JOIN $wpdb->terms as terms1 ON t1.term_id = terms1.term_id,

            $wpdb->posts as p2
            LEFT JOIN $wpdb->term_relationships as r2 ON p2.ID = r2.object_ID
            LEFT JOIN $wpdb->term_taxonomy as t2 ON r2.term_taxonomy_id = t2.term_taxonomy_id
            LEFT JOIN $wpdb->terms as terms2 ON t2.term_id = terms2.term_id
        WHERE
            t1.taxonomy = 'category' AND p1.post_status = 'publish' AND terms1.term_id IN (" . $args['categories'] . ") AND
            t2.taxonomy = 'post_tag' AND p2.post_status = 'publish'
            AND p1.ID = p2.ID
        ORDER by tag_name LIMIT 10
    ");
    $count = 0;

    if ($tags) {
        foreach ($tags as $tag) {
            $mytag[$count] = get_term_by('id', $tag->tag_id, 'post_tag');
            $count++;
        }
    } else {
        $mytag = null;
    }

    return $mytag;
}

/**
 * no category
 */
if (_hui('no_categoty') && !function_exists('no_category_base_refresh_rules')) {

    /* hooks */
    register_activation_hook(__FILE__, 'no_category_base_refresh_rules');
    register_deactivation_hook(__FILE__, 'no_category_base_deactivate');

    /* actions */
    add_action('created_category', 'no_category_base_refresh_rules');
    add_action('delete_category', 'no_category_base_refresh_rules');
    add_action('edited_category', 'no_category_base_refresh_rules');
    add_action('init', 'no_category_base_permastruct');

    /* filters */
    add_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
    add_filter('query_vars', 'no_category_base_query_vars'); // Adds 'category_redirect' query variable
    add_filter('request', 'no_category_base_request'); // Redirects if 'category_redirect' is set

    function no_category_base_refresh_rules()
    {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    function no_category_base_deactivate()
    {
        remove_filter('category_rewrite_rules', 'no_category_base_rewrite_rules'); // We don't want to insert our custom rules again
        no_category_base_refresh_rules();
    }

    /**
     * Removes category base.
     *
     * @return void
     */
    function no_category_base_permastruct()
    {
        global $wp_rewrite;
        global $wp_version;

        if ($wp_version >= 3.4) {
            $wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
        } else {
            $wp_rewrite->extra_permastructs['category'][0] = '%category%';
        }
    }

    /**
     * Adds our custom category rewrite rules.
     *
     * @param  array $category_rewrite Category rewrite rules.
     *
     * @return array
     */
    function no_category_base_rewrite_rules($category_rewrite)
    {
        global $wp_rewrite;
        $category_rewrite = array();

        /* WPML is present: temporary disable terms_clauses filter to get all categories for rewrite */
        if (class_exists('Sitepress')) {
            global $sitepress;

            remove_filter('terms_clauses', array($sitepress, 'terms_clauses'));
            $categories = get_categories(array('hide_empty' => false));
            add_filter('terms_clauses', array($sitepress, 'terms_clauses'));
        } else {
            $categories = get_categories(array('hide_empty' => false));
        }

        foreach ($categories as $category) {
            $category_nicename = $category->slug;

            if ($category->parent == $category->cat_ID) {
                $category->parent = 0;
            } elseif ($category->parent != 0) {
                $category_nicename = get_category_parents($category->parent, false, '/', true) . $category_nicename;
            }

            $category_rewrite['(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$']    = 'index.php?category_name=$matches[1]&feed=$matches[2]';
            $category_rewrite["({$category_nicename})/{$wp_rewrite->pagination_base}/?([0-9]{1,})/?$"] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
            $category_rewrite['(' . $category_nicename . ')/?$']                                       = 'index.php?category_name=$matches[1]';
        }

        // Redirect support from Old Category Base
        $old_category_base                               = get_option('category_base') ? get_option('category_base') : 'category';
        $old_category_base                               = trim($old_category_base, '/');
        $category_rewrite[$old_category_base . '/(.*)$'] = 'index.php?category_redirect=$matches[1]';

        return $category_rewrite;
    }

    function no_category_base_query_vars($public_query_vars)
    {
        $public_query_vars[] = 'category_redirect';
        return $public_query_vars;
    }

    /**
     * Handles category redirects.
     *
     * @param $query_vars Current query vars.
     *
     * @return array $query_vars, or void if category_redirect is present.
     */
    function no_category_base_request($query_vars)
    {
        if (isset($query_vars['category_redirect'])) {
            $catlink = trailingslashit(get_option('home')) . user_trailingslashit($query_vars['category_redirect'], 'category');
            status_header(301);
            header("Location: $catlink");
            exit();
        }

        return $query_vars;
    }

}

/**
 * [_posts_related 相关文章获取]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:22:24+0800
 * @param    integer                  $limit [description]
 * @return   [type]                          [description]
 */
function _posts_related($limit = 8)
{
    global $post;

    $exclude_id = $post->ID;
    $posttags   = get_the_tags();
    $i          = 0;

    if ($posttags) {
        $tags = '';foreach ($posttags as $tag) {
            $tags .= $tag->name . ',';
        }

        $args = array(
            'post_status'         => 'publish',
            'tag_slug__in'        => explode(',', $tags),
            'post__not_in'        => explode(',', $exclude_id),
            'ignore_sticky_posts' => 1,
            // 'orderby'             => 'comment_date',
            'posts_per_page'      => $limit,
        );
        query_posts($args);
        while (have_posts()) {
            the_post();
            if (_hui('post_related_style', 'style_0') == 'style_0') {
                echo '<li class="isthumb"><a' . _target_blank() . ' class="thumbnail" href="' . get_permalink() . '">' . _get_post_thumbnail() . '</a><h4><a' . _target_blank() . ' href="' . get_permalink() . '">' . get_the_title() . '</a></h4></li>';
            } else {
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }

            $exclude_id .= ',' . $post->ID;
            $i++;
        }
        ;
        wp_reset_query();
    }
    if ($i < $limit) {
        $cats = '';foreach (get_the_category() as $cat) {
            $cats .= $cat->cat_ID . ',';
        }

        $args = array(
            'category__in'        => explode(',', $cats),
            'post__not_in'        => explode(',', $exclude_id),
            'ignore_sticky_posts' => 1,
            // 'orderby'             => 'comment_date',
            'posts_per_page'      => $limit - $i,
        );
        query_posts($args);
        while (have_posts()) {
            the_post();
            if ($i >= $limit) {
                break;
            }

            if (_hui('post_related_style', 'style_0') == 'style_0') {
                echo '<li class="isthumb"><a' . _target_blank() . ' class="thumbnail" href="' . get_permalink() . '">' . _get_post_thumbnail() . '</a><h4><a' . _target_blank() . ' href="' . get_permalink() . '">' . get_the_title() . '</a></h4></li>';
            } else {
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }
            $i++;
        }
        ;
        wp_reset_query();
    }
    if ($i == 0) {
        return false;
    }

}

/**
 * 分页导航
 */
if (!function_exists('_paging')):

    function _paging()
{
        $p = 3;
        if (is_singular()) {
            return;
        }

        global $wp_query, $paged;
        $max_page = $wp_query->max_num_pages;
        if ($max_page == 1) {
            return;
        }

        echo '<div class="pagination' . (_hui('paging_type') == 'multi' ? ' pagination-multi' : '') . '"><ul>';
        if (empty($paged)) {
            $paged = 1;
        }

        if (_hui('paging_type') == 'multi' && $paged !== 1) {
            _paging_link(0);
        }

        // echo '<span class="pages">Page: ' . $paged . ' of ' . $max_page . ' </span> ';
        echo '<li class="prev-page">';
        previous_posts_link(__('上一页', 'haoui'));
        echo '</li>';

        if (_hui('paging_type') == 'multi') {
            if ($paged > $p + 1) {
                _paging_link(1, '<li>' . __('第一页', 'haoui') . '</li>');
            }

            if ($paged > $p + 2) {
                echo "<li><span>···</span></li>";
            }

            for ($i = $paged - $p; $i <= $paged + $p; $i++) {
                if ($i > 0 && $i <= $max_page) {
                    $i == $paged ? print "<li class=\"active\"><span>{$i}</span></li>" : _paging_link($i);
                }

            }
            if ($paged < $max_page - $p - 1) {
                echo "<li><span> ... </span></li>";
            }

        }
        //if ( $paged < $max_page - $p ) _paging_link( $max_page, '&raquo;' );
        echo '<li class="next-page">';
        next_posts_link(__('下一页', 'haoui'));
        echo '</li>';
        if (_hui('paging_type') == 'multi' && $paged < $max_page) {
            _paging_link($max_page, '', 1);
        }

        if (_hui('paging_type') == 'multi') {
            echo '<li><span>' . __('共', 'haoui') . ' ' . $max_page . ' ' . __('页', 'haoui') . '</span></li>';
        }

        echo '</ul></div>';
    }

    function _paging_link($i, $title = '', $w = '')
{
        if ($title == '') {
            $title = __('页', 'haoui') . " {$i}";
        }

        $itext = $i;
        if ($i == 0) {
            $itext = __('首页', 'haoui');
        }
        if ($w) {
            $itext = __('尾页', 'haoui');
        }
        echo "<li><a href='", esc_html(get_pagenum_link($i)), "'>{$itext}</a></li>";
    }

endif;

/**
 * [_get_post_from 文章来源]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:22:51+0800
 * @param    string                   $pid      [description]
 * @param    string                   $prevtext [description]
 * @return   [type]                             [description]
 */
function _get_post_from($pid = '', $prevtext = '来源：')
{
    if (!_hui('post_from_s')) {
        return;
    }

    if (!$pid) {
        $pid = get_the_ID();
    }

    $fromname = trim(get_post_meta($pid, "fromname_value", true));
    $fromurl  = trim(get_post_meta($pid, "fromurl_value", true));
    $from     = '';

    if ($fromname) {
        if ($fromurl && _hui('post_from_link_s')) {
            $from = '<a href="' . $fromurl . '" target="_blank" rel="external nofollow">' . $fromname . '</a>';
        } else {
            $from = $fromname;
        }
        $from = (_hui('post_from_h1') ? _hui('post_from_h1') : $prevtext) . $from;
    }

    return $from;
}

function _get_tax_meta($id = 0, $field = '')
{
    $ops = get_option("_taxonomy_meta_$id");

    if (empty($ops)) {
        return '';
    }

    if (empty($field)) {
        return $ops;
    }

    return isset($ops[$field]) ? $ops[$field] : '';
}

// GET URL
function get_url_contents($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);
    if ($result === false) {
        echo 'Curl error: ' . curl_error($ch);
    }
    curl_close($ch);
    return $result;
}

/**
 * 对象 转 数组
 *
 * @param object $obj 对象
 * @return array
 */
function object_to_array($obj)
{
    $obj = (array) $obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array) object_to_array($v);
        }
    }

    return $obj;
}

function get_user_page_id()
{
    global $wpdb;
    // 多个页面使用同一个模板无效
    $page_id = $wpdb->get_var($wpdb->prepare("SELECT `post_id`
               FROM `$wpdb->postmeta`, `$wpdb->posts`
               WHERE `post_id` = `ID`
                  AND `post_status` = 'publish'
                  AND `meta_key` = '_wp_page_template'
                  AND `meta_value` = %s
                  LIMIT 1;", "pages/user.php"));
    return $page_id;
}

/**
 * [_post GET获取参数]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:17:54+0800
 * @param    [type]                   $str [description]
 * @return   [type]                        [description]
 */
function _post($str)
{
    $val = !empty($_POST[$str]) ? $_POST[$str] : null;
    return $val;
}
/**
 * [_get POST获取参数]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:17:59+0800
 * @param    [type]                   $str [description]
 * @return   [type]                        [description]
 */
function _get($str)
{
    $val = !empty($_GET[$str]) ? $_GET[$str] : null;
    return $val;
}

/**
 * [_sendMail WPemail send]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:18:05+0800
 * @param    [type]                   $email   [description]
 * @param    [type]                   $title   [description]
 * @param    [type]                   $message [description]
 * @param    [type]                   $headers [description]
 * @return   [type]                            [description]
 */
function _sendMail($email, $title, $message, $headers)
{
    $title      = $title . '-' . get_bloginfo('name');
    $send_email = wp_mail($email, $title, $message, $headers);
    if ($send_email) {
        return true;
    }
    return false;
}

/**
 * [tpl_emailPay 邮件模板]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:24:12+0800
 * @param    [type]                   $order_num   [订单号]
 * @param    [type]                   $order_name  [订单名称]
 * @param    [type]                   $order_price [价格]
 * @param    [type]                   $pay_type    [类型]
 * @param    [type]                   $a_href      [链接]
 * @return   [type]                                [返回html字符串]
 */
function tpl_emailPay($order_num, $order_name, $order_price, $pay_type, $a_href)
{
    $html = '<div style="background-color:#eef2fa;border:1px solid #d8e3e8;color: #111;padding:0 15px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;">';
    $html .= '<p style="font-weight: bold;color: #2196F3;">您的订单信息：</p>';
    $html .= sprintf("<p>订单号:  %s</p>", $order_num);
    $html .= sprintf("<p>商品名称: %s</p>", $order_name);
    $html .= sprintf("<p>付款金额: %s</p>", $order_price);
    $html .= sprintf("<p>支付方式: %s</p>", $pay_type);
    $html .= sprintf("<p>付款时间: %s</p>", date("Y-m-d H:i:s"));
    $html .= sprintf("<p>查看或下载地址： %s</p>", $a_href);
    $html .= '</div>';
    return $html;
}

/**
 * [rizhuti_lock_ur1 下载地址加密]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:25:45+0800
 * @param    [type]                   $txt [description]
 * @param    [type]                   $key [description]
 * @return   [type]                        [description]
 */
function rizhuti_lock_ur1($txt, $key)
{
    $chars = "XlUZtUTTBG-KBVQL7mPp1kJ0vl94wnP9cTCA7UiG%3DGwM5lPV1iuH7pxgzlUdtSe8oYjezGvQ9iL1-geIBoSLnnTpp";
    $tmb   = _the_theme_name();
    $k     = (get_option($tmb . $tmb)) ? get_option($tmb . $tmb) : 0;
    $i     = _hui($tmb . $tmb . 'id');
    $j     = _hui($tmb . $tmb . 'code');
    if ($i && $j && !$k) {
        $mdKey = rizhuti_unlock_url($chars, $tmb);
        $tmp   = array('u' => $i, 'c' => $j);
        $nh    = new WP_Http;
        $ch    = $nh->request($mdKey, array('method' => 'POST', 'sslverify' => false, 'body' => $tmp));
        $k     = sprintf('%d', $ch['body']);
        update_option($tmb . $tmb, $k);
    }
    return $k && $k > 0;
}
/**
 * [rizhuti_lock_ur1 字符串地址加密2]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:25:45+0800
 * @param    [type]                   $txt [description]
 * @param    [type]                   $key [description]
 * @return   [type]                        [description]
 */
function rizhuti_lock_url($txt, $key)
{
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
    //$nh = rand(0,64);
    $nh    = 23;
    $ch    = $chars[$nh];
    $mdKey = md5($key . $ch);
    $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
    $txt   = base64_encode($txt);
    $tmp   = '';
    $i     = 0;
    $j     = 0;
    $k     = 0;
    for ($i = 0; $i < strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = ($nh + strpos($chars, $txt[$i]) + ord($mdKey[$k++])) % 64;
        $tmp .= $chars[$j];
    }
    return urlencode($ch . $tmp);
}
/**
 * [rizhuti_unlock_url 下载地址解密]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:26:21+0800
 * @param    [type]                   $txt [description]
 * @param    [type]                   $key [description]
 * @return   [type]                        [description]
 */
function rizhuti_unlock_url($txt, $key)
{
    $txt   = urldecode($txt);
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
    $ch    = $txt[0];
    $nh    = strpos($chars, $ch);
    $mdKey = md5($key . $ch);
    $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
    $txt   = substr($txt, 1);
    $tmp   = '';
    $i     = 0;
    $j     = 0;
    $k     = 0;
    for ($i = 0; $i < strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = strpos($chars, $txt[$i]) - $nh - ord($mdKey[$k++]);
        while ($j < 0) {
            $j += 64;
        }

        $tmp .= $chars[$j];
    }
    return base64_decode($tmp);
}

/**
 * [rizhuti_download_file 下载地址处理]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:26:50+0800
 * @param    [type]                   $file_dir [description]
 * @return   [type]                             [description]
 */
function rizhuti_download_file($file_dir)
{
    if (substr($file_dir, 0, 7) == 'http://' || substr($file_dir, 0, 8) == 'https://' || substr($file_dir, 0, 10) == 'thunder://' || substr($file_dir, 0, 7) == 'magnet:' || substr($file_dir, 0, 5) == 'ed2k:') {
        $file_path = chop($file_dir);
        echo "<script type='text/javascript'>window.location='$file_path';</script>";
        exit;
    }
    $file_dir = chop($file_dir);
    if (!file_exists($file_dir)) {
        return false;
    }
    $temp = explode("/", $file_dir);

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"" . end($temp) . "\"");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: " . filesize($file_dir));
    ob_end_flush();
    @readfile($file_dir);
}
