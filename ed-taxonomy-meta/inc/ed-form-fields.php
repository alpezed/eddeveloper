<?php

class Ex_Tax_Meta_Fields {

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'edci_init' ) );
    }

    /**
     * Function that display the meta text/textarea/editor/image input.
     */
    public function edci_add_meta_input()
    {
        global $category, $taxonomy;

        wp_nonce_field( basename(__FILE__), 'edci_add_meta_nonce' );
        $categoryID = $category;

        $edci_metaList = get_option('ed_meta_taxonomy');

        if(is_object($categoryID)) {
            $categoryID = $categoryID->term_id;
        }

        if(!is_null($edci_metaList) && count($edci_metaList) > 0 && $edci_metaList != '') {

            foreach ($edci_metaList as $inputName => $inputData) {
                $edci_inputType = '';
                $edci_inputTaxonomy = 'category';

                if(is_array($inputData)) {
                    $edci_inputType = $inputData['type'];
                    $edci_inputTaxonomy = $inputData['taxonomy'];
                } else {
                    $edci_inputType = $inputData;
                }

                if( $edci_inputTaxonomy == $taxonomy ) {

                    switch ($edci_inputType) {
                        case "text": ?>
                            <div class="form-field <?php echo sanitize_title('edci_' . $inputName); ?>">
                                <label for="<?php echo edci_sanitize_input($inputName); ?>"><?php echo $inputName; ?></label>
                                <input type="text" id="<?php echo edci_sanitize_input($inputName); ?>" name="<?php echo edci_sanitize_input($inputName); ?>" value="">
                            </div>
                            <?php break;
                        case "image": ?>
                            <div class="form-field <?php echo sanitize_title('edci_' . $inputName); ?>">
                                <label for="<?php echo sanitize_title('edci_' . $inputName); ?>"><?php echo $inputName; ?></label>
                                <div class="form-field cat-img">
                                    <img id="catImg">
                                    <input type="hidden" id="catImg_data" name="<?php echo edci_sanitize_input($inputName); ?>">
                                    <input type="button" id="cat-img-upload-btn" class="button" value="<?php echo esc_html__( 'Add Category Image', 'edci' ); ?>">
                                    <input type="button" id="cat-img-remove-btn" class="button" value="<?php echo esc_html__( 'Remove Category Image', 'edci' ); ?>">
                                    <p class="description">Displaying image to your category page.</p>
                                </div>
                            </div>
                            <?php break;
                        case "textarea": ?>
                            <div class="form-field <?php echo sanitize_title('edci_' . $inputName); ?>">
                                <label for="<?php echo sanitize_title('edci_' . $inputName); ?>"><?php echo $inputName; ?></label>
                                <textarea class="large-text" rows="5" cols="50"
                                          id="<?php echo sanitize_title('edci_' . $inputName); ?>"
                                          name="<?php echo sanitize_title('edci_' . $inputName); ?>"></textarea>
                            </div>
                            <?php break;
                        case "editor": ?>
                            <div class="form-field <?php echo sanitize_title('edci_' . $inputName); ?>">
                                <label
                                    for="<?php echo sanitize_title('edci_' . $inputName); ?>"><?php echo $inputName; ?></label>
                                <?php
                                $content = get_term_meta($categoryID, edci_sanitize_input($inputName), true);
                                $settings = array(
                                    'textarea_rows' => 10,
                                    'textarea_name' => edci_sanitize_input($inputName)
                                );
                                wp_editor($content, edci_sanitize_input($inputName), $settings); ?>
                            </div>
                            <?php break;
                        default: ?>
                            <div class="form-field <?php echo sanitize_title('edci_' . $inputName); ?>">
                                <label
                                    for="<?php echo edci_sanitize_input($inputName); ?>"><?php echo $inputName; ?></label>
                                <input type="text" id="<?php echo edci_sanitize_input($inputName); ?>"
                                       name="<?php echo edci_sanitize_input($inputName); ?>" value="">
                            </div>
                            <?php
                    }
                }
            }
        }
    }

    /**
     * Function that Edit the meta text/textarea/editor/image input.
     */
    public function edci_edit_meta_input( $term )
    {
        global $category, $taxonomy;
        wp_nonce_field( basename(__FILE__), 'edci_add_meta_nonce' );
        $categoryID = $category;

        $edci_metaList = get_option('ed_meta_taxonomy');

        if(is_object($categoryID)) {
            $categoryID = $categoryID->term_id;
        }

        if(!is_null($edci_metaList) && count($edci_metaList) > 0 && $edci_metaList != '') {

            foreach ($edci_metaList as $inputName => $inputData) {

                $cat_meta = get_term_meta( $term->term_id, edci_sanitize_input($inputName), true );

                $edci_inputType = '';
                $edci_inputTaxonomy = 'category';

                if(is_array($inputData)) {
                    $edci_inputType = $inputData['type'];
                    $edci_inputTaxonomy = $inputData['taxonomy'];
                } else {
                    $edci_inputType = $inputData;
                }

                if( $edci_inputTaxonomy == $taxonomy ) {

                    switch ($edci_inputType) {
                        case "text": ?>
                            <tr class="form-field <?php echo sanitize_title('edci_' . $inputName); ?>">
                                <th><label for="<?php echo edci_sanitize_input($inputName); ?>"><?php echo $inputName; ?></label></th>
                                <td><input type="text" id="<?php echo edci_sanitize_input($inputName); ?>" name="<?php echo edci_sanitize_input($inputName); ?>" value="<?php echo $cat_meta; ?>"></td>
                            </tr>
                            <?php break;
                        case "image": ?>
                            <tr class="form-field <?php echo sanitize_title('edci_' . $inputName); ?>">
                                <th><label for="<?php echo sanitize_title('edci_' . $inputName); ?>"><?php echo $inputName; ?></label></th>
                                <td>
                                    <div class="form-field cat-img">
                                        <?php
                                        $imgID = $cat_meta;
                                        $srcImg = wp_get_attachment_image_src($imgID);
                                        echo '<img src="' . $srcImg[0] . '" id="catImg" >';
                                        ?>
                                        <!--<img id="catImg">-->
                                        <input type="hidden" id="catImg_data" name="<?php echo edci_sanitize_input($inputName); ?>" value="<?php echo $cat_meta; ?>">
                                        <input type="button" id="cat-img-upload-btn" class="button" value="<?php echo esc_html__( 'Add Category Image', 'edci' ); ?>">
                                        <input type="button" id="cat-img-remove-btn" class="button" value="<?php echo esc_html__( 'Remove Category Image', 'edci' ); ?>">
                                        <p class="description">Displaying image to your category page.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php break;
                        case "textarea": ?>
                            <tr class="form-field <?php echo sanitize_title('edci_' . $inputName); ?>">
                                <th><label for="<?php echo sanitize_title('edci_' . $inputName); ?>"><?php echo $inputName; ?></label></th>
                                <td><textarea class="large-text" rows="5" cols="50"
                                              id="<?php echo sanitize_title('edci_' . $inputName); ?>"
                                              name="<?php echo sanitize_title('edci_' . $inputName); ?>"></textarea></td>
                            </tr>
                            <?php break;
                        case "editor": ?>
                            <tr class="form-field <?php echo sanitize_title('edci_' . $inputName); ?>">
                                <th><label for="<?php echo sanitize_title('edci_' . $inputName); ?>"><?php echo $inputName; ?></label></th>
                                <td><?php
                                    $content = get_term_meta($categoryID, $inputName, true);
                                    $settings = array(
                                        'textarea_rows' => 10,
                                        'textarea_name' => 'edci_' . sanitize_title($inputName)
                                    );
                                    wp_editor($content, 'edci_' . sanitize_title($inputName), $settings); ?></td>
                            </tr>
                            <?php break;
                        default: ?>
                            <tr class="form-field <?php echo sanitize_title('edci_' . $inputName); ?>">
                                <th><label
                                        for="<?php echo sanitize_title('edci_' . $inputName); ?>"><?php echo $inputName; ?></label></th>
                                <td><input type="text" id="<?php echo sanitize_title('edci_' . $inputName); ?>"
                                           name="<?php echo sanitize_title('edci_' . $inputName); ?>" value=""></td>
                            </tr>
                            <?php
                    }
                }
            }
        }
    }

    public function edci_init()
    {
        add_action( 'create_term', array( $this, 'edci_save_category_meta' ) );
        add_action( 'edit_term', array( $this, 'edci_save_category_meta' ) );

        $edci_taxonomies = get_taxonomies('', 'names');

        if(is_array($edci_taxonomies)) {
            foreach ( $edci_taxonomies as $edci_taxonomy ) {
                add_action( $edci_taxonomy . '_add_form_fields', array( $this, 'edci_add_meta_input' ) );
                add_action( $edci_taxonomy . '_edit_form_fields', array( $this, 'edci_edit_meta_input' ) );
            }
        }
    }

}

