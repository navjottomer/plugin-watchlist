<?php 
    $i_userId = osc_logged_user_id();
	if(Params::getParam('delete') != '' && osc_is_web_user_logged_in()){
		delete_item(Params::getParam('delete'), $i_userId);
	}

    $itemsPerPage = (Params::getParam('itemsPerPage') != '') ? Params::getParam('itemsPerPage') : 5;
    $iPage        = (Params::getParam('iPage') != '') ? Params::getParam('iPage') : 1;

    Search::newInstance()->addConditions(sprintf("%st_item_watchlist.fk_i_user_id = %d", DB_TABLE_PREFIX, $i_userId));
    Search::newInstance()->addConditions(sprintf("%st_item_watchlist.fk_i_item_id = %st_item.pk_i_id", DB_TABLE_PREFIX, DB_TABLE_PREFIX));
    Search::newInstance()->addTable(sprintf("%st_item_watchlist", DB_TABLE_PREFIX));
    Search::newInstance()->page($iPage -1, $itemsPerPage);

    $aItems      = Search::newInstance()->doSearch();
    $iTotalItems = Search::newInstance()->count();
    $iNumPages   = ceil($iTotalItems / $itemsPerPage) ;

    View::newInstance()->_exportVariableToView('items', $aItems);
    View::newInstance()->_exportVariableToView('search_total_pages', $iNumPages);
    View::newInstance()->_exportVariableToView('search_page', $iPage -1) ;

	// delete item from watchlist
	function delete_item($item, $uid){
		$conn = getConnection();
		$conn->osc_dbExec("DELETE FROM %st_item_watchlist WHERE fk_i_item_id = %d AND fk_i_user_id = %d LIMIT 1", DB_TABLE_PREFIX , $item, $uid);
	}
?>
<?php 
$adminoptions = false; ?>
	<div class="panel panel-success row">
		<div class="panel-heading">
			<strong><?php _e('Your watchlist', 'watchlist'); ?></strong>
		</div>
		<div class="content user_account panel-body">
			<div id="sidebar" class="col-md-3">
				<?php echo tfc_private_user_menu() ; ?>
				<div class="user-dashboard-widget"><?php osc_show_widgets('user-dashboard'); ?></div>
			</div>
			<div class="col-md-9">
				<legend><?php _e('Your watchlist items', 'tuffclassified'); ?></legend>
				<?php if(osc_count_items() == 0) { ?>
				<h3><?php _e('You don\'t have any items yet', 'watchlist'); ?></h3>
				<?php } else { ?>
				<?php while(osc_has_items()) { ?>
				<div class="item col-md-12" >
					<?php include tfc_path().'ad-loop.php';?>
					<p align="right"><a class="delete btn btn-danger btn-xs" onclick="javascript:return confirm('<?php _e('This action can not be undone. Are you sure you want to continue?', 'watchlist'); ?>')" href="<?php echo osc_render_file_url(osc_plugin_folder(__FILE__) . 'watchlist.php') . '&delete=' . osc_item_id(); ?>" ><?php _e('Delete from watchlist', 'watchlist'); ?></a></p>
				</div>
				<?php } ?>
				<?php } ?>
				<div class="pagination col-md-6 col-md-offset-5" >
					<?php echo tfc_pagination_items(array('url' => osc_render_file_url(osc_plugin_folder(__FILE__) . 'watchlist.php') . '&iPage={PAGE}')); ?>
				</div>
			</div>
		</div>
	</div>