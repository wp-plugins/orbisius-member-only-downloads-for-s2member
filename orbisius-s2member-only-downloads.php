<?php
/*
Plugin Name: Orbisius Member Only Downloads for S2Member
Plugin URI: http://club.orbisius.com/products/wordpress-plugins/orbisius-s2member-only-downloads/
Description: S2Member extension plugin which allows you to display member only download links. You can also set a minimum level required in order for the download link to be displayed.
Version: 1.0.2
Author: Svetoslav Marinov (Slavi)
Author URI: http://orbisius.com
*/

/*  Copyright 2012 Svetoslav Marinov (Slavi) <slavi@orbisius.com>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Set up plugin
add_action('init', 'orbisius_s2member_only_downloads_init');
add_action('admin_init', 'orbisius_s2member_only_downloads_admin_init');
add_action('admin_menu', 'orbisius_s2member_only_downloads_setup_admin');
add_action('wp_footer', 'orbisius_s2member_only_downloads_add_plugin_credits', 1000); // be the last in the footer

/**
 * Replaces [orb_s2member_dl] with a download link. Requires user to be logged in
 * & to have certain access level in order to access a download.
 *
 * Example: [orb_s2member_dl url="http://file.zip"]
 * Example: [orb_s2member_dl req_login=1 url="http://file.zip"]
 * Example: [orb_s2member_dl level=1 url="http://file.zip"]
 * @return string
 */
function orbisius_s2member_only_downloads_shortcode($attr) {
    $url = empty($attr['url']) ? '' : trim($attr['url']);
    $level = empty($attr['level']) ? 0 : intval($attr['level']);
    $cls_succ = 'orb-s2member-dl-positive';
    $cls_err = 'orb-s2member-dl-negative';

    if (!isset($attr['req_login']) || $level || intval($attr['req_login']) == 1) {
        if ($level) {
            if (current_user_can("access_s2member_level" . $level)) {
                $buff = <<<BUFF_EOF
        <a href="$url" target="_blank">Download</a>
BUFF_EOF;
            } else {
                $cls = 'error';
                $buff = <<<BUFF_EOF
        You must be a premium member in order to access this download.
BUFF_EOF;
            }
        } else {
            $buff = <<<BUFF_EOF
        [s2If is_user_logged_in()]
            <div class="alert-positive">
                <a href="$url" target="_blank">Download</a>
        [/s2If]

        [s2If !is_user_logged_in()]
            You must be logged in order to access this download.
        [/s2If]
BUFF_EOF;
        }
    } else {
        $buff = <<<BUFF_EOF
    <div class="alert-positive">
        <a href="$url" target="_blank">Download</a>
    </div>
BUFF_EOF;
    }

    $buff = '<div class="alert-'.$cls.'">'
            . $buff
            . '</div>';

    $buff = do_shortcode($buff);

    return $buff;
}

/**
 * 
 */
function orbisius_s2member_only_downloads_init() {
    add_shortcode('orb_s2member_dl', 'orbisius_s2member_only_downloads_shortcode');
}

/**
 * @package Orbisius Member Only Downloads
 * @since 1.0
 *
 * Searches through posts to see if any matches the REQUEST_URI.
 * Also searches tags
 */
function orbisius_s2member_only_downloads_admin_init() {
    wp_register_style(dirname(__FILE__), plugins_url('/assets/main.css', __FILE__), false);
    wp_enqueue_style(dirname(__FILE__));
}

/**
 * Set up administration
 *
 * @package Orbisius Member Only Downloads
 * @since 0.1
 */
function orbisius_s2member_only_downloads_setup_admin() {
    add_options_page( 'Orbisius Member Only Downloads', 'Orbisius Member Only Downloads', 'manage_options', __FILE__,
            'orbisius_s2member_only_downloads_tools_action' );
    
	// when plugins are show add a settings link near my plugin for a quick access to the settings page.
	add_filter('plugin_action_links', 'orbisius_s2member_only_downloads_add_plugin_settings_link', 10, 2);
}

