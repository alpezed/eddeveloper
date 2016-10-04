<?php

class Ed_Taxonomy_Meta {
    
    const LANG = "ed_tm";

    public function __construct()
    {
        add_action( 'wp_before_admin_bar_render', array( $this, 'ed_category_add_link' ) );
        add_action( 'admin_menu', array( $this, 'ed_category_image_options' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'edci_enqueue_scripts' ) );
    }

    public function ed_category_add_link() {
        global $wp_admin_bar;
        $wp_admin_bar->add_menu( array(
            'id' => 'tax_meta_admin_bar',
            'title' => '<span class="ab-icon"></span>' . __( 'Ed Taxonomy Meta', self::LANG ),
            'href' => admin_url() . 'options-general.php?page=edci_taxonomy_meta_options'
        ) );
    }

    public function ed_category_image_options() {
        add_submenu_page(
            'options-general.php',
            'Taxonomy Meta',
            'Taxonomy Meta',
            'manage_options',
            'edci_taxonomy_meta_options',
            array( $this, 'edci_category_image_admin_settings' )
        );
    }

    public function edci_category_image_admin_settings() {

        $edci_config_opt = get_option('ed_meta_taxonomy');

        if(is_null($edci_config_opt) || $edci_config_opt == "") {
            $edci_config_opt = array();
        }

        if( isset($_POST['action']) && $_POST['action'] == "add" ) {

            $new_meta_name = $_POST['edci_meta_name'];
            $new_meta_type = $_POST['edci_meta_type'];
            $new_meta_tax = $_POST['edci_meta_taxonomy'];

            $edci_config_opt[$new_meta_name] = array( 'type' => $new_meta_type, 'taxonomy' => $new_meta_tax );

            update_option( 'ed_meta_taxonomy', $edci_config_opt );

        } else if( isset($_POST['action']) && $_POST['action'] == "delete" ) {

            $deleteMetaName = $_POST['remove_meta'];
            unset($edci_config_opt[$deleteMetaName]);

            update_option( 'ed_meta_taxonomy', $edci_config_opt );

        }

        ?>

        <div class="wrap">

            <h1><?php _e( 'Taxonomy Meta', self::LANG ); ?></h1>

            <div style="padding-bottom: 10px"></div>

            <?php

            if ( isset( $_REQUEST['cat_img_option_submit'] ) ) {
                echo '<div id="message" class="updated notice is-dismissible"><p><strong>Meta Added.</strong></p> <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
            }

            if ( isset( $_REQUEST['delete_meta'] ) ) {
                echo '<div id="message" class="updated notice is-dismissible"><p><strong>Meta Deleted.</strong></p> <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
            }

            if( !current_user_can( 'manage_options' ) ) {
                echo '<div id="message" class="error"><p><strong>There was an error submitting the meta, or you do not have proper permission.</strong></p></div>';
            }

            ?>

            <table class="widefat">
                <thead>
                <tr>
                    <th class="row-title" colspan="4"><?php esc_attr_e( 'Meta List', self::LANG ); ?></th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td class="row-title"><label><?php esc_attr_e( 'Meta Name', self::LANG ); ?></label></td>
                    <td class="row-title"><label><?php esc_attr_e( 'Meta Type', self::LANG ); ?></label></td>
                    <td class="row-title"><label><?php esc_attr_e( 'Meta Taxonomy', self::LANG ); ?></label></td>
                    <td class="row-title"><label><?php esc_attr_e( 'Action', self::LANG ); ?></label></td>
                </tr>
                <?php
                $i = 0;
                foreach ($edci_config_opt as $name => $data) {
                    $type = '';
                    $taxonomy = 'category';
                    if(is_array($data)) {
                        $type = $data['type'];
                        $taxonomy = $data['taxonomy'];
                    } else {
                        $type = array();
                    }
                    ?>
                    <tr class="<?php echo( $i % 2 == 0 ) ? 'alternate' : '' ?>">
                        <td class="titledesc"><?php esc_attr_e($name, self::LANG); ?></td>
                        <td class="forminp"><?php esc_attr_e($type, self::LANG); ?></td>
                        <td class="forminp"><?php esc_attr_e($taxonomy, self::LANG); ?></td>
                        <td class="forminp">
                            <form method="post">
                                <input type="hidden" name="action" value="delete" />
                                <input type="hidden" name="remove_meta" value="<?php esc_attr_e($name, self::LANG); ?>" />
                                <input class="button-secondary" name="delete_meta" type="submit" value="<?php esc_attr_e( 'Delete meta', self::LANG ) ?>" />
                            </form>
                        </td>
                    </tr>
                    <?php
                    $i++;
                } ?>
                <!--<tr>
                    <td colspan="4"></td>
                </tr>-->

                </tbody>
            </table>

            <div class="form">
                <form method="post" action="">
                    <table class="form-table">

                        <tr>
                            <th><label for="edci_meta_name">Meta Name</label></th>
                            <td>
                                <input type="text" name="edci_meta_name" id="edci_meta_name" class="regular-text" value="">
                                <p class="description">The name of your term meta.</p>
                            </td>
                        </tr>

                        <tr>
                            <th><label for="edci_meta_type">Meta Type</label></th>
                            <td>
                                <select name="edci_meta_type" id="edci_meta_type">
                                    <option value="text"><?php echo esc_html_e( 'Text', self::LANG ) ?></option>
                                    <option value="textarea"><?php echo esc_html_e( 'Text area', self::LANG ) ?></option>
                                    <option value="editor"><?php echo esc_html_e( 'WYSIWYG', self::LANG ) ?></option>
                                    <option value="image"><?php echo esc_html_e( 'Image', self::LANG ); ?></option>
                                </select>
                                <p class="description">Select what kind of meta type you want to use.</p>
                            </td>
                        </tr>

                        <tr>
                            <th><label for="edci_meta_taxonomy">Meta Taxonomy</label></th>
                            <td>
                                <select name="edci_meta_taxonomy" id="edci_meta_taxonomy">
                                    <?php
                                    $taxonomies = get_taxonomies( array( 'public' => true ), 'names' );
                                    foreach ( $taxonomies as $taxonomy ) {
                                        echo '<option value="'.$taxonomy.'">'.$taxonomy.'</option>';
                                    } ?>
                                </select>
                                <p class="description">Select which taxonomy you want to use your new meta field.</p>
                            </td>
                        </tr>

                    </table>

                    <p>
                        <input type="hidden" name="action" value="add">
                        <input class="button-primary" type="submit" name="cat_img_option_submit" value="+ Add meta" />
                    </p>

                </form>
            </div>
        </div>

        <?php

    }

