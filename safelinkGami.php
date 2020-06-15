<?php

/*
 Plugin Name: Safelink Private - Sativa
 Plugin URI: #
 Description: Ntahlah ini deskripsi..
 Version: 1.0
 Author: Sativa Wahyu Priyanto
 Author URI: nimegami.com
 */

class safe_nimegami {
    public function __construct(){
        add_action('admin_menu', array(&$this, 'safe_menu'));
        add_action('init', array(&$this, 'safelink_setting'));
        add_shortcode('safelink_home', array(&$this, 'safelink_home'));
        add_shortcode('safelink_single_top', array(&$this, 'safelink_single_top'));
        add_shortcode('safelink_copy_link', array(&$this, 'safelink_copy_link'));
        add_action('wp_head', array(&$this, 'css_safelink'));
        add_action('wp_footer', array(&$this, 'js_safelink'));

        function opti_lsg($id, $else){
            if(get_option($id)){
                return get_option($id);
            }else{
                return $else;
            }
        }

        function random_url(){
            $link = "";
            $args = new wp_query(['posts_per_page' => 1, 'orderby' => 'rand', 'post_type' => 'POST']);
            if($args->have_posts()) : while($args->have_posts()) : $args->the_post();
                $link = get_the_permalink();
            endwhile; endif;
            return $link;
        }

    }
    public function safelink_setting(){
        register_setting('img_safe', 'img_home');
        register_setting('img_safe', 'img_single');
        register_setting('img_safe', 'img_please_wait');

        register_setting('interval_safelink', 'intervalx');
        register_setting('interval_safelink', 'intervala');
    }
    public function safe_menu(){
        add_menu_page( 'Safelink Nimegami', 'Safelink Nimegami', 'manage_options', 'safelink-nimegami', array(&$this, 'safelink_nimegami_callback') );
    }
    
