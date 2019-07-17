<?php
/**
 * [unregister_d_widget 初始化日主题小工具]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:35:51+0800
 * @return   [type]                   [description]
 */
function unregister_d_widget()
{
    unregister_widget('WP_Widget_Recent_Comments');
}
add_action('widgets_init', 'unregister_d_widget');

$widgets = array('download', 'asst', 'comments', 'postlist', 'textasst');

/**
 * [widget_ui_loader 加载小工具]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:36:06+0800
 * @return   [type]                   [description]
 */
function widget_ui_loader()
{
    global $widgets;
    foreach ($widgets as $widget) {
        register_widget('widget_' . $widget);
    }
}
add_action('widgets_init', 'widget_ui_loader');

/**
 * 下载信息小部件
 */
class widget_download extends WP_Widget
{

    public function __construct()
    {
        parent::__construct('widget_download', _the_theme_name() . ': 资源下载信息', array('classname' => 'widget-download'));
    }
    /**
     * [widget 下载小工具]
     * @Author   Dadong2g
     * @DateTime 2019-05-28T13:36:20+0800
     * @param    [type]                   $args     [description]
     * @param    [type]                   $instance [description]
     * @return   [type]                             [description]
     */
    public function widget($args, $instance)
    {
        extract($args);
        global $post;
        $type      = get_post_meta($post->ID, 'wppay_type', true);
        $price     = get_post_meta($post->ID, 'wppay_price', true);
        $demo_url  = get_post_meta($post->ID, 'wppay_demourl', true);
        $downData  = get_post_meta($post->ID, 'wppay_down', true);
        $infoArr   = get_post_meta($post->ID, 'wppay_info', true);
        $post_auth = get_post_meta($post->ID, 'wppay_vip_auth', true);

        // 优惠信息
        switch (intval($post_auth)) {
            case 1:
                $vip_infotext = '月费会员免费';
                break;
            case 2:
                $vip_infotext = '年费会员免费';
                break;
            case 3:
                $vip_infotext = '终身会员免费';
                break;
            case 4:
                $vip_infotext = '限时免费';
                break;
            default:
                $vip_infotext = '资源信息';
        }
        // 判断资源是否需要显示下载小工具
        if ($type >= 3) {
            // 检测当前用户是否已购买
            $user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
            $shop    = new SHOP($post->ID, $user_id);
            if ($shop->is_paid() || $type == 4) {
                $content_pay = '';
                if ($downData && is_array($downData)) {
                    $new_downArr = $downData;
                } else {
                    #旧版本数据完美兼容处理wppay_down wppay_down_info
                    $new_downArr[] = array(
                        'name' => '立即下载',
                        'url'  => $downData,
                        'pwd'  => get_post_meta($post->ID, 'wppay_down_info', true),
                        'lock' => 1,
                    );
                }
                foreach ($new_downArr as $key => $value) {
                    $action_url = get_stylesheet_directory_uri() . '/action/download.php?id=' . $post->ID . '&url=';
                    $down_url   = ($value['lock']) ? $action_url . rizhuti_lock_url($value['url'], _hui('rzt_down_downkey')) : $value['url'];
                    $content_pay .= '<a href="javascript:;" target="_blank" data-url="' . $down_url . '" data-info="' . $value['pwd'] . '" class="btn btn-primary download-popup"><i class="iconfont">&#xe69d;</i> ' . $value['name'] . '</a>';
                }
            } else {
                if (!_hui('no_loginpay') && !is_user_logged_in()) {
                    $content_pay = '<a href="' . home_url('login') . '" class="btn btn-primary" etap="login_btn"><i class="iconfont">&#xe66b;</i> 登录购买</a>';
                } else {
                    $content_pay = '<a href="javascript:;" id="pay-loader" data-nonce="' . wp_create_nonce("pay-click-" . $post->ID) . '" data-post="' . $post->ID . '" class="btn btn-primary"><i class="iconfont">&#xe762;</i> 立即购买</a>';
                }
            }
            echo $before_widget;
            echo '<div class="down-info">';
            if ($price && $type != 4) {
                echo '<div class="price"><font>' . $price . '</font><span>元</span></div>';
            }
            echo '<p class="vipinfo">' . $vip_infotext . '</p>';
            // 下载购买信息
            echo $content_pay;
            // 演示地址
            if ($demo_url) {
                echo '<a href="' . $demo_url . '" target="_blank" id="post-demo" class="btn btn-default"><i class="iconfont">&#xe63e;</i> 演示地址</a>';
            }
            //其他信息
            echo '<table><tbody>';

            if ($infoArr) {
                foreach ($infoArr as $key => $value) {
                    echo '<tr><td><font>' . $value['title'] . '</font></td><td><font>' . $value['desc'] . '</font></td></tr>';
                }
            }
            echo '<tr><td><font>最近更新</font></td><td><font>' . get_the_modified_time('Y年m月d日') . '</font></td></tr>';
            echo '</tbody></table>';
            // 在线咨询信息
            if (_hui('ac_qqhao')) {
                echo '<a class="ac_qqhao" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=' . _hui('ac_qqhao') . '&site=qq&menu=yes"><i class="iconfont">&#xe640;</i> 在线咨询</a>';
            }
            echo '</div>';
            echo $after_widget;
        }
    }
}

