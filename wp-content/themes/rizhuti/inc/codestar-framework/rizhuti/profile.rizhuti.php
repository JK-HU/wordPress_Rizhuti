<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.

//
// Set a unique slug-like ID
//
$prefix = '_rizhuti_profile_options';

//
// Create profile options
//
CSF::createProfileOptions($prefix, array(
    'data_type' => 'unserialize',
));

//
// Create a section
//
CSF::createSection($prefix, array(
    'title'  => '会员其他信息-Rizhuti',
    'fields' => array(

        array(
            'id'          => 'vip_type',
            'type'        => 'select',
            'title'       => '会员类型',
            'placeholder' => '选择会员类型',
            'options'     => array(
                '0'    => '普通会员',
                '31'   => '包月会员',
                '365'  => '包年会员',
                '3600' => '终身会员',
            ),
        ),
        array(
            'id'       => 'vip_time',
            'type'     => 'text',
            'title'    => '会员到期时间戳（日期不对会导致会员过期，无权限查看）',
            'subtitle' => '时间戳转换：https://tool.lu/timestamp/',
        ),

    ),
));
