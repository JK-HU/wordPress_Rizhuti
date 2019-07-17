<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.

//
// Set a unique slug-like ID
//
$prefix = '_prefix_taxonomy_options';

//
// Create taxonomy options
//
CSF::createTaxonomyOptions($prefix, array(
    'taxonomy'  => 'category',
    'data_type' => 'unserialize', // The type of the database save options. `serialize` or `unserialize`
));

//
// Create a section
//
CSF::createSection($prefix, array(
    'fields' => array(

        array(
            'id'          => 'cat-img',
            'type'        => 'upload',
            'title'       => '自定义分类图片',
            'library'     => 'image',
            'placeholder' => 'http://',
        ),
        array(
            'id'    => 'seo-title',
            'type'  => 'text',
            'title' => '自定义SEO标题',
            'help'  => '不设置则遵循WP标题规则',
        ),
        array(
            'id'    => 'seo-keywords',
            'type'  => 'textarea',
            'title' => '自定义SEO关键词',
            'help'  => '自定义SEO关键词,用英文逗号隔开',
        ),
        array(
            'id'    => 'seo-description',
            'type'  => 'textarea',
            'title' => '自定义SEO描述',
            'help'  => '自定义SEO描述',
        ),

    ),
));
