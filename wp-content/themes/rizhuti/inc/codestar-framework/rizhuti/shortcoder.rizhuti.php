<?php if ( ! defined( 'ABSPATH' )  ) { die; } // Cannot access directly.

//
// Set a unique slug-like ID
//
$prefix = 'csf_demo_shortcodes';

//
// Create a shortcoder
//
CSF::createShortcoder( $prefix, array(
  'button_title'   => '添加付费隐藏内容',
  'select_title'   => '选择添加的内容块',
  'insert_title'   => '插入简码',
  'show_in_editor' => true,
  'gutenberg'      => array(
    'title'        => '日主题简码',
    'description'  => '日主题简码块',
    'icon'         => 'screenoptions',
    'category'     => 'widgets',
    'keywords'     => array( 'shortcode', 'csf', 'insert' ),
    'placeholder'  => '在这里写短代码...',
  )
) );


//
// A shortcode [foo title=""]content[/foo]
//
CSF::createSection( $prefix, array(
  'title'     => '[rihide] 隐藏部分付费内容',
  'view'      => 'normal',
  'shortcode' => 'rihide',
  'fields'    => array(

    array(
      'id'    => 'content',
      'type'  => 'wp_editor',
      'title' => '',
      'desc' => '[rihide]隐藏部分付费内容[/rihide] <br/> 添加隐藏内容后，资源类型选择为付费查看内容模式',
    ),

  )
) );