    public function safelink_nimegami_callback(){
        ?>
        <style>
            .safe_container {display: grid;grid-template-columns:repeat(2, 1fr)}
            .safe {padding:20px;background:#fff;border:1px solid #ddd;border-radius:4px;display:inline-block;box-sizing:border-box;margin-right:2%;margin-bottom:2%;}
            .safe h3 {font-size:14px;margin:0 0 10px 0;}
            .safe input {width:500px}
            .img_safe {margin-top:10px;width:200px;height:auto;margin-bottom:10px}
            .clear {clear:both;}
            .shortc {background:#eee;padding:5px;color:#333;display:inline-block;}
        </style>
        <h1>Safelink Nimegami</h1>
        <p>Safelink Nimegami v.1 yang sederhana.</p>
        <h2>Shortcode</h2>
        <form method="POST" action="options.php">
            <?php settings_fields( 'img_safe' ); ?>
            <?php do_settings_sections( 'img_safe' ); ?>
            <div class="safe_container">
                <div class="safe">
                    <h3><label for="home_img">Gambar di Halaman Utama</label></h3>

                    <input type="text" id="home_img" name="img_home" value="<?php echo opti_lsg('img_home', plugins_url().'/safelinkGami/img/img-1.png'); ?>">
                    <div class="clear"></div>
                    <img class="img_safe" src="<?php echo opti_lsg('img_home', plugins_url().'/safelinkGami/img/img-1.png'); ?>">
                    <div class="clear"></div>
                    <span class="shortc">[safelink_home]</span> | <span class="shortc">do_shortcode('[safelink_home]')</span>
                </div>

                <div class="safe">
                    <h3><label for="img_single">Gambar di Halaman Post (Klik 2x)</label></h3>

                    <input type="text" id="img_single" name="img_single" value="<?php echo opti_lsg('img_single', plugins_url().'/safelinkGami/img/img-4.png'); ?>">
                    <div class="clear"></div>
                    <img class="img_safe" src="<?php echo opti_lsg('img_single', plugins_url().'/safelinkGami/img/img-4.png'); ?>">
                    <div class="clear"></div>
                    <span class="shortc">[safelink_single_top]</span> | <span class="shortc">do_shortcode('[safelink_single_top]')</span>
                </div>

                <div class="safe">
                    <h3><label for="img_please_wait">Gambar di Halaman Post (Please Wait)</label></h3>

                    <input type="text" id="img_please_wait" name="img_please_wait" value="<?php echo opti_lsg('img_please_wait', plugins_url().'/safelinkGami/img/img-3.png'); ?>">
                    <div class="clear"></div>
                    <img class="img_safe" src="<?php echo opti_lsg('img_please_wait', plugins_url().'/safelinkGami/img/img-3.png'); ?>">
                </div>
                <div class="safe">
                    <h3>Link Asli</h3>
                    <span class="shortc">[safelink_copy_link]</span> | <span class="shortc">do_shortcode('[safelink_copy_link]')</span>
                </div>

            </div>

            <?php submit_button(); ?>
        </form>

        <h2>Interval</h2>
        <form method="POST" action="options.php">
            <?php settings_fields( 'interval_safelink' ); ?>
            <?php do_settings_sections( 'interval_safelink' ); ?>
            <label for="interval_01">Interval Pertama</label>
            <input type="text" id="intervalx" name="intervalx" value="<?php echo opti_lsg('intervalx', '0'); ?>"> Detik
            <br><br>
            <label for="interval_01">Interval Kedua</label>
            <input type="text" id="intervala" name="intervala" value="<?php echo opti_lsg('intervala', '0'); ?>"> Detik
            
            <?php submit_button(); ?>
        </form>
        <h2>Auto Safelink</h2>
        <textarea cols="100" rows="20">
        <!-- Full Page Script Exclude-->
            <script type="text/javascript">
            var go_url = 'http://example.com'; // Ganti dengan domain safelink pertama anda
            var shorten_exclude = ['google.com', 'youtube.com']; // link yang tidak ingin disafelink
            </script>

            <script>
            function go_get_url(url) {
                var l = document.createElement("a");
                l.href = url;
                return l
            };

            function go_get_host_name(url) {
                var domain;
                if (typeof url === 'undefined' || url === null || url === '' || url.match(/^\#/)) {
                    return ""
                }
                url = go_get_url(url);
                if (url.href.search(/^http[s]?:\/\//) !== -1) {
                    domain = url.href.split('/')[2]
                } else {
                    return ""
                }
                domain = domain.split(':')[0];
                return domain.toLowerCase()
            }
            document.addEventListener("DOMContentLoaded", function(event) {
                if (typeof go_url === 'undefined') {
                    return
                }
                var advert_type = 2;
                var anchors = document.getElementsByTagName("a");
                if (typeof shorten_includ !== 'undefined') {
                    for (var i = 0; i < anchors.length; i++) {
                        var hostname = go_get_host_name(anchors[i].getAttribute("href"));
                        if (hostname.length > 0 && shorten_includ.indexOf(hostname) > -1) {
                            anchors[i].href = go_url + "/?data=" + btoa(anchors[i].href)
                        } else {
                            if (anchors[i].protocol === "magnet:") {
                                anchors[i].href = go_url + "/?data=" + btoa(anchors[i].href)
                            }
                        }
                    }
                    return
                }
                if (typeof shorten_exclude !== 'undefined') {
                    for (var i = 0; i < anchors.length; i++) {
                        var hostname = go_get_host_name(anchors[i].getAttribute("href"));
                        if (hostname.length > 0 && shorten_exclude.indexOf(hostname) === -1) {
                            anchors[i].href = go_url + "/?data=" + btoa(anchors[i].href)
                        } else {
                            if (anchors[i].protocol === "magnet:") {
                                anchors[i].href = go_url + "/?data=" + btoa(anchors[i].href)
                            }
                        }
                    }
                    return
                }
            })
            </script>
            <!-- Full Page Script Exclude-->
        </textarea>
        <?php
    }

    public function css_safelink(){
        ?>
        <style>
            .img_safe {width:200px;display:block;margin:10px auto;}
            .safelink_home,.go_safe {display:none;text-align:center;}
            .go_safe {cursor:pointer;}
            .copy_link_asli {position:absolute;top:-100px;}
            .copy_safe {display:none;}
            .generate_link_asli {background:#50B3D9;border-radius:4px;color:#fff;padding:10px 15px;display:inline-block;font-size:22px;cursor:pointer;}
            .generate_link_asli_container {text-align:center;display:none;}

            .copy_safe {border-radius:7px;background:#CFE7F1;padding:20px}
            .copy_safe h3 {font-size:17px;margin:0 0 10px 0;letter-spacing:0.5px;color:#333;}

            .input_class {overflow:hidden;position:relative;}
            .input_safe {width:100%;height:40px;box-sizing:border-box;border-radius:20px !important;font-size:17px;}
            .copy_asli {height:40px;padding: 0 20px;border-radius:20px;background:#4995B5;color:#fff;position:absolute;top:50%;right:0;font-size:15px;line-height:40px;-webkit-transform: translate(0, -50%);-ms-transform: translate(0, -50%);transform: translate(0, -50%);font-size:14px;}
        </style>
        <?php
    }

    public function js_safelink(){
        ?>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script>
            $(document).ready(function() {
                var interval01 = setInterval(function(){ $('.safelink_home').show(); $('.safelink_please_wait').hide(); clearInterval(interval01); }, <?php echo opti_lsg('intervalx', '0')*1000; ?>);

                var interval02 = setInterval(function(){ $('.go_safe').show(); $('.safelink_please_wait').hide(); clearInterval(interval02); }, <?php echo opti_lsg('intervala', '0')*1000; ?>);

                $('.go_safe').click(function(){
                    $('html, body').animate({
                        scrollTop: $('.copy_link_asli').offset().top
                    }, 1000);
                    $('.generate_link_asli_container').show();
                })

                $('.generate_link_asli').click(function(){
                    $('.copy_safe').slideToggle();
                })

            });

            function copyvalue() {
                /* Get the text field */
                var copyText = document.getElementById("input_safe");

                /* Select the text field */
                copyText.select();
                copyText.setSelectionRange(0, 99999); /*For mobile devices*/

                /* Copy the text inside the text field */
                document.execCommand("copy");

                /* Alert the copied text */
            }

        </script>
        <?php
    }

    public function safelink_home(){
        if(!empty(isset($_GET['data'])) && is_home()){
            $please = '<div class="safelink_please_wait"><img class="img_safe" src="'.opti_lsg('img_please_wait', plugins_url().'/safelinkGami/img/img-3.png').'"></div>';
            $generate = '<div class="safelink_home"><form action="'.random_url().'" method="POST"><button type="submit" style="border: 0; background: transparent"><img class="img_safe" src="'.opti_lsg('img_home', plugins_url().'/safelinkGami/img/img-1.png').'"></button><input type="hidden" value="'.$_GET['data'].'" name="blogdata_url"></form></a></div>';
            return $please.$generate;
        }
    }
    public function safelink_single_top(){
        if(!empty($_POST['blogdata_url']) && is_single()){
            $please = '<div class="safelink_please_wait"><img class="img_safe" src="'.opti_lsg('img_please_wait', plugins_url().'/safelinkGami/img/img-3.png').'"></div>';
            $klik_2x = '<div class="go_safe"><img class="img_safe" src="'.opti_lsg('img_single', plugins_url().'/safelinkGami/img/img-4.png').'"></div>';
            return $please.$klik_2x;
        }
    }
    public function safelink_copy_link(){
        if(!empty($_POST['blogdata_url']) && is_single()){
            $open = '<div style="position:relative;"><div class="copy_link_asli"></div></div><div class="generate_link_asli_container"><div class="generate_link_asli">Generate Link Asli</div></div>';
            $data = '<div class="copy_safe"><h3>Berikut ini adalah link asli, silahkan copy dan paste di kolom url browser.</h3><div class="input_class"><input type="text" id="input_safe" class="input_safe" value="'.base64_decode($_POST['blogdata_url']).'"><button class="copy_asli" onclick="copyvalue()">Copy</button></div></div>';
            $copy_link = '<div class="copy_link">'.$data.'</div>';
            return $open.$copy_link;
        }
    }
}

$safelink_nimegami = new safe_nimegami();