class widget_asst extends WP_Widget
{

    public function __construct()
    {
        parent::__construct('widget_asst', _the_theme_name() . ': 广告', array('classname' => 'widget-asst'));
    }

    public function widget($args, $instance)
    {
        extract($args);

        $title   = apply_filters('widget_name', $instance['title']);
        $code    = $instance['code'];
        $nophone = isset($instance['nophone']) ? $instance['nophone'] : '';

        if ($nophone && wp_is_mobile()) {

        } else {
            echo $before_widget;
            echo $code;
            echo $after_widget;
        }
    }

    public function form($instance)
    {
        $defaults = array(
            'title'   => __('广告', 'haoui') . ' ' . date('m-d'),
            'code'    => '<a href="https://ylit.cc/" target="_blank"><img src="' . get_stylesheet_directory_uri() . '/img/wpay_ad.jpg"></a>',
            'nophone' => '',
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
    <p>
      <label>
        <?php echo __('标题：', 'haoui') ?>
        <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" class="widefat" />
      </label>
    </p>
    <p>
      <label>
        <?php echo __('广告代码：', 'haoui') ?>
        <textarea id="<?php echo $this->get_field_id('code'); ?>" name="<?php echo $this->get_field_name('code'); ?>" class="widefat" rows="12" style="font-family:Courier New;"><?php echo $instance['code']; ?></textarea>
      </label>
    </p>
    <p>
      <label>
        <input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['nophone'], 'on');?> id="<?php echo $this->get_field_id('nophone'); ?>" name="<?php echo $this->get_field_name('nophone'); ?>">不在手机端显示
      </label>
    </p>
  <?php
}
}

class widget_comments extends WP_Widget
{

    public function __construct()
    {
        parent::__construct('widget_comments', _the_theme_name() . ': 最新评论', array('classname' => 'widget-comments'));
    }

    public function widget($args, $instance)
    {
        extract($args);

        $title = apply_filters('widget_name', $instance['title']);
        $limit = $instance['limit'];
        $outer = $instance['outer'];

        if (!$outer) {
            $outer = -1;
        }

        echo $before_widget;
        echo $before_title . $title . $after_title;

        $output = '';

        global $wpdb;
        $sql      = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date, comment_approved,comment_author_email, comment_type,comment_author_url, SUBSTRING(comment_content,1,60) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE user_id!='" . $outer . "' AND comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date DESC LIMIT $limit";
        $comments = $wpdb->get_results($sql);

        foreach ($comments as $comment) {
            $output .= '<li><a' . _target_blank() . ' href="' . get_permalink($comment->ID) . '#comment-' . $comment->comment_ID . '" title="' . $comment->post_title . __('上的评论', 'haoui') . '">';
            $output .= _get_user_avatar($comment->comment_author_email);
            $output .= '<div class="inner"><time><strong>' . strip_tags($comment->comment_author) . '</strong>' . ($comment->comment_date) . '</time>' . str_replace(' src=', ' data-src=', convert_smilies(strip_tags($comment->com_excerpt))) . '</div>';
            $output .= '</a></li>';
        }

        echo '<ul>' . $output . '</ul>';
        echo $after_widget;
    }

    public function form($instance)
    {
        $defaults = array('title' => __('最新评论', 'haoui'), 'limit' => 8, 'outer' => '');
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
    <p>
      <label>
        <?php echo __('标题：', 'haoui') ?>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
      </label>
    </p>
    <p>
      <label>
        <?php echo __('显示数目：', 'haoui') ?>
        <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" />
      </label>
    </p>
    <p>
      <label>
        <?php echo __('排除某用户ID：', 'haoui') ?>
        <input class="widefat" id="<?php echo $this->get_field_id('outer'); ?>" name="<?php echo $this->get_field_name('outer'); ?>" type="number" value="<?php echo $instance['outer']; ?>" />
      </label>
    </p>

  <?php
}
}

class widget_postlist extends WP_Widget
{

    public function __construct()
    {
        parent::__construct('widget_postlist', _the_theme_name() . ': 文章展示', array('classname' => 'widget-postlist'));
    }

    public function widget($args, $instance)
    {
        extract($args);

        $title   = apply_filters('widget_name', $instance['title']);
        $limit   = $instance['limit'];
        $cat     = isset($instance['cat']) ? $instance['cat'] : '';
        $orderby = $instance['orderby'];
        // $showstyle      = $instance['showstyle'];
        // $img = $instance['img'];

        // $style = ' class="'.$showstyle.'"';
        echo $before_widget;
        echo $before_title . $title . $after_title;
        echo '<ul>';

        $args = array(
            'order'               => 'DESC',
            'cat'                 => $cat,
            'orderby'             => $orderby,
            'showposts'           => $limit,
            'ignore_sticky_posts' => 1,
        );
        query_posts($args);
        while (have_posts()): the_post();
            echo '<li><a class="thumbnail" ' . _target_blank() . ' href="' . get_the_permalink() . '">';
            /*if( $showstyle!=='items-03' ){
            }*/
            echo '' . _get_post_thumbnail() . '</a>';
            echo '<a' . _target_blank() . ' href="' . get_the_permalink() . '">' . get_the_title() . '</a>';
            echo '</li>';

        endwhile;
        wp_reset_query();

        echo '</ul>';
        echo $after_widget;

    }

