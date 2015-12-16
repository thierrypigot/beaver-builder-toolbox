<?php
class BB_Page_Settings {

    function __construct(){
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'print_footer'));
        add_action( 'wp_ajax_bb_pageSettings_update_post',      array( $this, 'ajax_handle_update_post' ) );
        add_action( 'wp_ajax_bb_pageSettings_update_postmeta',  array( $this, 'ajax_handle_update_postmeta' ) );
    }


    function enqueue_scripts(){
        global $post;

        if (class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active()) {
            wp_enqueue_script( 'bb-page-settings',   plugins_url( '/bb-page-settings/js/bb-page-settings.js', dirname(__FILE__) ), false, false, true );
            wp_enqueue_style( 'bb-page-settings',    plugins_url( '/bb-page-settings/css/bb-page-settings.css', dirname(__FILE__) ) );

            $data = array(
                'button_text'   => __('Page settings', 'bb-toolbox'),
                'saved_text'    => __('Saved!', 'bb-toolbox'),
                'homeurl'       => home_url(),
                'ajaxurl'       => admin_url('admin-ajax.php'),
                'post_id'       => $post->ID
            );
            wp_localize_script( 'bb-page-settings', 'BB_Settings', $data );
        }
    }

    function print_footer(){
        global $post;

        if (class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active())
        {

            $detect_seo = false;

            if( $this->detect_plugin( $this->detect_seo_plugins() ) )
            {
                $detect_seo = true;

                if( $this->detect_plugin( array( 'classes' => array('All_in_One_SEO_Pack','All_in_One_SEO_Pack_p') ) ) )
                {
                    // All in one SEO : aiosp_title + aiosp_description
                    $meta_title_field       = '_aioseop_title';
                    $meta_description_field = '_aioseop_description';
                }
                else if( $this->detect_plugin( array( 'classes' => array('wpSEO'), 'constants' => array( 'WPSEO_VERSION' ) ) ) )
                {
                    //  WordPress SEO
                    $meta_title_field       = '_yoast_wpseo_title';
                    $meta_description_field = '_yoast_wpseo_metadesc';
                }
                else if( $this->detect_plugin( array( 'classes' => array('HeadSpace_Plugin') ) ) )
                {
                    //  HeadSpace2 SEO
                    $meta_title_field       = '_headspace_page_title';
                    $meta_description_field = '_headspace_description';
                }
                else if( $this->detect_plugin( array( 'classes' => array('Platinum_SEO_Pack') ) ) )
                {
                    //  Platinum SEO Pack
                    $meta_title_field       = 'title';
                    $meta_description_field = 'description';
                }
                else if( $this->detect_plugin( array( 'classes' => array('Genesis_Admin_SEO_Settings') ) ) )
                {
                    //  Genesis
                    $meta_title_field       = '_genesis_title';
                    $meta_description_field = '_genesis_description';
                }

                $meta_title         = get_post_meta( $post->ID, $meta_title_field, true );
                $meta_description   = get_post_meta( $post->ID, $meta_description_field, true );
            }

            ?>
            <div class="fl-pageSettings-panel">
                <div class="fl-pageSettings-tabs">
                    <i class="fl-builder-pageSettings-close fa fa-times"></i>
                    <a data-tab="current-page" class="fl-active"><?php _e('Page Details', 'bb-toolbox'); ?></a>
                    <a data-tab="seo"><?php _e('SEO', 'bb-toolbox'); ?></a>
                </div>
                <div class="fl-pageSettings-panel-content">
                    <form action="">
                        <div data-tab="current-page" class="active" action="">
                            <div class="cell">
                                <div class="field">
                                    <div class="input-wrap">
                                        <input name="post_title" value="<?php echo esc_attr( $post->post_title ); ?>">
                                    </div>
                                    <label><?php _e('Title', 'bb-toolbox'); ?></label>
                                    <span class="indicator"><?php _e('Saving...', 'bb-toolbox'); ?></span>
                                </div>
                                <div class="field">
                                    <div class="input-wrap">
                                        <span class="input-prefix"><?php echo home_url(); ?>/</span><input name="post_name" class="inline-input" value="<?php echo esc_attr( $post->post_name ); ?>">
                                    </div>
                                    <label><?php _e('Permalink', 'bb-toolbox'); ?></label>
                                    <span class="indicator"><?php _e('Saving...', 'bb-toolbox'); ?></span>
                                </div>
                            </div>
                            <?php /*
                            <div class="panel-footer">
                                <input type="submit" value="submit">
                            </div>
                            */ ?>
                        </div>

                        <div data-tab="seo" action="">
                            <div class="cell">
                                <?php if( true === $detect_seo ): ?>
                                    <div class="field">
                                        <div class="input-wrap">
                                            <input name="meta_title" value="<?php echo esc_attr( $meta_title ); ?>" class="count" data-field="<?php echo esc_attr( $meta_title_field ); ?>">
                                        </div>
                                        <label><?php _e('Meta title', 'bb-toolbox'); ?> (<span><?php echo esc_html( strlen( $meta_title ) ); ?></span>/60)</label>
                                        <span class="indicator"><?php _e('Saving...', 'bb-toolbox'); ?></span>
                                    </div>
                                    <div class="field">
                                        <div class="input-wrap">
                                            <input name="meta_description" value="<?php echo esc_html( $meta_description ); ?>" class="count" data-field="<?php echo esc_attr( $meta_description_field ); ?>">
                                        </div>
                                        <label><?php _e('Meta description', 'bb-toolbox'); ?> (<span><?php echo esc_html( strlen( $meta_description ) ); ?></span>/160)</label>
                                        <span class="indicator"><?php _e('Saving...', 'bb-toolbox'); ?></span>
                                    </div>
                                <?php else: ?>
                                    <p><?php _e('No SEO plugin detected :(', 'bb-toolbox'); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>

                </form>
            </form>
            <?php
        }
    }

    function ajax_handle_update_post(){

        $post_id    = $_REQUEST['update_post'];
        $name       = $_REQUEST['update_name'];
        $value      = $_REQUEST['update_value'];

        $args = array(
            'ID' => $post_id
        );

        $args[$name] = $value;

        $success = wp_update_post( $args );

        if ($success) {
            $message = __('Saved!', 'bb-toolbox');
        } else {
            $message = __('Nope', 'bb-toolbox');
        }

        print $message;
        die();
    }

    function ajax_handle_update_postmeta(){

        $post_id    = $_REQUEST['update_post'];
        $field      = $_REQUEST['update_field'];
        $value      = $_REQUEST['update_value'];

        $success = update_post_meta( $post_id, $field, $value );

        if ($success) {
            $message = __('Saved!', 'bb-toolbox');
        } else {
            $message = __('Nope', 'bb-toolbox');
        }

        print $message;
        die();
    }


    /**
     * Detect plugin by constant, class or function existence.
     *
     * @since 1.6.0
     *
     * @param array $plugins Array of array for constants, classes and / or
     * functions to check for plugin existence.
     * @return boolean True if plugin exists or false if plugin constant, class or
     * function not detected.
     *
     * src: genesis
     */
    function detect_plugin( $plugins ) {

        /** Check for classes */
        if ( isset( $plugins['classes'] ) ) {
            foreach ( $plugins['classes'] as $name ) {
                if ( class_exists( $name ) )
                    return true;
            }
        }

        /** Check for functions */
        if ( isset( $plugins['functions'] ) ) {
            foreach ( $plugins['functions'] as $name ) {
                if ( function_exists( $name ) )
                    return true;
            }
        }

        /** Check for constants */
        if ( isset( $plugins['constants'] ) ) {
            foreach ( $plugins['constants'] as $name ) {
                if ( defined( $name ) )
                    return true;
            }
        }

        /** No class, function or constant found to exist */
        return false;

    }

    /**
     * Detect some SEO Plugin that add constants, classes or functions.
     *
     * Uses genesis_detect_seo_plugin filter to allow third party manpulation of SEO
     * plugin list.
     *
     * @since 1.6.0
     *
     * @uses detect_plugin()
     *
     * @return boolean True if plugin exists or false if plugin constant, class or function not detected.
     *
     * src: Genesis
     */
    function detect_seo_plugins() {

        return 	(
            // Use this filter to adjust plugin tests.
            apply_filters(
                'genesis_detect_seo_plugins',
                /** Add to this array to add new plugin checks. */
                array(

                    // Classes to detect.
                    'classes' => array(
                        'All_in_One_SEO_Pack',
                        'All_in_One_SEO_Pack_p',
                        'HeadSpace_Plugin',
                        'Platinum_SEO_Pack',
                        'wpSEO',
                        'Genesis_Admin_SEO_Settings',
                    ),

                    // Functions to detect.
                    'functions' => array(),

                    // Constants to detect.
                    'constants' => array( 'WPSEO_VERSION', ),
                )
            )
        );
    }
}

$BB_Page_Settings = new BB_Page_Settings();