<?php
/*
 * Plugin Name:       FanaByte - External Connection Blocker
 * Plugin URI:        https://fanabyte.com/themes-plugins/plugins/fanabyte-plugins/fanabyte-external-connection-blocker/
 * Description:       Block and Manage Outgoing Connections from Your WordPress Admin Panel
 * Version:           1.0.0
 * Author:            FanaByte Academy
 * Author URI:        https://fanabyte.com
 * Text Domain:       fanabyte-external-connection-blocker
 * Requires at least: 6.8
 * Requires PHP:      7.4
 * License: 	      GPLv2 or later
 * Domain Path:       /languages
 */


// Function to enqueue styles for the plugin
function fanabyte_external_connection_blocker_styles() {
    wp_enqueue_style('external-connection-blocker-styles', plugins_url('assets/css/style.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'fanabyte_external_connection_blocker_styles'); // Use admin_enqueue_scripts for admin area

add_action('admin_menu', function () {
    $plugin_dir = plugin_dir_url(__FILE__);
    $icon_url = $plugin_dir . 'assets/images/fanabyte-plugins-icon.png';

    if ( get_locale() === 'fa_IR') {
        add_menu_page(
            'اتصالات خارجی',
            'اتصالات خارجی',
            'manage_options',
            'fanabyte-external-connection-blocker-home', // New slug for main page
            'fanabyte_external_connection_blocker_home_callback', // New callback for main page
            $icon_url,
            41 // Adjusted position to be before UTM Generator (42)
        );

        add_submenu_page(
            'fanabyte-external-connection-blocker-home',
            'آکادمی فنابایت',
            'آکادمی فنابایت',
            'manage_options',
            'fanabyte-external-connection-blocker-fanabyte', // Slug for submenu page
            'fanabyte_external_connection_blocker_fanabyte_callback' // Callback for submenu page
        );
    } else {
        add_menu_page(
            'External Connections',
            'External Connections',
            'manage_options',
            'fanabyte-external-connection-blocker-home',
            'fanabyte_external_connection_blocker_home_callback',
            $icon_url,
            41
        );

        add_submenu_page(
            'fanabyte-external-connection-blocker-home',
            'FanaByte Academy',
            'FanaByte Academy',
            'manage_options',
            'fanabyte-external-connection-blocker-fanabyte',
            'fanabyte_external_connection_blocker_fanabyte_callback'
        );
    }
});

function fanabyte_external_connection_blocker_home_callback() {
    if (!current_user_can('manage_options')) return;

    $blocked = get_option('blocked_external_hosts', []);
    $logged  = get_option('logged_external_hosts', []);

    if (isset($_POST['save_blocked_hosts'])) {
        check_admin_referer('save_blocked_hosts');
        $blocked = array_keys($_POST['blocked'] ?? []);
        update_option('blocked_external_hosts', $blocked);
        echo '<div class="updated"><p>تنظیمات ذخیره شد.</p></div>';
    }

    echo '<div class="bee-page-container">
    <div>
    <div style="padding: 15px;max-width: 1000px;margin: 0 auto;display: flex;background-color: white;border-radius: 5px;margin-top: 20px;border: 1px solid #ccc;">
    <div style="padding-bottom: 5px; padding-top: 5px; flex-basis: 100%;">
    <div>';

    if ( get_locale() === 'fa_IR') {
        echo '<h1 style="color:#7747FF;direction:ltr;font-size:38px;font-weight:700;letter-spacing:normal;line-height:120%;text-align:right;margin-top:0;margin-bottom:0;"><span class="tinyMce-placeholder">بلاک کردن اتصالات خارجی</span> </h1>
        <p style=" text-align: right; direction: rtl;padding: 10px;">در این بخش می توانید اتصالات خارجی وردپرس خود را مدیریت و مسدود کنید. اتصالات شناسایی شده در جدول زیر لیست شده اند:</p>';
    } else {
        echo '<h1 style="color:#7747FF;direction:ltr;font-size:38px;font-weight:700;letter-spacing:normal;line-height:120%;text-align:left;margin-top:0;margin-bottom:0;">Block External Connections</h1>
        <p style="padding: 10px; text-align: left; direction: ltr;">In this section, you can manage and block external connections from your WordPress site. Identified connections are listed in the table below:</p>';
    }

    if (empty($logged)) {
        echo '<p>هنوز هیچ اتصال خارجی شناسایی نشده است.</p>';
    }
    echo '<form method="post">';
    wp_nonce_field('save_blocked_hosts');
    echo '<table class="widefat"><thead><tr><th>بلاک</th><th>دامنه</th></tr></thead><tbody>';

    foreach ($logged as $host) {
        if (fanabyte_is_internal_host($host)) continue;
        echo '<tr>';
        echo '<td><input type="checkbox" name="blocked[' . esc_attr($host) . ']" ' . checked(in_array($host, $blocked), true, false) . '></td>';
        echo '<td>' . esc_html($host) . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '<p><input type="submit" name="save_blocked_hosts" class="button-primary" value="ذخیره تغییرات"></p>';
    echo '</form></div>'; // Closing div for content
    echo '</div></div></div></div>'; // Closing divs for styling container
}


function fanabyte_external_connection_blocker_fanabyte_callback() {
    $plugin_dir = plugin_dir_url(__FILE__);
    $logo_url = $plugin_dir . 'assets/images/fanabyte-logo.png';

    echo '<div class="bee-page-container">
    <div>
    <div style="padding: 15px;max-width: 1000px;margin: 0 auto;display: flex;background-color: white;border-radius: 5px;margin-top: 20px;border: 1px solid #ccc;">
    <div style="padding-bottom: 5px; padding-top: 5px; flex-basis: 100%;">
    <div>';

    $icon_youtube = $plugin_dir . 'assets/images/YouTube.png';
    $icon_email = $plugin_dir . 'assets/images/Email.png';
    $icon_facebook = $plugin_dir . 'assets/images/Facebook.png';
    $icon_github = $plugin_dir . 'assets/images/GitHub.png';
    $icon_instagram = $plugin_dir . 'assets/images/Instagram.png';
    $icon_linkedin = $plugin_dir . 'assets/images/LinkedIn.png';
    $icon_telegram = $plugin_dir . 'assets/images/Telegram.png';
    $icon_threads = $plugin_dir . 'assets/images/Threads.png';
    $icon_x = $plugin_dir . 'assets/images/X.png';
    $icon_pintrest = $plugin_dir . 'assets/images/Pinterest.png';

    if ( get_locale() === 'fa_IR') {
        echo '<h1 style="color:#7747FF;direction:ltr;font-size:38px;font-weight:700;letter-spacing:normal;line-height:120%;text-align:right;margin-top:0;margin-bottom:0;"><span class="tinyMce-placeholder">آکادمی فنابایت</span> </h1>
        </div>
        <div style="padding: 30px;position: relative;">
        <div style="position: relative;display: inline-block;width: 55%;margin-left: 2%;margin-right: 2%;white-space-collapse: preserve-breaks;">
            <h3>داستان فنابایت</h3>
            <p style="text-align: justify;">از سال ۱۴۰۰ شروع به‌کار کردیم البته دقیق‌تر از سال ۱۳۹۰ اون اوایل وبسایت رسمی نداشتیم، هدف فنابایت کمک به همه افراد برای ساخت یک کسب‌و‌کار اینترنتی موفق است. اما چطور؟ ما در فنابایت نحوه راه اندازی و استفاده از بهترین سایت‌ساز دنیا را که بیش از ۴۰ درصد وب‌سایت های دنیا از آن استفاده می‌کنند را به شما می‌آموزیم. هم‌چنین کلی مقاله و ویدئو های آموزشی رایگان و یکسری پکیج های آموزشی در حوزه های مختلف منتشر کرده‌ایم، که میتوانید از آنها در جهت افزایش علم و توسعه کسب و کار خود استفاده کنید.</p>
            </div>
        <div style="position: relative;display: inline-block;width: 35%;margin-left: 2%;margin-right: 2%;">
            <p style="text-align: center;"><img src="';echo $logo_url; echo '" alt="آکادمی فنابایت - FanaByte Academy" style="height: 200px;"></p>
        </div>
        </div>

        <div style="background-color: #f0f0f1;padding: 10px;position: relative;border-radius: 3px;text-align: center;">
        <div style="position: relative;display: inline-block;">';
        echo '<p style="text-align: center;"><b>ما را دنبال کنید!</b></p>';
        echo '<p style="text-align: center;"><b>لطفا ما را در شبکه‌های اجتماعی دنبال کنید:</b></p>';
        echo '<p style="text-align: center;">';
        echo '<a href="https://youtube.com/@fanabyte" target="_blank"><img src="';echo $icon_youtube; echo'" alt="FanaByte Youtube" style="height: 30px;"></a> ';
        echo '<a href="https://instagram.com/fanabyte" target="_blank"><img src="';echo $icon_instagram; echo'" alt="FanaByte Instagram" style="height: 30px;"></a> ';
        echo '<a href="https://threads.net/@fanabyte" target="_blank"><img src="';echo $icon_threads; echo'" alt="FanaByte Threads" style="height: 30px;"></a> ';
        echo '<a href="https://facebook.com/fanabyte" target="_blank"><img src="';echo $icon_facebook; echo'" alt="FanaByte Facebook" style="height: 30px;"></a> ';
        echo '<a href="https://github.com/fanabyte" target="_blank"><img src="';echo $icon_github; echo'" alt="FanaByte GitHub" style="height: 30px;"></a> ';
        echo '<a href="https://www.linkedin.com/company/fanabyte" target="_blank"><img src="';echo $icon_linkedin; echo'" alt="FanaByte LinkedIn" style="height: 30px;"></a> ';
        echo '<a href="https://twitter.com/fanabyte" target="_blank"><img src="';echo $icon_x; echo'" alt="FanaByte X (Twitter)" style="height: 30px;"></a> ';
        echo '<a href="https://t.me/fanabyte" target="_blank"><img src="';echo $icon_telegram; echo'" alt="FanaByte Telegram" style="height: 30px;"></a> ';
        echo '<a href="https://www.pinterest.com/fanabyte" target="_blank"><img src="';echo $icon_pintrest; echo'" alt="FanaByte Pintrest" style="height: 30px;"></a> ';
        echo '<a href="mailto:info@fanabyte.com" target="_blank"><img src="';echo $icon_email; echo'" alt="FanaByte Email" style="height: 30px;"></a> ';
        echo '</p>';
        echo '<p style="text-align: center;"><b>☕ اگر از این افزونه لذت می‌برید و برای شما مفید است، لطفاً با خرید یک قهوه از توسعه آن حمایت کنید. حمایت شما برای ما بسیار ارزشمند است!</b></p>';
        echo '<p style="text-align: center;"><b><a style="text-decoration:none;" href="https://www.coffeete.ir/fanabyte" target="_blank" title="حمایت از فنابایت در کافیته">حمایت از فنابایت در کافیته</a></b></p>';
        echo '<p style="text-align: center;"><b><a style="text-decoration:none;" href="https://fanabyte.com/" target="_blank" title="وبسایت آکادمی فنابایت">مشاهده وب‌سایت آکادمی فنابایت</a></b></p>';
        echo '</p></p>
        </div>';
        echo'</div>
        ';} else {
        echo '<h1 style="color:#7747FF;direction:ltr;font-size:38px;font-weight:700;letter-spacing:normal;line-height:120%;text-align:left;margin-top:0;margin-bottom:0;"><span class="tinyMce-placeholder">FanaByte Academy</span> </h1>
        </div>
        <div style="padding: 30px;position: relative;">
        <div style="position: relative;display: inline-block;width: 55%;margin-left: 2%;margin-right: 2%;white-space-collapse: preserve-breaks;">
            <h3>FanaByte story</h3>
            <p style="text-align: justify;">We started working since 2001, although we did not have an official website since 2011, FanaByte goal is to help everyone build a successful internet business. But how? At FanaByte we will teach you how to set up and use the world best website builder, which is used by more than 40% of the world websites. Also, we have published a lot of free educational articles and videos and a series of educational packages in different fields, which you can use to increase your knowledge and develop your business.</p>
            </div>
        <div style="position: relative;display: inline-block;width: 35%;margin-left: 2%;margin-right: 2%;">
            <p style="text-align: center;"><img src="';echo $logo_url; echo '" alt="آکادمی فنابایت - FanaByte Academy" style="height: 200px;"></p>
        </div>
        </div>

        <div style="background-color: #f0f0f1;padding: 10px;position: relative;border-radius: 3px;text-align: center;">
        <div style="position: relative;display: inline-block;">';
        echo '<p style="text-align: center;"><b>Follow us!</b></p>';
        echo '<p style="text-align: center;"><b>Please follow us on social networks:</b></p>';
        echo '<p style="text-align: center;">';
        echo '<a href="https://youtube.com/@fanabyte" target="_blank"><img src="';echo $icon_youtube; echo'" alt="FanaByte Youtube" style="height: 30px;"></a> ';
        echo '<a href="https://instagram.com/fanabyte" target="_blank"><img src="';echo $icon_instagram; echo'" alt="FanaByte Instagram" style="height: 30px;"></a> ';
        echo '<a href="https://threads.net/@fanabyte" target="_blank"><img src="';echo $icon_threads; echo'" alt="FanaByte Threads" style="height: 30px;"></a> ';
        echo '<a href="https://facebook.com/fanabyte" target="_blank"><img src="';echo $icon_facebook; echo'" alt="FanaByte Facebook" style="height: 30px;"></a> ';
        echo '<a href="https://github.com/fanabyte" target="_blank"><img src="';echo $icon_github; echo'" alt="FanaByte GitHub" style="height: 30px;"></a> ';
        echo '<a href="https://www.linkedin.com/company/fanabyte" target="_blank"><img src="';echo $icon_linkedin; echo'" alt="FanaByte LinkedIn" style="height: 30px;"></a> ';
        echo '<a href="https://twitter.com/fanabyte" target="_blank"><img src="';echo $icon_x; echo'" alt="FanaByte X (Twitter)" style="height: 30px;"></a> ';
        echo '<a href="https://t.me/fanabyte" target="_blank"><img src="';echo $icon_telegram; echo'" alt="FanaByte Telegram" style="height: 30px;"></a> ';
        echo '<a href="https://www.pinterest.com/fanabyte" target="_blank"><img src="';echo $icon_pintrest; echo'" alt="FanaByte Pintrest" style="height: 30px;"></a> ';
        echo '<a href="mailto:info@fanabyte.com" target="_blank"><img src="';echo $icon_email; echo'" alt="FanaByte Email" style="height: 30px;"></a> ';
        echo '</p>';
        echo '<p style="text-align: center;"><b>☕ If you enjoy this plugin and find it useful, please support its development by buying us a coffee. Your support is greatly appreciated!</b></p>';
        echo '<p style="text-align: center;"><b><a style="text-decoration:none;" href="https://www.coffeete.ir/fanabyte" target="_blank" title="Support FanaByte on Coffeete">Support FanaByte on Coffeete</a></b></p>';
        echo '<p style="text-align: center;"><b><a style="text-decoration:none;" href="https://fanabyte.com/" target="_blank" title="FanaByte Academy Website">Visit FanaByte Academy Website</a></b></p>';
        echo '</p></p>
        </div>';
    }

    echo '</div>
    </div>
    </div>
    </div>';
}

add_filter('pre_http_request', function ($pre, $args, $url) {
    $host = parse_url($url, PHP_URL_HOST);

    if ($host && !fanabyte_is_internal_host($host)) {
        $logged = get_option('logged_external_hosts', []);
        if (!in_array($host, $logged)) {
            $logged[] = $host;
            update_option('logged_external_hosts', $logged);
        }

        $blocked = get_option('blocked_external_hosts', []);
        if (in_array($host, $blocked)) {
            return new WP_Error('http_blocked', 'اتصال به این دامنه بلاک شده است: ' . $host);
        }
    }

    return $pre;
}, 5, 3);

function fanabyte_is_internal_host($host) {
    $current_host = parse_url(home_url(), PHP_URL_HOST);
    $current_host = preg_replace('/^www\./', '', strtolower($current_host));
    $target_host = preg_replace('/^www\./', '', strtolower($host));
    return $current_host === $target_host;
}

function fanabyte_default_known_hosts() {
    return [
        'api.wordpress.org',
        'downloads.wordpress.org',
        's.w.org',
        'login.wordpress.org',
        'plugins.svn.wordpress.org',
        'themes.svn.wordpress.org',
        'fonts.googleapis.com',
        'fonts.gstatic.com',
        'google.com',
        'www.google.com',
        'recaptcha.net',
        'fanabyte.com',
        'api.fanabyte.com',
    ];
}

add_action('admin_init', function () {
    $logged = get_option('logged_external_hosts', []);
    $default_hosts = fanabyte_default_known_hosts();

    $merged = array_unique(array_merge($logged, $default_hosts));
    if ($merged !== $logged) {
        update_option('logged_external_hosts', $merged);
    }
});
?>