// Add the ? settings link in Plugins page very good
function orbisius_s2member_only_downloads_add_plugin_settings_link($links, $file) {
    if ($file == plugin_basename(__FILE__)) {
        $prefix = 'options-general.php?page=' . plugin_basename(__FILE__);
        $dashboard_link = "<a href=\"{$prefix}\">" . 'Settings' . '</a>';
        array_unshift($links, $dashboard_link);
    }

    return $links;
}

/**
 * Upload page.
 * Ask the user to upload a file
 * Preview
 * Process
 *
 * @package Permalinks to Category/Permalinks
 * @since 1.0
 */
function orbisius_s2member_only_downloads_tools_action() {
    $msg = '';

    ?>
    <div class="wrap orbisius_s2member_downloads_only_container">
        <h2>Orbisius Member Only Downloads</h2>

        <?php echo $msg; ?>

        <form id="orbisius_s2member_only_downloads_form" class="orbisius_s2member_only_downloads_form" method="post">
            <?php //wp_nonce_field( basename(__FILE__) . '-action', 'orbisius_s2member_only_downloads_nonce' ); ?>
            <div class="updated">
                <p>Currently, the plugin does not require any configuration options.</p>
            </div>
        </form>

		<h2>Support &amp; Premium Plugins</h2>
		<div class="app-alert-notice">
			<p>
			** NOTE: ** We have launched our Club Orbisius site: <a href="http://club.orbisius.com/" target="_blank" title="[new window]">http://club.orbisius.com/</a>
			which offers lots of free and premium plugins, video tutorials and more. The support is handled there as well.
			<br/>Please do NOT use the WordPress forums or other places to seek support.
			</p>
		</div>


        <h2>Usage</h2>
        <p>
            <div>
                Paste direct links to your downloads. They could be links to Amazon S3.
            </div>

            <div>
            <p>
                <h3>1. Requires the user to be logged in order to see the download link </h3>
                Example: [orb_s2member_dl url="http://file.zip"]</p>
            <p>
                <h3>2. Requires (explicitely) the user to be logged in order to see the download link. Can be turned off too by setting req_login=0</h3>
                Example: [orb_s2member_dl req_login=1 url="http://file.zip"]
            </p>
            
            <p>
                <h3>3. Requires the user to be logged and to have at least level 1 membership in order to see the download link</h3>
                Example: [orb_s2member_dl level=1 url="http://wordpress.org/latest.zip"]
            </p>
            </div>
        </p>

        <h2>Want to hear about future plugins? Join our mailing List! (no spam)</h2>
            <p>
                Get the latest news and updates about this and future cool <a href="http://profiles.wordpress.org/lordspace/"
                                                                                target="_blank" title="Opens a page with the pugins we developed. [New Window/Tab]">plugins we develop</a>.
            </p>

            <p>
                <!-- // MAILCHIMP SUBSCRIBE CODE \\ -->
                1) Subscribe by going to <a href="http://eepurl.com/guNzr" target="_blank">http://eepurl.com/guNzr</a>
                <!-- \\ MAILCHIMP SUBSCRIBE CODE // -->
             OR
                2) by using our QR code. [Scan it with your mobile device].<br/>
                <img src="<?php echo plugin_dir_url(__FILE__); ?>/i/guNzr.qr.2.png" alt="" />
            </p>

            <?php if (1) : ?>
            <?php
                $plugin_data = get_plugin_data(__FILE__);

                $app_link = urlencode($plugin_data['PluginURI']);
                $app_title = urlencode($plugin_data['Name']);
                $app_descr = urlencode($plugin_data['Description']);
            ?>
            <!--<h2>Demo</h2>
            <p> 
				<iframe width="560" height="315" src="http://www.youtube.com/embed/BZUVq6ZTv-o" frameborder="0" allowfullscreen></iframe>
				
				<br/>Video Link: <a href="http://www.youtube.com/watch?v=BZUVq6ZTv-o&feature=youtu.be" target="_blank">http://www.youtube.com/watch?v=BZUVq6ZTv-o&feature=youtu.be</a>
			</p>-->
			<h2>Share with friends</h2>
            <p>
                <!-- AddThis Button BEGIN -->
                <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
                    <a class="addthis_button_facebook" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                    <a class="addthis_button_twitter" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                    <a class="addthis_button_google_plusone" g:plusone:count="false" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                    <a class="addthis_button_linkedin" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                    <a class="addthis_button_email" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                    <a class="addthis_button_myspace" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                    <a class="addthis_button_google" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                    <a class="addthis_button_digg" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                    <a class="addthis_button_delicious" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                    <a class="addthis_button_stumbleupon" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                    <a class="addthis_button_tumblr" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                    <a class="addthis_button_favorites" addthis:url="<?php echo $app_link?>" addthis:title="<?php echo $app_title?>" addthis:description="<?php echo $app_descr?>"></a>
                    <a class="addthis_button_compact"></a>
                </div>
                <!-- The JS code is in the footer -->

                <script type="text/javascript">
                var addthis_config = {"data_track_clickback":true};
                var addthis_share = {
                  templates: { twitter: 'Check out {{title}} #wordpress #plugin at {{lurl}} (via @orbisius)' }
                }
                </script>
                <!-- AddThis Button START part2 -->
                <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
                <!-- AddThis Button END part2 -->
            </p>
            <?php endif ?>
        </div>
    <?php
}

