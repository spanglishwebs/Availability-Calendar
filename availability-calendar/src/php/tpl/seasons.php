<div class="wrap libersy-wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2><?php _e('Seasons Settings', AC_TEXTDOMAIN) ?></h2>
    
    <?php include 'messages.php' ?>
    
    <form name="form" action="" method="post" enctype="multipart/form-data">
        <input id="ac_autoincrement" type="hidden" name="<?php echo AC_OPT_SEASONS_OPTIONS ?>[auto_increment]" value="<?php echo ac_get_seasons_options_auto_increment() ?>" />

        <table class="form-table ac-form-table">
            <thead>
                <tr>
                    <th><?php _e('Actions', AC_TEXTDOMAIN) ?></th>
                    <th><?php _e('Name', AC_TEXTDOMAIN) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach(ac_get_seasons_options() as $aid => $ao): ?>
                <tr>
                    <td>
                        <label><input type="checkbox" name="<?php echo AC_OPT_SEASONS_OPTIONS ?>[<?php echo $aid ?>][remove]" value="1" />
                        <?php _e('Remove', AC_TEXTDOMAIN) ?></label>
                    </td>
                    <td>
                        <input type="text" name="<?php echo AC_OPT_SEASONS_OPTIONS ?>[<?php echo $aid ?>][name]" value="<?php echo $ao['name'] ?>" />
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr id="ac_availability_options_table_last_tr">
                    <td><a class="ac-add-seasons" href="#"><?php _e('Add new', AC_TEXTDOMAIN) ?></a></td>
                    <td colspan="3"></td>
                </tr>
            </tbody>
        </table>
        
        <script type="text/javascript">
            var AC_AUTOINCREMENT = <?php echo ac_get_seasons_options_auto_increment() ?>;
        </script>     
        
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', AC_TEXTDOMAIN) ?>" /></p>
    </form>
    
</div>
