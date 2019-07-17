<?php
/**
 * WordPress基础配置文件。
 *
 * 这个文件被安装程序用于自动生成wp-config.php配置文件，
 * 您可以不使用网站，您需要手动复制这个文件，
 * 并重命名为“wp-config.php”，然后填入相关信息。
 *
 * 本文件包含以下配置选项：
 *
 * * MySQL设置
 * * 密钥
 * * 数据库表名前缀
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/zh-cn:%E7%BC%96%E8%BE%91_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL 设置 - 具体信息来自您正在使用的主机 ** //
/** WordPress数据库的名称 */
define('DB_NAME', 'spzzds_wuyiyizhan_com');

/** MySQL数据库用户名 */
define('DB_USER', 'spzzdsdb');

/** MySQL数据库密码 */
define('DB_PASSWORD', 'PsqrQM9vYPUOxMls');

/** MySQL主机 */
define('DB_HOST', '127.0.0.1');

/** 创建数据表时默认的文字编码 */
define('DB_CHARSET', 'utf8mb4');

/** 数据库整理类型。如不确定请勿更改 */
define('DB_COLLATE', '');

/**#@+
 * 身份认证密钥与盐。
 *
 * 修改为任意独一无二的字串！
 * 或者直接访问{@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org密钥生成服务}
 * 任何修改都会导致所有cookies失效，所有用户将必须重新登录。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'dmk)~U.KgW?=*+*h:Y$We@s_jjZt]ntvZP 8$oY;=-<Tma*A(w=Yw*4G}i7-rjw?');
define('SECURE_AUTH_KEY',  '.W,2Z<T92V uF*HYpIC,YcI/Mf0b#>.0#/Z~pdF.k/dEopW$A&(nxj9)F&]nj478');
define('LOGGED_IN_KEY',    'u7/GU1}LX~?6f#WYIrK-YFHJGDl|~ kI6%x2;E.Sf,~iG0^Mq4Md; SbGTJ<=SNL');
define('NONCE_KEY',        '[WV +vDH63tz$=s(H7/D[8s~Iu*#dsyyu|yK5M8_$yEtxg&zcNXSj<V6!zS757>L');
define('AUTH_SALT',        ' Ta|22b:wS$*Zn$* iHe3Eu~e&Pu#N: &Sv6*/~Pv!B2W*[s;$9i:@i}axbP,w.V');
define('SECURE_AUTH_SALT', 'i_<(b_T_kzmaiTTwb!=B!#wqrtALg`Q>VSfi3c-Yo6>;c=sC}q Ss(E47FS?<? 4');
define('LOGGED_IN_SALT',   '$uo }3)qs-[G=WHO8d[+v$xLnMp[XU:U}5K^~)q Vt:t@#!UXX#g0+&5 #vFfo(E');
define('NONCE_SALT',       ']F$hG(pXj=AX)f~IB4)aTills)VuQx@FJI|bFKi1n$BDl%Eq@t.zVk_/9v*Y[ekc');

/**#@-*/

/**
 * WordPress数据表前缀。
 *
 * 如果您有在同一数据库内安装多个WordPress的需求，请为每个WordPress设置
 * 不同的数据表前缀。前缀名只能为数字、字母加下划线。
 */
$table_prefix  = 'wp_';

/**
 * 开发者专用：WordPress调试模式。
 *
 * 将这个值改为true，WordPress将显示所有用于开发的提示。
 * 强烈建议插件开发者在开发环境中启用WP_DEBUG。
 *
 * 要获取其他能用于调试的信息，请访问Codex。
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* 好了！请不要再继续编辑。请保存本文件。使用愉快！ */

/** WordPress目录的绝对路径。 */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** 设置WordPress变量和包含文件。 */
require_once(ABSPATH . 'wp-settings.php');