    public function form($instance)
    {
        $defaults = array(
            'title'   => '最新文章',
            'limit'   => 6,
            'orderby' => 'date',
            // 'showstyle' => ''
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
    <p>
      <label>
        <?php echo __('标题：', 'haoui') ?>
        <input style="width:100%;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
      </label>
    </p>
    <p>
      <label>
        <?php echo __('排序：', 'haoui') ?>
        <select style="width:100%;" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" style="width:100%;">
          <option value="comment_count" <?php selected('comment_count', $instance['orderby']);?>><?php echo __('评论数', 'haoui') ?></option>
          <option value="date" <?php selected('date', $instance['orderby']);?>><?php echo __('发布时间', 'haoui') ?></option>
          <option value="rand" <?php selected('rand', $instance['orderby']);?>><?php echo __('随机', 'haoui') ?></option>
        </select>
      </label>
    </p>

    <p>
      <label>
        <?php echo __('分类限制：', 'haoui') ?>
        <a style="font-weight:bold;color:#f60;text-decoration:none;" href="javascript:;" title="<?php echo __('格式：1,2 &nbsp;表限制ID为1,2分类的文章&#13;格式：-1,-2 &nbsp;表排除分类ID为1,2的文章&#13;也可直接写1或者-1；注意逗号须是英文的', 'haoui') ?>">？</a>
        <input style="width:100%;" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>" type="text" value="<?php echo $instance['cat']; ?>" size="24" />
      </label>
    </p>
    <p>
      <label>
        <?php echo __('显示数目：', 'haoui') ?>
        <input style="width:100%;" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" size="24" />
      </label>
    </p>

  <?php
}
}

class widget_textasst extends WP_Widget
{

    public function __construct()
    {
        parent::__construct('widget_textasst', _the_theme_name() . ': 特别推荐', array('classname' => 'widget-textasst'));
    }

    public function widget($args, $instance)
    {
        extract($args);

        $title   = apply_filters('widget_name', $instance['title']);
        $tag     = $instance['tag'];
        $content = $instance['content'];
        $link    = $instance['link'];
        $style   = $instance['style'];
        $blank   = isset($instance['blank']) ? $instance['blank'] : '';

        $lank = '';
        if ($blank) {
            $lank = ' target="_blank"';
        }

        echo $before_widget;
        echo '<a class="' . $style . '" href="' . $link . '"' . $lank . '>';
        echo '<strong>' . $tag . '</strong>';
        echo '<h2>' . $title . '</h2>';
        echo '<p>' . $content . '</p>';
        echo '</a>';
        echo $after_widget;
    }

    public function form($instance)
    {
        $defaults = array(
            'title'   => '付费资源主题',
            'tag'     => 'RIZHUTI',
            'content' => '如少女般纯洁，干净，无需任何插件，极度优化，支持支付宝，微信付款...',
            'link'    => 'https://vip.ylit.cc/',
            'style'   => 'style01',
            'blank'   => 'on',
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
    <p>
      <label>
        名称：
        <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" class="widefat" />
      </label>
    </p>
    <p>
      <label>
        描述：
        <textarea id="<?php echo $this->get_field_id('content'); ?>" name="<?php echo $this->get_field_name('content'); ?>" class="widefat" rows="3"><?php echo $instance['content']; ?></textarea>
      </label>
    </p>
    <p>
      <label>
        标签：
        <input id="<?php echo $this->get_field_id('tag'); ?>" name="<?php echo $this->get_field_name('tag'); ?>" type="text" value="<?php echo $instance['tag']; ?>" class="widefat" />
      </label>
    </p>
    <p>
      <label>
        链接：
        <input style="width:100%;" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="url" value="<?php echo $instance['link']; ?>" size="24" />
      </label>
    </p>
    <p>
      <label>
        样式：
        <select style="width:100%;" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" style="width:100%;">
          <option value="style01" <?php selected('style01', $instance['style']);?>>蓝色</option>
          <option value="style02" <?php selected('style02', $instance['style']);?>>橘红色</option>
          <option value="style03" <?php selected('style03', $instance['style']);?>>绿色</option>
          <option value="style04" <?php selected('style04', $instance['style']);?>>紫色</option>
        </select>
      </label>
    </p>
    <p>
      <label>
        <input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['blank'], 'on');?> id="<?php echo $this->get_field_id('blank'); ?>" name="<?php echo $this->get_field_name('blank'); ?>">新打开浏览器窗口
      </label>
    </p>
  <?php
}
}
