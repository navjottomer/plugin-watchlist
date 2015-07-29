<?php
/*
  Plugin Name: Watchlist
  Plugin URI: https://github.com/osclass/osclass-plugins/tree/watchlist/watchlist
  Description: This plugin add possibility for user to watch items. Customised to work with Tuffclassified theme
  Version: 3.0
  Author: Richard Martin (keny) & Osclass
  Author URI: http://www.proodi.com
  Author Email: keny10@gmail.com
  Short Name: WatchList
 */

    define('WATCHLIST_VERSION', '3.0');

    function watchlist() {
        echo '<span class="watchlist" id="' . osc_item_id() . '"><a class="btn btn-default btn-sm" href="javascript://">';
        echo '<i class="fa fa-heart fa-fw"></i>'.__('Watchlist', 'watchlist');
        echo '</a></span>';
    osc_add_hook('footer_scripts_loaded', 'watchlist_scripts_loaded');
	osc_add_hook('footer_scripts_loaded','watchlist_js');
    }
	function watchlist_js(){ ?>
	<script>
	$(document).ready(function($) {
    $(".watchlist").click(function() {
        var id = $(this).attr("id");
        var dataString = 'id='+ id ;
        var parent = $(this);

        $(this).fadeOut(300);
        $.ajax({
            type: "POST",
            url: watchlist_url,
            data: dataString,
            cache: false,

            success: function(html) {
            parent.html(html);
            parent.fadeIn(300);
            }
        });
    });
});</script>
	<?php }

    function watchlist_user_menu() {
        echo '<li class="" ><a href="' . osc_render_file_url(osc_plugin_folder(__FILE__) . 'watchlist.php') . '" >' . __('Watchlist', 'watchlist') . '</a></li>';
    }

    function watchlist_call_after_install() {
        $conn = getConnection();
        $path = osc_plugin_resource('watchlist/struct.sql');
        $sql  = file_get_contents($path);
        $conn->osc_dbImportSQL($sql);
    }

    function watchlist_call_after_uninstall() {
        $conn = getConnection();
        $conn->osc_dbExec('DROP TABLE %st_item_watchlist', DB_TABLE_PREFIX);
    }

    function watchlist_scripts_loaded() {
        echo '<!-- Watchlist js -->';
        echo '<script type="text/javascript">';
        echo 'var watchlist_url = "' . osc_ajax_plugin_url('watchlist/ajax_watchlist.php') . '";';
        echo '</script>';
        echo '<!-- Watchlist js end -->';
    }

    function watchlist_delete_item($item) {
        $conn = getConnection();
        $conn->osc_dbExec("DELETE FROM %st_item_watchlist WHERE fk_i_item_id = '$item'", DB_TABLE_PREFIX);
    }

    function watchlist_help() {
        osc_admin_render_plugin(osc_plugin_path(dirname(__FILE__)) . '/help.php');
    }

    // This is needed in order to be able to activate the plugin
    osc_register_plugin(osc_plugin_path(__FILE__), 'watchlist_call_after_install');

    // This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
    osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'watchlist_call_after_uninstall');

    // This is a hack to show a Configure link at plugins table (you could also use some other hook to show a custom option panel)
    osc_add_hook(osc_plugin_path(__FILE__) . '_configure', 'watchlist_help');

    // Add link in user menu page
    osc_add_hook('user_menu', 'watchlist_user_menu');

    // add javascript

    //Delete item
    osc_add_hook('delete_item', 'watchlist_delete_item');

?>
