<div class="wrap libersy-wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2><?php _e('Availability Calendar Settings', AC_TEXTDOMAIN) ?></h2>
    
    <?php include 'messages.php' ?>
    
    <form name="form" action="" method="post" enctype="multipart/form-data">
        <table class="form-table ac-form-table">
            <thead>
                <tr>
                    <th><?php _e('Post type', AC_TEXTDOMAIN) ?></th>
                    <th><?php _e('Admin settings', AC_TEXTDOMAIN) ?></th>
                    <th><?php _e('Front-end settings', AC_TEXTDOMAIN) ?></th>
                    <th><?php _e('Availability Options', AC_TEXTDOMAIN) ?></th>
                    <th><?php _e('Default Option', AC_TEXTDOMAIN) ?></th>
                    <th><?php _e('Price Table', AC_TEXTDOMAIN) ?></th>
                    <th><?php _e('Season Options', AC_TEXTDOMAIN) ?></th>
                    <th><?php _e('Duration Options', AC_TEXTDOMAIN) ?></th>
                    <th><?php _e('Default Minimal Stay', AC_TEXTDOMAIN) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach(ac_get_post_types() as $ptid => $ptname): ?>
                <tr>
                    <th><?php echo $ptname ?></th>
                    <td valign="top"> 
                        <select name="<?php echo AC_OPT_POST_TYPE_OPTIONS ?>[<?php echo $ptid ?>][admin]">
                            <?php
                            foreach(ac_get_display_options() as $value => $title):
                                $selected = ac_get_post_type_option($ptid, 'admin') == $value ? ' selected="selected"' : null;
                            ?>
                            <option value="<?php echo $value ?>"<?php echo $selected ?>><?php echo $title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td valign="top">
                        <select name="<?php echo AC_OPT_POST_TYPE_OPTIONS ?>[<?php echo $ptid ?>][front]">
                            <?php
                            foreach(ac_get_display_options() as $value => $title):
                                $selected = ac_get_post_type_option($ptid, 'front') == $value ? ' selected="selected"' : null;
                            ?>
                            <option value="<?php echo $value ?>"<?php echo $selected ?>><?php echo $title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <?php
                        foreach(ac_get_availability_options() as $aid => $ao):
                            $selected = ac_get_post_type_option($ptid, $aid) ? ' checked="checked"' : null;
                        ?>
                            <label><input type="checkbox" name="<?php echo AC_OPT_POST_TYPE_OPTIONS ?>[<?php echo $ptid ?>][<?php echo $aid ?>]" value="1"<?php echo $selected?>/> <?php echo $ao['name'] ?></label><br />
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <select name="<?php echo AC_OPT_POST_TYPE_OPTIONS ?>[<?php echo $ptid ?>][default]">
                            <option value="0"><?php _e('Select default', AC_TEXTDOMAIN) ?></option>
                        <?php
                        foreach(ac_get_availability_options() as $aid => $ao):
                            $selected = ac_get_post_type_option($ptid, 'default') == $aid ? ' selected="selected"' : null;
                        ?>
                            <option value="<?php echo $aid ?>"<?php echo $selected ?>><?php echo $ao['name'] ?></option>
                        <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name="<?php echo AC_OPT_POST_TYPE_OPTIONS ?>[<?php echo $ptid ?>][price_table]">
                            <?php
                            foreach(ac_get_enabled_options() as $value => $title):
                                $selected = ac_get_post_type_option($ptid, 'price_table') == $value ? ' selected="selected"' : null;
                            ?>
                            <option value="<?php echo $value ?>"<?php echo $selected ?>><?php echo $title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <?php
                        $seasons = ac_get_post_type_option($ptid, 'season', array());
                        foreach(ac_get_seasons_options() as $aid => $ao):
                            $checked = isset($seasons[$aid]) && $seasons[$aid] ? ' checked="checked"' : null;
                        ?>
                            <label><input type="checkbox" name="<?php echo AC_OPT_POST_TYPE_OPTIONS ?>[<?php echo $ptid ?>][season][<?php echo $aid ?>]" value="1"<?php echo $checked?>/> <?php echo $ao['name'] ?></label><br />
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php
                        $durations = ac_get_post_type_option($ptid, 'duration', array());
                        foreach(ac_get_duration_options() as $aid => $ao):
                            $checked = isset($durations[$aid]) && $durations[$aid] ? ' checked="checked"' : null;
                        ?>
                            <label><input type="checkbox" name="<?php echo AC_OPT_POST_TYPE_OPTIONS ?>[<?php echo $ptid ?>][duration][<?php echo $aid ?>]" value="1"<?php echo $checked?>/> <?php echo $ao['name'] ?></label><br />
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <select name="<?php echo AC_OPT_POST_TYPE_OPTIONS ?>[<?php echo $ptid ?>][default_minimal_stay]">
                            <option value="0"><?php _e('Select default', AC_TEXTDOMAIN) ?></option>
                        <?php
                        
                        foreach(ac_get_duration_options() as $aid => $ao):
                            if (!isset($ao['minimal_stay']) || !$ao['minimal_stay']) {
                                continue;
                            }
                            
                            $selected = ac_get_post_type_option($ptid, 'default_minimal_stay') == $aid ? ' selected="selected"' : null;
                        ?>
                            <option value="<?php echo $aid ?>"<?php echo $selected ?>><?php echo $ao['name'] ?></option>
                        <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <?php endforeach; ?>
                
            </tbody>
        </table>
        
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', AC_TEXTDOMAIN) ?>" /></p>
    </form>
    
</div>
