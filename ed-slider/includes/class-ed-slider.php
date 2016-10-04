<?php

if( !class_exists( 'Ed_Slider' ) ) {
    class Ed_Slider
    {

        const LANG = 'esl';

        private $esl_metaboxes;

        public function __construct()
        {

            $this->esl_metaboxes = array(
                'esl_appearance_metaboxes',
                'esl_animation_metaboxes',
                'esl_navigation_metaboxes',
                'esl_slider_metabox'
            );
            foreach ($this->esl_metaboxes as $esl_metabox) {
                add_action('add_meta_boxes', array($this, $esl_metabox));
            }
            add_action('save_post', array($this, 'esl_meta_field_save'));
            add_action('admin_enqueue_scripts', array($this, 'esl_admin_scripts'));
            add_filter('plugin_row_meta', array($this, 'esl_filter_plugin_row_meta'), 10, 2);

        }

        public function esl_filter_plugin_row_meta( $links, $file )
        {
            if ( $file == ESL_PLUGIN_BASENAME ) {
                $row_meta = array(
                    'setting' => '<a href="'. admin_url() .'edit.php?page=ed_slider_settings">Slider Setting</a>'
                );
                return array_merge( $links, $row_meta );
            }

            return (array) $links;
        }

        /*
         * Appearance
        */
        public function esl_appearance_metaboxes()
        {
            add_meta_box(
                'ed_slider_layout',
                __('Layout', self::LANG),
                array($this, 'esl_slider_apperance_callback'),
                'ed_slider',
                'side',
                'high'
            );
        }


        public function esl_slider_apperance_callback()
        {
            wp_nonce_field(basename(__FILE__), 'esl_appearance_nonce_field');
            ?>

            <div class="esl-opt">
                <div id="template-select" class="templates-grid">
                    <div class="esl-template">
                        <input id="default" type="radio" name="template" value="default" checked="checked">
                        <label style="background-position: -4px -100px ;" for="default"></label>
                    </div>
                    <div class="esl-template">
                        <input id="gallery" type="radio" name="template" value="gallery">
                        <label style="background-position: -93px -100px;" for="gallery"></label>
                    </div>
                    <div class="esl-template">
                        <input id="gallery_vertical_fade" type="radio" name="template" value="gallery_vertical_fade">
                        <label style="background-position: -4px -165px ;" for="gallery_vertical_fade"></label>
                    </div>
                    <div class="esl-template">
                        <input id="content_slider" type="radio" name="template" value="content_slider">
                        <label style="background-position: -93px -165px ;" for="content_slider"></label>
                    </div>
                    <div class="esl-template">
                        <input id="simple_vertical" type="radio" name="template" value="simple_vertical">
                        <label style="background-position: -4px -230px ;" for="simple_vertical"></label>
                    </div>
                    <div class="esl-template">
                        <input id="gallery_with_thumbs_text" type="radio" name="template"
                               value="gallery_with_thumbs_text">
                        <label style="background-position: -93px -230px ;" for="gallery_with_thumbs_text"></label>
                    </div>
                    <div class="esl-template">
                        <input id="visible_nearby_zoom" type="radio" name="template" value="visible_nearby_zoom">
                        <label style="background-position: -4px -295px ;" for="visible_nearby_zoom"></label>
                    </div>
                    <div class="esl-template">
                        <input id="visible_nearby_simple" type="radio" name="template" value="visible_nearby_simple">
                        <label style="background-position: -93px -295px ;" for="visible_nearby_simple"></label>
                    </div>
                    <div class="esl-template">
                        <input id="gallery_thumbs_grid" type="radio" name="template" value="gallery_thumbs_grid">
                        <label style="background-position: -4px -360px ;" for="gallery_thumbs_grid"></label>
                    </div>
                    <div class="esl-template">
                        <input id="slider_rs_home" type="radio" name="template" value="slider_rs_home">
                        <label style="background-position: -93px -360px ;" for="slider_rs_home"></label>
                    </div>
                    <div class="esl-template">
                        <input id="slider_in_laptop" type="radio" name="template" value="slider_in_laptop">
                        <label style="background-position: -4px -425px ;" for="slider_in_laptop"></label>
                    </div>
                    <div class="esl-template">
                        <input id="two_at_once" type="radio" name="template" value="two_at_once">
                        <label style="background-position: -93px -425px ;" for="two_at_once"></label>
                    </div>
                </div>
            </div>
            <hr>
            <div class="esl-opt">
                <div class="esl-template-title">Skin</div>
                <select id="skin-select">
                    <option value="eslUni" selected="selected"><?php _e('Universal', self::LANG); ?></option>
                    <option value="eslDefault"><?php _e('Dark-default', self::LANG); ?></option>
                    <option value="eslDefaultInv"><?php _e('Light', self::LANG); ?></option>
                    <option value="eslMinW"><?php _e('With controls in corner', self::LANG); ?></option>
                </select>
            </div>

            <?php
        }

        /*
         * Animation
        */
        public function esl_animation_metaboxes()
        {
            add_meta_box(
                'ed_slider_animation',
                __('Animation', self::LANG),
                array( $this, 'esl_slider_animation_callback' ),
                'ed_slider',
                'side',
                'high'
            );
        }

        public function esl_slider_animation_callback()
        {
            wp_nonce_field(basename(__FILE__), 'esl_animation_nonce_field');
            ?>
            <table width="100%">
                <tbody>
                <tr>
                    <td>
                        <label
                            for="slide_animation_duration"><?php _e('Slide Animation Duration', self::LANG); ?></label>
                    </td>
                    <td>
                        <input id="slide_animation_duration" class="setting" type="text" name="slide_animation_duration"
                               value="700"></td>
                </tr>
                </tbody>
            </table>
            <?php
        }

        /*
         * Navigation
        */
        public function esl_navigation_metaboxes()
        {
            add_meta_box(
                'ed_slider_navigation',
                __('Navigation', self::LANG),
                array($this, 'esl_slider_navigation_callback'),
                'ed_slider',
                'side',
                'high'
            );
        }

        public function esl_slider_navigation_callback()
        {
            wp_nonce_field(basename(__FILE__), 'esl_navigation_nonce_field');
            ?>
            <table width="100%">
                <tbody>
                <tr>
                    <td>
                        <label for="autoplay">Autoplay</label>
                    </td>
                    <td>
                        <input id="autoplay" class="setting" type="checkbox" name="autoplay" checked="checked"></td>
                </tr>
                <tr>
                    <td>
                        <label for="autoplay_delay">Autoplay Delay</label>
                    </td>
                    <td>
                        <input id="autoplay_delay" class="setting" type="text" name="autoplay_delay" value="5000"></td>
                </tr>
                <tr>
                    <td>
                        <label for="autoplay_direction">Autoplay Direction</label>
                    </td>
                    <td>
                        <select id="autoplay_direction" class="setting" name="autoplay_direction">
                            <option value="normal" selected="selected">Normal</option>
                            <option value="backwards">Backwards</option>
                        </select></td>
                </tr>
                <tr>
                    <td>
                        <label for="autoplay_on_hover">Autoplay On Hover</label>
                    </td>
                    <td>
                        <select id="autoplay_on_hover" class="setting" name="autoplay_on_hover">
                            <option value="pause" selected="selected">Pause</option>
                            <option value="stop">Stop</option>
                            <option value="none">None</option>
                        </select></td>
                </tr>
                <tr>
                    <td>
                        <label for="arrows">Arrows</label>
                    </td>
                    <td>
                        <input id="arrows" class="setting" type="checkbox" name="arrows"></td>
                </tr>
                <tr>
                    <td>
                        <label for="fade_arrows">Fade Arrows</label>
                    </td>
                    <td>
                        <input id="fade_arrows" class="setting" type="checkbox" name="fade_arrows" checked="checked">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="buttons">Buttons</label>
                    </td>
                    <td>
                        <input id="buttons" class="setting" type="checkbox" name="buttons" checked="checked"></td>
                </tr>
                <tr>
                    <td>
                        <label for="keyboard">Keyboard</label>
                    </td>
                    <td>
                        <input id="keyboard" class="setting" type="checkbox" name="keyboard" checked="checked"></td>
                </tr>
                <tr>
                    <td>
                        <label for="keyboard_only_on_focus">Keyboard Only On Focus</label>
                    </td>
                    <td>
                        <input id="keyboard_only_on_focus" class="setting" type="checkbox"
                               name="keyboard_only_on_focus"></td>
                </tr>
                <tr>
                    <td>
                        <label for="touch_swipe">Touch Swipe</label>
                    </td>
                    <td>
                        <input id="touch_swipe" class="setting" type="checkbox" name="touch_swipe" checked="checked">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="touch_swipe_threshold">Touch Swipe Threshold</label>
                    </td>
                    <td>
                        <input id="touch_swipe_threshold" class="setting" type="text" name="touch_swipe_threshold"
                               value="50"></td>
                </tr>
                </tbody>
            </table>
            <?php
        }

        public function esl_slider_metabox()
        {
            add_meta_box(
                'ed_slider_meta',
                __('Slides', self::LANG),
                array($this, 'esl_meta_callback'),
                'ed_slider',
                'normal',
                'high'
            );
        }

        public function esl_meta_callback()
        {
            global $post;
            wp_nonce_field(basename(__FILE__), 'esl_nonce_field');
            ?>
            <div class="ed-slider">
                <div class="ed-slider-label">
                    <label for="">Slides</label>
                </div>
                <div class="slide-input">
                    <div class="slide-repeater">
                        <table class="ed-slider-table">
                            <thead>
                            <tr>
                                <th><span></span></th>
                                <th><?php _e('Slide Image', 'ed-slider') ?></th>
                                <th><?php _e('Slide Title', 'ed-slider') ?></th>
                                <th><?php _e('Slide Content', 'ed-slider') ?></th>
                                <th><span></span></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            $slideCount = 0;
                            $slidemetaData = esl_get_field($post->ID, '_esl');
                            if ($slidemetaData) {
                                foreach ($slidemetaData as $slidemeta => $slideVal) {
                                    ?>
                                    <tr class="esl-row" id="esl-row-<?php echo $slideCount; ?>">
                                        <td class="esl-field reorder esl-row-handle">
                                            <span><?php echo $slideCount + 1; ?></span>
                                        </td>
                                        <td class="esl-field" width="150">
                                            <div>
                                                <div class="esl-img-uploader">
                                                    <div class="esl-hidden">
                                                        <input type="hidden" id="esl-img-<?php echo $slideCount; ?>"
                                                               name="esl[<?php echo $slideCount; ?>][img]"
                                                               value="<?php echo $slideVal['img']; ?>">

                                                    </div>
                                                    <div class="eslView">
                                                        <?php
                                                        $imgID = $slideVal['img'];
                                                        $srcImg = wp_get_attachment_image_src($imgID);
                                                        echo '<img src="' . $srcImg[0] . '" class="esl-img" >';
                                                        ?>
                                                    </div>
                                                    <div class="esl-view">
                                                        <p style="margin:0;">No image selected <a
                                                                class="edsl-upload-img-btn button" href="#">Add
                                                                Image</a></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="esl-field">
                                            <div class="esl-input-wrap">
                                                <input type="text" id="esl-img-<?php echo $slideCount; ?>" class=""
                                                       name="esl[<?php echo $slideCount; ?>][title]"
                                                       value="<?php echo $slideVal['title']; ?>">
                                            </div>
                                        </td>
                                        <td class="esl-field">
                                            <div class="esl-input-wrap">
                                                <textarea name="esl[<?php echo $slideCount; ?>][content]" class=""
                                                          id="esl-img-<?php echo $slideCount; ?>" cols="30"
                                                          rows="7"><?php echo $slideVal['content']; ?></textarea>
                                            </div>
                                        </td>
                                        <td class="esl-field remove esl-row-handle">
                                            <a href="#" class="esl-remove-row"><span
                                                    class="dashicons dashicons-trash"></span></a>
                                        </td>
                                    </tr>
                                    <?php
                                    $slideCount++;
                                }
                            }

                            if (0 == $slideCount) {
                                ?>
                                <tr class="esl-row">
                                    <td class="esl-field reorder esl-row-handle"><span>1</span></td>
                                    <td class="esl-field" width="150">
                                        <div>
                                            <div class="esl-img-uploader">
                                                <div class="esl-hidden">
                                                    <input type="hidden" id="esl-img-0" name="esl[0][img]" value="">

                                                </div>
                                                <div class="eslView">
                                                    <?php
                                                    //$imgID = $esl_meta['esl_img'][0];
                                                    $srcImg = wp_get_attachment_image_src($imgID);
                                                    echo '<img src="' . $srcImg[0] . '" class="esl-img" >';
                                                    ?>
                                                </div>
                                                <div class="esl-view">
                                                    <p style="margin:0;">No image selected <a
                                                            class="edsl-upload-img-btn button" href="#">Add Image</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="esl-field">
                                        <div class="esl-input-wrap">
                                            <input type="text" id="esl-img-0" class="" name="esl[0][title]" value="">
                                        </div>
                                    </td>
                                    <td class="esl-field">
                                        <div class="esl-input-wrap">
                                            <textarea name="esl[0][content]" class="" id="esl-img-0" cols="30"
                                                      rows="7"></textarea>
                                        </div>
                                    </td>
                                    <td class="esl-field remove esl-row-handle">

                                    </td>
                                </tr>
                                <?php
                            }
                            ?>


                            </tbody>
                        </table>
                        <ul class="ed-slider-acions">
                            <li>
                                <a href="javascript:;" id="add-new-slide"
                                   class="ed-slider-button button button-primary">+ Add slide</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
        }

        public function esl_meta_field_save($post_id)
        {

            //Check save status
            $is_autosave = wp_is_post_autosave($post_id);
            $is_revision = wp_is_post_revision($post_id);
            $is_valid_nonce = (isset($_POST['esl_nonce_field']) && wp_verify_nonce($_POST['esl_nonce_field'], basename(__FILE__))) ? 'true' : 'false';

            //Exit script depending on save status
            if ($is_autosave || $is_revision || !$is_valid_nonce) {
                return;
            }

            if ( ! current_user_can('edit_post', $post_id) ) {
                return;
            }

            if (isset($_POST['esl'])) {
                /*foreach ($_POST['esl'] as $row_id => $rows) {
                    foreach ($rows as $key => $value) {
                        if (is_array($value)) {
                            foreach ($value as $sub_key => $sub_value) {
                                $meta_key = '_' . $key . '_' . $row_id . '_' . $sub_key;
                                update_post_meta($post_id, $meta_key, $sub_value);
                            }
                        } else {
                            $meta_key = "_esl_" . $row_id . "_" . $key;
                            update_post_meta($post_id, $meta_key, $value);
                        }
                    }
                }*/
                update_post_meta($post_id, '_esl', $_POST['esl']);
            }

        }

        public function esl_admin_scripts()
        {
            wp_enqueue_media();
            wp_enqueue_style('esl-admin-style', plugins_url('../css/ed-slider.css', __FILE__));
            wp_enqueue_script('esl-admin-js', plugins_url('../js/ed-slider.js', __FILE__), array('jquery', 'jquery-ui-sortable'), '08112016', true);
        }

    }
}
