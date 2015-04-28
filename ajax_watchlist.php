<?php

    if (Params::getParam('id') != '') {
        $id    = Params::getParam('id');
        $count = 0;

        if ( osc_is_web_user_logged_in() ) {
            //check if the item is not already in the watchlist
            $conn   = getConnection();
            $detail = $conn->osc_dbFetchResult("SELECT * FROM %st_item_watchlist WHERE fk_i_item_id = %d and fk_i_user_id = %d", DB_TABLE_PREFIX, $id, osc_logged_user_id());

            //If nothing returned then we can process
            if (!isset($detail['fk_i_item_id'])) {
                $conn = getConnection();
                $conn->osc_dbExec("INSERT INTO %st_item_watchlist (fk_i_item_id, fk_i_user_id) VALUES (%d, '%d')", DB_TABLE_PREFIX, $id, osc_logged_user_id());
                ?>
                
                    <a class="btn-success btn-success btn-sm" href="<?php echo osc_base_url(true); ?>?page=custom&file=watchlist/watchlist.php">
                        <i class="fa fa-heart fa-fw"></i><?php _e('View your watchlist', 'watchlist') ?>
                    </a>
                
                <?php
            } else {
                //Already in watchlist !
                echo '<a class="btn-success btn-success btn-sm" href="' . osc_base_url(true) . '?page=custom&file=watchlist/watchlist.php"><i class="fa fa-heart fa-fw"></i>' . __('View your watchlist', 'watchlist') . '</a>';
            }
        } else {
            //error user is not login in
            echo '<a class="btn btn-warning btn-sm" href="' . osc_user_login_url() . '"><i class="fa fa-heart fa-fw"></i>' . __('Please login', 'watchlist') . '</a>';
        }
    }

?>