/**
* adds some HTML comments in the page so people would know that this plugin powers their site.
*/
function orbisius_s2member_only_downloads_add_plugin_credits() {
    // pull only these vars
    $default_headers = array(
		'Name' => 'Plugin Name',
		'PluginURI' => 'Plugin URI',
	);

    $plugin_data = get_file_data(__FILE__, $default_headers, 'plugin');

    $url = $plugin_data['PluginURI'];
    $name = $plugin_data['Name'];
    
    printf(PHP_EOL . PHP_EOL . '<!-- ' . "Powered by $name | URL: $url " . '-->' . PHP_EOL . PHP_EOL);
}

/**
 */
class orbisius_s2member_only_downloads {
    public $result = null;
    public $target_dir_path; // /var/www/vhosts/domain.com/www/wp-content/themes/Parent-Theme-child-01/

    /**
     * Sets up the params.
     * directories contain trailing slashes.
     * 
     * @param str $parent_theme_basedir
     */
    public function __construct($parent_theme_basedir = '') {
        $all_themes_root = get_theme_root();
        
        $this->parent_theme_basedir = $parent_theme_basedir;
        $this->parent_theme_dir = $all_themes_root . '/' . $parent_theme_basedir . '/';

        $i = 0;

        // Let's create multiple folders in case the script is run multiple times.
        do {
            $i++;
            $target_dir = $all_themes_root . '/' . $parent_theme_basedir . '-child-' . sprintf("%02d", $i) . '/';
        } while (is_dir($target_dir));

        $this->target_dir_path = $target_dir;
        $this->target_base_dirname = dirname($target_dir);

        // this is appended to the new theme's name
        $this->target_name_suffix = 'Child ' . sprintf("%02d", $i);
    }

    /**
     * Loads files from a directory but skips . and ..
     */
    public function load_files($dir) {
        $files = array();
        $all_files = scandir($dir);

        foreach ($all_files as $file) {
            if ($file == '.' || $file == '..') {
				continue;
			}

            $files[] = $file;
        }

        return $files;
    }

    private $info_result = 'n/a';
    private $data_file = '.ht_orbisius_s2member_only_downloads.json';

    /**
     * Checks for correct permissions by trying to create a file in the target dir
     * Also it checks if there are files in the target directory in case it exists.
     */
    public function check_permissions() {
        $target_dir_path = $this->target_dir_path;
        
        if (!is_dir($target_dir_path)) {
            if (!mkdir($target_dir_path, 0775)) {
                throw new Exception("Target child theme directory cannot be created. This is probably a permission error. Cannot continue.");
            }
        } else { // let's see if there will be files in that folder.
            $files = $this->load_files($target_dir_path);

            if (count($files) > 0) {
                throw new Exception("Target folder already exists and has file(s) in it. Cannot continue. Files: ["
                        . join(',', array_slice($files, 0, 5)) . ' ... ]' );
            }
        }

        // test if we can create the folder and then delete it.
        if (!touch($target_dir_path . $this->data_file)) {
            throw new Exception("Target directory is not writable.");
        }
    }
    
