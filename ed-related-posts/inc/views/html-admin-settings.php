<?php
/**
 * Admin View: Settings
 */

if( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<div class="wrap">

    <h2>Related Posts Option</h2>

    <?php self::show_message(); ?>

    <div class="form">
        <form method="post" action="admin-post.php">

            <input type="hidden" name="action" value="ed_rp_save_opts">
            <?php wp_nonce_field( 'ed_rp_options_verify' ); ?>

            <table class="form-table">

                <tr>
                    <th><label for="ed_rp_title"><?php _e( 'Related Post Title', 'edrp' ); ?></label></th>
                    <td>
                        <input type="text" name="ed_rp_title" id="ed_rp_title" class="all-options" value="<?php echo $edrp[ 'ed_rp_title' ]; ?>">
                        <p class="description">Title for related posts (Default: Related Posts)</p>
                    </td>
                </tr>

                <tr>
                    <th><label for="ed_rp_display"><?php _e( 'Enable Related Post', 'edrp' ); ?></label></th>
                    <td>
                        <input type="checkbox" name="ed_rp_display" id="ed_rp_display" value="true" <?php checked( $edrp[ 'ed_rp_display' ], 'true' ); ?>>
                        <span class="description">Check to enable related posts on your website</span>
                    </td>
                </tr>

                <tr>
                    <th><label for="ed_rp_show_thumb"><?php _e( 'Show Thumbnail', 'edrp' ); ?></label></th>
                    <td>
                        <input type="checkbox" name="ed_rp_show_thumb" id="ed_rp_show_thumb" value="true" <?php checked( $edrp[ 'ed_rp_show_thumb' ], 'true' ); ?>>
                        <span class="description">Check to display thumbnail on related posts display</span>
                    </td>
                </tr>

                <tr>
                    <th><label for="ed_rp_layout"><?php _e( 'Column Layout', 'edrp' ); ?></label></th>
                    <td>
                        <select name="ed_rp_layout" id="ed_rp_layout">
                            <option value="2" <?php selected( $edrp[ 'ed_rp_layout' ], '2' ) ?>>2 Columns</option>
                            <option value="3" <?php selected( $edrp[ 'ed_rp_layout' ], '3' ) ?>>3 Columns</option>
                            <option value="4" <?php selected( $edrp[ 'ed_rp_layout' ], '4' ) ?>>4 Columns</option>
                        </select>
                        <p class="description">Number of columns to display (default 2)</p>
                    </td>
                </tr>

                <tr>
                    <th><label for="ed_rp_count"><?php _e( 'Num. of Related Posts', 'edrp' ) ?></label></th>
                    <td>
                        <select name="ed_rp_count" id="ed_rp_count">
                            <?php
                            for ( $i = 1; $i <= 10; $i++ ) {
                                ?>
                                <option value="<?php echo $i; ?>" <?php selected( $edrp[ 'ed_rp_count' ], $i ) ?>><?php echo $i; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <p class="description">Number of related posts to display</p>
                    </td>
                </tr>

            </table>

            <p>
                <input class="button-primary" type="submit" name="ed_rp_option_submit" value="<?php _e( 'Update Option', 'edrp' ); ?>" />
            </p>

        </form>
    </div>

</div>
