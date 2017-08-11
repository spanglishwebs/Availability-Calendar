<div class="wrap libersy-wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2><?php _e('Availability Calendar Settings', AC_TEXTDOMAIN) ?></h2>
    
    <?php include 'messages.php' ?>
    
    <form name="form" action="" method="post" enctype="multipart/form-data">
        <h3><?php _e('Settings', AC_TEXTDOMAIN) ?></h3>
        
        <table class="form-table ac-form-table">
            <tbody>
                <tr>
                    <th><label for="<?php echo AC_OPT_ENABLED ?>"><?php _e('Enabled', AC_TEXTDOMAIN) ?></label></th>
                    <td>
                        <select name="<?php echo AC_OPT_ENABLED ?>">
                            <?php
                            foreach(ac_get_enabled_options() as $value => $title):
                                $selected = ac_get_enabled() == $value ? ' selected="selected"' : null;
                            ?>
                            <option value="<?php echo $value ?>"<?php echo $selected ?>><?php echo $title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="<?php echo AC_OPT_ENABLED_CALENDAR ?>"><?php _e('Calendar Enabled', AC_TEXTDOMAIN) ?></label></th>
                    <td>
                        <select name="<?php echo AC_OPT_ENABLED_CALENDAR ?>">
                            <?php
                            foreach(ac_get_enabled_options() as $value => $title):
                                $selected = ac_get_enabled_calendar() == $value ? ' selected="selected"' : null;
                            ?>
                            <option value="<?php echo $value ?>"<?php echo $selected ?>><?php echo $title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="<?php echo AC_OPT_ENABLED_PRICE_TABLE ?>"><?php _e('Price Table Enabled', AC_TEXTDOMAIN) ?></label></th>
                    <td>
                        <select name="<?php echo AC_OPT_ENABLED_PRICE_TABLE ?>">
                            <?php
                            foreach(ac_get_enabled_options() as $value => $title):
                                $selected = ac_get_enabled_price_table() == $value ? ' selected="selected"' : null;
                            ?>
                            <option value="<?php echo $value ?>"<?php echo $selected ?>><?php echo $title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="<?php echo AC_OPT_THEME ?>"><?php _e('Theme', AC_TEXTDOMAIN) ?></label></th>
                    <td>
                        <select name="<?php echo AC_OPT_THEME ?>">
                            <?php
                            foreach(ac_get_theme_options() as $value => $title):
                                $selected = ac_get_theme() == $value ? ' selected="selected"' : null;
                            ?>
                            <option value="<?php echo $value ?>"<?php echo $selected ?>><?php echo $title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="<?php echo AC_OPT_CURRENCY ?>"><?php _e('Default Currency', AC_TEXTDOMAIN) ?></label></th>
                    <td>
                        <select name="<?php echo AC_OPT_CURRENCY ?>">
                            <?php
                            foreach(ac_get_currency_options() as $value => $title):
                                $selected = ac_get_currency() == $value ? ' selected="selected"' : null;
                            ?>
                            <option value="<?php echo $value ?>"<?php echo $selected ?>><?php echo $title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>      
        
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', AC_TEXTDOMAIN) ?>" /></p>
    </form>
</div>