    /**
     * Copy some files from the parent theme.
     * @return bool success
     */
    public function copy_main_files() {
        $stats = 0;
        $main_files = array('screenshot.png', 'footer.php', 'license.txt');

        foreach ($main_files as $file) {
            if (!file_exists($this->parent_theme_dir . $file)) {
                continue;
            }

            $stat = copy($this->parent_theme_dir . $file, $this->target_dir_path . $file);
            $stat = intval($stat);
            $stats += $stat;
        }
    }

    /**
     *
     * @return bool success
     * @see http://codex.wordpress.org/Child_Themes
     */
    public function generate_style() {
        global $wp_version;
        
        $plugin_data = get_plugin_data(__FILE__);
        $app_link = $plugin_data['PluginURI'];
        $app_title = $plugin_data['Name'];

        $parent_theme_data = version_compare($wp_version, '3.4', '>=')
                ? wp_get_theme($this->parent_theme_basedir)
                : (object) get_theme_data($this->target_dir_path . 'style.css');

        $buff = '';
        $buff .= "/*\n";
        $buff .= "Theme Name: $parent_theme_data->Name $this->target_name_suffix\n";
        $buff .= "Theme URI: $parent_theme_data->ThemeURI\n";
        $buff .= "Description: $this->target_name_suffix theme for the $parent_theme_data->Name theme\n";
        $buff .= "Author: $parent_theme_data->Author\n";
        $buff .= "Author URI: $parent_theme_data->AuthorURI\n";
        $buff .= "Template: $this->parent_theme_basedir\n";
        $buff .= "Version: $parent_theme_data->Version\n";
        $buff .= "*/\n";

        $buff .= "\n/* Generated by $app_title ($app_link) on " . date('r') . " */ \n\n";

        $buff .= "@import url('../$this->parent_theme_basedir/style.css');\n";
        
        file_put_contents($this->target_dir_path . 'style.css', $buff);

        // RTL langs; make rtl.css to point to the parent file as well
        if (file_exists($this->parent_theme_dir . 'rtl.css')) {
            $rtl_buff .= "/*\n";
            $rtl_buff .= "Theme Name: $parent_theme_data->Name $this->target_name_suffix\n";
            $rtl_buff .= "Template: $this->parent_theme_basedir\n";
            $rtl_buff .= "*/\n";

            $rtl_buff .= "\n/* Generated by $app_title ($app_link) on " . date('r') . " */ \n\n";

            $rtl_buff .= "@import url('../$this->parent_theme_basedir/rtl.css');\n";

            file_put_contents($this->target_dir_path . 'rtl.css', $rtl_buff);
        }

        $this->info_result = "$parent_theme_data->Name " . $this->target_name_suffix . ' has been created in ' . $this->target_dir_path
                . ' based on ' . $parent_theme_data->Name . ' theme.'
                . "\n<br/>Next Go to Appearance > Themes and Activate the new theme.";
    }

    /**
     *
     * @return string
     */
    public function get_details() {
        return $this->info_result;
    }
    
    /**
     *
     * @return bool success
     */
    public function install() {
        $src = dirname(__FILE__) . '/dist/mibew164.zip';
        $target_chat_dir_path = $this->target_dir_path;

        // extract files
        WP_Filesystem(); // we need to call this so we can use the unzip_file function
        $res = unzip_file($src, $target_chat_dir_path); // wp func

        $this->status = is_wp_error($res) ? 0 : 1;
        $this->result = $res;

        if (!$this->status) {
            throw new Exception("There was an error with the mibew zip package.");
        }
    }

    /**
     *
     * @param type $filename
     */
    function log($msg) {
        error_log($msg . "\n", 3, ini_get('error_log'));
    }   
}

class orbisius_s2member_only_downloads_util {
    /**
     * Outputs a message (adds some paragraphs).
     */
    function msg($msg, $status = 0) {
        $msg = join("<br/>\n", (array) $msg);

        if (empty($status)) {
            $cls = 'app-alert-error';
        } elseif ($status == 1) {
            $cls = 'app-alert-success';
        } else {
            $cls = 'app-alert-notice';
        }

        $str = "<div class='$cls'><p>$msg</p></div>";

        return $str;
    }
}