    public function edci_enqueue_scripts() {
        global $pagenow;

        wp_enqueue_style( 'edci-script', plugins_url( '../css/edci-style.css', __FILE__ ));

        if( $pagenow === 'edit-tags.php' || $pagenow === 'term.php' ) {
            wp_enqueue_media();
            wp_enqueue_script( 'edci-script', plugins_url( '../js/edci-script.js', __FILE__ ), array( 'jquery' ), '08042016', true );

            $tag_ID = absint( $_REQUEST['tag_ID'] );
            //$tag    = get_term( $tag_ID, 'category', OBJECT, 'edit' );
            $cat = get_category( $tag_ID );

            wp_localize_script( 'edci-script', 'CAT_IMG_UPLOAD', array(
                'catImgData' => get_term_meta( $cat->term_id, 'edci_category_image', true )
            ) );
        }
    }

    public function edci_save_category_meta( $term_id ) {

        $edci_metaList = get_option('ed_meta_taxonomy');

        if( ! isset( $_POST['edci_add_meta_nonce'] ) && ! wp_verify_nonce( $_POST['edci_add_meta_nonce'], basename(__FILE__) ) ) {
            return;
        }

        foreach ($edci_metaList as $inputName => $inputData) {
            $inputName = edci_sanitize_input($inputName);
            update_term_meta( $term_id, $inputName, sanitize_text_field( $_POST[$inputName] ) );
        }

    }

}


