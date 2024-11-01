<?php

GFForms::include_addon_framework();

class GFEasytooltippro extends GFAddOn {

	protected $_version = GF_EASY_TOOlTIP_PRO_VERSION;
	protected $_min_gravityforms_version = '2.0';
	protected $_slug = 'easy-gravity-tooltip_pro';
	protected $_url = 'https://neatma.com/gravity-forms-tooltips/';
	protected $_path = 'easy-gravity-tooltip-pro/index.php';
	protected $_full_path = __FILE__;
	protected $_title = 'Gravity Forms Easy Tooltip PRO Add-On';
	protected $_short_title = 'Easy Gravity Tooltip Pro';
	private static $_instance = null;

	/**
	 * Get an instance of this class.
	 *
	 * @return GFEasyTooltip
	 */
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new GFEasytooltippro();
		}

		return self::$_instance;
	}

	/**
	 * Handles hooks and loading of language files.
	 */
	public function init() {
		parent::init();
		add_filter( 'gform_form_args', array($this,'tp_form_args'));
		add_action( 'gform_editor_js', array($this, 'tooltip_editor_js'));
		add_filter( 'gform_field_content', array($this, 'tooltip_content'),10, 5 );
		add_action( 'admin_notices', array($this, 'update_available_notice' ));
		add_action( 'wp_enqueue_scripts', array($this, 'enquee_main_js_css' ));


	}



	/**
	 * Initialize the admin specific hooks.
	 */
	public function init_admin() {

		// form editor
		add_action( 'gform_field_appearance_settings', array($this, 'tooltip_input'), 10, 2 );
		
//		add_action( 'in_plugin_update_message-easy_gravity_tooltip/index.php', array($this, 'update_detector'), 10, 2 );
//		add_action( 'after_plugin_row_easy-gravity-tooltip-pro/index.php', array( $this, 'gf_plugin_row' ), 10, 2 );

		parent::init_admin();

	}





	/**
	 * enquee main script and styles.
	 */
function enquee_main_js_css (){
	
     wp_enqueue_script( 'tooltip-script',GF_EASY_TOOlTIP_PRO_URL . 'assests/js/easytp.bundle.min.js', array( 'jquery' ) );

	 wp_enqueue_style( 'tooltip-css' , GF_EASY_TOOlTIP_PRO_URL . 'assests/css/easytp.bundle.min.css', 'all' );
     wp_enqueue_style( 'tooltip-css-themes' ,GF_EASY_TOOlTIP_PRO_URL . 'assests/css/themes/easytp-themes.min.css', 'all' );
	 
}


	/**
	 * enquee editor js.
	 */

	function tooltip_editor_js(){
		
		?>
		<script type='text/javascript'>
			fieldSettings.text += ', tooltip_input';
			jQuery(document).on('gform_load_field_settings', function(event, field, form){
				jQuery('#tooltip_input').val(field['tooltiptext']);
				jQuery('.tooltip_input.field_setting').show();
			});
		</script>
		<?php
	}


	/**
	 * Get option method.
	 */
public function get_form_options( $form_id ) {
        $form = GFAPI::get_form( $form_id );
		$esytp_form_settings  = $this->get_form_settings( $form );

	return $esytp_form_settings;
}


public function tp_form_args( $form_args ) {
		$form_id = $form_args['form_id'];
        $form = GFAPI::get_form( $form_id );
		$esytp_form_settings  = $this->get_form_settings( $form );
        $this->enquee_tpscript($esytp_form_settings);
		return $form_args;
	}



public function enquee_tpscript ($esytp_form_settings){
        $triggeropen =  rgar($esytp_form_settings,'open-hover');
        $triggerclose =  rgar($esytp_form_settings,'close-hover');
		print	'<style type="text/css">' . rgar($esytp_form_settings,'css') . '</style> ';
	
		print '<script  type="text/javascript">'; ?>	
	
		        jQuery(document).ready(function() {	
				jQuery('.easygf-tooltip').tooltipster({
					    trigger: 'custom',
				triggerOpen: {
					mouseenter: <?php if ($triggeropen == NULL) {print 'true';}elseif ($triggeropen == '1') {print 'true';} else {print 'false';} ?>,
					click: <?php  (rgar($esytp_form_settings,'open-click') == '1') ? print 'true' : print 'false'; ?>,
					touchstart: <?php  (rgar($esytp_form_settings,'open-touch') == '1') ? print 'true' : print 'false'; ?>,
					tap: <?php  (rgar($esytp_form_settings,'open-tap') == '1') ? print 'true' : print 'false'; ?>,

				},
				triggerClose: {
					mouseleave: <?php  if ($triggerclose == NULL) {print 'true';}elseif ($triggerclose == '1') {print 'true';} else {print 'false';} ?>,
					click: <?php  (rgar($esytp_form_settings,'close-click') == '1') ? print 'true' : print 'false'; ?>,
					scroll: <?php  (rgar($esytp_form_settings,'close-scroll') == '1') ? print 'true' : print 'false'; ?>,
					touchleave: <?php  (rgar($esytp_form_settings,'close-touch') == '1') ? print 'true' : print 'false'; ?>,
					tap: <?php  (rgar($esytp_form_settings,'close-tap') == '1') ? print 'true' : print 'false'; ?>,
					originClick: <?php  (rgar($esytp_form_settings,'close-origin') == '1') ? print 'true' : print 'false'; ?>,
				},
				 
   				 functionInit: function(instance, helper){
					
     			   var content = jQuery(helper.origin).find('.tooltip_content').detach();
      			  instance.content(content);
   				 },
							
    			 theme: '<?php echo rgar($esytp_form_settings,'theme'); ?>',
				 animation: '<?php echo rgar($esytp_form_settings,'animation','grow'); ?>',
				 animationDuration: <?php echo rgar($esytp_form_settings,'animationDuration',350); ?>,
 				 delay: <?php echo rgar($esytp_form_settings,'delay',300); ?>,
 				 delayTouch: <?php echo rgar($esytp_form_settings,'delay-touch',300); ?>,
				 side: '<?php echo rgar($esytp_form_settings,'tposition','top'); ?>',
				 arrow: <?php (rgar($esytp_form_settings,'showarrow') == '1') ? print 'true' : print 'false'; ?>,
				 contentAsHTML: 'true',
				 maxWidth: <?php echo rgar($esytp_form_settings,'maxWidth',0); ?>,
				 minWidth: <?php echo rgar($esytp_form_settings,'minWidth',0); ?>,
				 distance: <?php echo rgar($esytp_form_settings,'tpdistance',6); ?>,

					});
		
                });

	<?php print	'</script>';

		
}




function tooltip_content($field_content, $field, $value, $lead_id, $form_id){
		$esytp_form_settings = $this->get_form_options($form_id);
	
    		 if (! is_admin()) {
				$tooltip_field =  $field->tooltiptext;
			if  (empty($tooltip_field)) return $field_content;
	 	$showicon = rgar($esytp_form_settings,'showicon');
		settype($showicon, 'bool');
	 		$position_label = strpos($field_content, "</label");
			$position_class = strpos($field_content, "gfield_label");
			$field_content = substr_replace($field_content, $tooltip_field , $position_label, 0);
			$field_content = substr_replace($field_content, '<span style="display:none;" class="tooltip_content">', $position_label, 0);
					
		 if($showicon == 1) {
			 	$field_content = substr_replace($field_content, 'easygf-tooltip icon ', $position_class, 0);
				(rgar($esytp_form_settings,'tpiconposition') == 'after' ) ? $tp_icon_margin = 'margin-left' : $tp_icon_margin = 'margin-right';
				
			 if( rgar($esytp_form_settings,'custom_tp_icon') == true) {
			 	 print	'<style type="text/css">  .easygf-tooltip.icon:' . rgar($esytp_form_settings,'tpiconposition','after') . ' {  content: ""; position:relative;background-size:contain;background-image: url( ' .  wp_get_attachment_url( rgar($esytp_form_settings,'custom_tp_icon') ) .  ' ); background-repeat: no-repeat; width: ' . rgar($esytp_form_settings,'tpiconsize',20) . 'px;  height: ' . rgar($esytp_form_settings,'tpiconsize',20) . 'px; display: inline-block; ' . $tp_icon_margin . ' : 5px;top: 4px;} </style> ';
			 }
			 if( rgar($esytp_form_settings,'custom_tp_icon') == false) {
				 print	'<style type="text/css">  .easygf-tooltip.icon:' . rgar($esytp_form_settings,'tpiconposition','after') . ' {  content: ""; position:relative;background-size:contain;background-image: url(' . GF_EASY_TOOlTIP_PRO_URL . 'assests/img/question-mark.svg); background-repeat: no-repeat; width: ' . rgar($esytp_form_settings,'tpiconsize',20) . 'px;  height: ' . rgar($esytp_form_settings,'tpiconsize',20) . 'px; display: inline-block; ' . $tp_icon_margin . ' : 5px;top: 4px;} </style> ';
			 }
		 } else {
				$field_content = substr_replace($field_content, 'easygf-tooltip ', $position_class, 0);
    	 }
	 
	 }


return $field_content;
}



function tooltip_input( $placement, $form_id ) {
 
		if ( $placement == 0 ) {
			?>
			<li class="tooltip_input field_setting" style="">
				<label for="tooltip_input" class="easy-gf-tp">
					<?php esc_html_e( 'Tooltip Content (options is in settings menu)', 'easy-gravity-tooltip' ); ?>
				</label>
				<textarea style="width:100%;height:75px;" type="text" id="tooltip_input" onchange="SetFieldProperty('tooltiptext', this.value);"></textarea> 
			</li>
			<?php
		}
}


	/**
	 * Configures the settings which should be rendered on the Form Settings > Simple Add-On tab.
	 *
	 * @return array
	 */
	public function form_settings_fields( $form ) {
		return array(
			array(
				'title'  => esc_html__( 'Easy Tooltip Pro Settings', 'easy-gravity-tooltip' ),
				'fields' => array(
					array(
						'label'   => esc_html__( 'Show tooltip icon', 'easy-gravity-tooltip' ),
						'type'    => 'checkbox',
						'name'    => 'showicon',
						'tooltip' => esc_html__( 'If you enable this option an icon will be displayed beside the label.', 'easy-gravity-tooltip' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Enabled', 'easy-gravity-tooltip' ),
								'name'  => 'showicon',
								'default_value' => false,
							),
						),
					),
					array(
						'label'   => esc_html__( 'Show tooltip arrow', 'easy-gravity-tooltip' ),
						'type'    => 'checkbox',
						'name'    => 'arrow',
						'tooltip' => esc_html__( 'If you enable this option an icon will be displayed beside the label.', 'easy-gravity-tooltip' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Enabled', 'easy-gravity-tooltip' ),
								'name'  => 'showarrow',
								'default_value' => true,
							),
						),
					),
			
					array(
						'label'   => esc_html__( 'Tooltip Position', 'easy-gravity-tooltip' ),
						'type'    => 'radio',
						'name'    => 'tposition',
						'horizontal' => true,
						'tooltip' => esc_html__( 'Choose the position of tooltip', 'easy-gravity-tooltip' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'top', 'easy-gravity-tooltip' ),
								'name'  => 'top',
							),
							array(
								'label' => esc_html__( 'bottom', 'easy-gravity-tooltip' ),
								'name'  => 'bottom',
							),
							array(
								'label' => esc_html__( 'right', 'easy-gravity-tooltip' ),
								'name'  => 'right',
							),
							array(
								'label' => esc_html__( 'left', 'easy-gravity-tooltip' ),
								'name'  => 'left',
							),
						),
					),
					array(
						'label'   => esc_html__( 'Tooltip Icon Position', 'easy-gravity-tooltip' ),
						'type'    => 'radio',
						'name'    => 'tpiconposition',
						'horizontal' => true,
						'tooltip' => esc_html__( 'Choose the position of tooltip icon', 'easy-gravity-tooltip' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'after', 'easy-gravity-tooltip' ),
								'name'  => 'right',
							),
							array(
								'label' => esc_html__( 'before', 'easy-gravity-tooltip' ),
								'name'  => 'left',
							),
						),
					),
					array(
						'label'   => esc_html__( 'How to open', 'easy-gravity-tooltip' ),
						'type'    => 'checkbox',
						'name'    => 'triggeropen',
						'tooltip' => esc_html__( 'When a tooltip should be opened ', 'easy-gravity-tooltip' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Hover', 'easy-gravity-tooltip' ),
								'name'  => 'open-hover',
							),
							array(
								'label' => esc_html__( 'Click', 'easy-gravity-tooltip' ),
								'name'  => 'open-click',
							),
							array(
								'label' => esc_html__( 'Touch And Hold (touch device)', 'easy-gravity-tooltip' ),
								'name'  => 'open-touch',
							),
							array(
								'label' => esc_html__( 'Tap the label in screen (touch device)', 'easy-gravity-tooltip' ),
								'name'  => 'open-tap',
							),
						),
					),
					array(
						'label'   => esc_html__( 'How to close', 'easy-gravity-tooltip' ),
						'type'    => 'checkbox',
						'name'    => 'triggerclose',
						'tooltip' => esc_html__( 'When a tooltip should be closed', 'easy-gravity-tooltip' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Mouse Leave', 'easy-gravity-tooltip' ),
								'name'  => 'close-hover',
							),
							array(
								'label' => esc_html__( 'Click', 'easy-gravity-tooltip' ),
								'name'  => 'close-click',
							),
							array(
								'label' => esc_html__( 'Click on origin (label)', 'easy-gravity-tooltip' ),
								'name'  => 'close-origin',
							),
							array(
								'label' => esc_html__( 'Scroll', 'easy-gravity-tooltip' ),
								'name'  => 'close-scroll',
							),
							array(
								'label' => esc_html__( 'Touch Leave (touch device)', 'easy-gravity-tooltip' ),
								'name'  => 'close-touch',
							),
							array(
								'label' => esc_html__( 'Tap the screen (touch device)', 'easy-gravity-tooltip' ),
								'name'  => 'close-tap',
							),
						),
					),
					array(
						'label'   => esc_html__( 'Animation', 'easy-gravity-tooltip' ),
						'type'    => 'select',
						'name'    => 'animation',
						'tooltip' => esc_html__( 'Determines how the tooltip will animate in and out', 'easy-gravity-tooltip' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Fade', 'easy-gravity-tooltip' ),
								'value' => 'fade',
							),
							array(
								'label' => esc_html__( 'Grow', 'easy-gravity-tooltip' ),
								'value' => 'grow',
							),
							array(
								'label' => esc_html__( 'Swing', 'easy-gravity-tooltip' ),
								'value' => 'swing',
							),
							array(
								'label' => esc_html__( 'Slide', 'easy-gravity-tooltip' ),
								'value' => 'slide',
							),
							array(
								'label' => esc_html__( 'Fall', 'easy-gravity-tooltip' ),
								'value' => 'fall',
							),
							array(
								'label' => esc_html__( 'backInDown', 'easy-gravity-tooltip' ),
								'value' => 'backInDown',
							),
							array(
								'label' => esc_html__( 'bounceInLeft', 'easy-gravity-tooltip' ),
								'value' => 'bounceInLeft',
							),
							array(
								'label' => esc_html__( 'bounceInRight', 'easy-gravity-tooltip' ),
								'value' => 'bounceInRight',
							),
							array(
								'label' => esc_html__( 'bounceInUp', 'easy-gravity-tooltip' ),
								'value' => 'bounceInUp',
							),
							array(
								'label' => esc_html__( 'bounceInDown', 'easy-gravity-tooltip' ),
								'value' => 'bounceInDown',
							),
							array(
								'label' => esc_html__( 'jackInTheBox', 'easy-gravity-tooltip' ),
								'value' => 'jackInTheBox',
							),

						),
					),
					array(
						'label'   => esc_html__( 'Theme', 'easy-gravity-tooltip' ),
						'type'    => 'select',
						'name'    => 'theme',
				        'class'   => 'fieldwidth-1',
						'tooltip' => esc_html__( 'Custom built styles', 'easy-gravity-tooltip' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Gray', 'easy-gravity-tooltip' ),
								'value' => '',
							),
							array(
								'label' => esc_html__( 'Golden', 'easy-gravity-tooltip' ),
								'value' => 'tooltipster-punk',
							),
							array(
								'label' => esc_html__( 'Light Gray', 'easy-gravity-tooltip' ),
								'value' => 'tooltipster-light',
							),
							array(
								'label' => esc_html__( 'Borderless Black', 'easy-gravity-tooltip' ),
								'value' => 'tooltipster-borderless',
							),
							array(
								'label' => esc_html__( 'Shadow', 'easy-gravity-tooltip' ),
								'value' => 'tooltipster-shadow',
							),
							array(
								'label' => esc_html__( 'Noir', 'easy-gravity-tooltip' ),
								'value' => 'tooltipster-noir',
							),

						),
					),
					array(
						'label'             => esc_html__( 'Delay Start', 'easy-gravity-tooltip' ),
						'type'              => 'text',
						'name'              => 'delay',
						'tooltip'           => esc_html__( 'This is the delay before the tooltip starts its opening and closing in ms (Default: 300)', 'easy-gravity-tooltip' ),
						'class'             => 'small',
						'feedback_callback' => array( $this, 'is_valid_setting' ),
						'placeholder' => 300,
					),
					array(
						'label'             => esc_html__( 'Delay Start For Touch', 'easy-gravity-tooltip' ),
						'type'              => 'text',
						'name'              => 'delay-touch',
						'tooltip'           => esc_html__( 'This is the delay before the tooltip starts its opening and closing in touch and hold option in ms (Default: 300)', 'easy-gravity-tooltip' ),
						'class'             => 'small',
						'feedback_callback' => array( $this, 'is_valid_setting' ),
						'placeholder' => 300,
					),
					array(
						'label'             => esc_html__( 'Animation Duration', 'easy-gravity-tooltip' ),
						'type'              => 'text',
						'name'              => 'animationDuration',
						'tooltip'           => esc_html__( 'This is the duration of the tooltip opening and closing animation in ms (Default: 350)', 'easy-gravity-tooltip' ),
						'class'             => 'small',
						'feedback_callback' => array( $this, 'is_valid_setting' ),
						'placeholder' => 350,
					),
					array(
						'label'             => esc_html__( 'Max width of tooltip box', 'easy-gravity-tooltip' ),
						'type'              => 'text',
						'name'              => 'maxWidth',
						'tooltip'           => esc_html__( 'This is the max width of tooltip box in px (Default: auto)', 'easy-gravity-tooltip' ),
						'class'             => 'small',
						'feedback_callback' => array( $this, 'is_valid_setting' ),
						'placeholder' => 0,
					),
					array(
						'label'             => esc_html__( 'Min width of tooltip box', 'easy-gravity-tooltip' ),
						'type'              => 'text',
						'name'              => 'minWidth',
						'tooltip'           => esc_html__( 'This is the min width of tooltip box in px (Default: auto)', 'easy-gravity-tooltip' ),
						'class'             => 'small',
						'feedback_callback' => array( $this, 'is_valid_setting' ),
						'placeholder' => 0,
					),
					array(
						'label'             => esc_html__( 'Distance between label and tooltip box', 'easy-gravity-tooltip' ),
						'type'              => 'text',
						'name'              => 'tpdistance',
						'tooltip'           => esc_html__( 'This is the Distance between the label and the tooltip box in px (Default: 6)', 'easy-gravity-tooltip' ),
						'class'             => 'small',
						'feedback_callback' => array( $this, 'is_valid_setting' ),
						'placeholder' => 6,
					),
					
					array(
						'label' => esc_html__( 'Custom Tooltip Icon', 'easy-gravity-tooltip' ),
						'type'  => 'custom_icon_type',
						'name'  => 'custom_tp_icon_toplevel',
						'args'  => array(
							'text'     => array(
								//'label'         => esc_html__( 'A textbox sub-field', 'easy-gravity-tooltip' ),
								'name'          => 'custom_tp_icon',
								'class'         => 'process_custom_icon',
							),
						),
					),
										array(
						'label'             => esc_html__( 'Tooltip Icon Size', 'easy-gravity-tooltip' ),
						'type'              => 'text',
						'name'              => 'tpiconsize',
						'tooltip'           => esc_html__( 'This is the tooltip icon size in px (Default: 20)', 'easy-gravity-tooltip' ),
						'class'             => 'small',
						'placeholder' => 20,
					),
					array(
						'label'   => esc_html__( 'Custom CSS', 'easy-gravity-tooltip' ),
						'type'    => 'textarea',
						'name'    => 'css',
						'tooltip' => esc_html__( 'You can add your custom css here it will be loaded at the frontend', 'easy-gravity-tooltip' ),
						'class'   => 'medium merge-tag-support mt-position-right',
					),
					array(
						'label' => esc_html__( 'Easy-Gf-Tp hidden Field', 'easy-gravity-tooltip' ),
						'type'  => 'hidden',
						'name'  => 'myhidden',
					),

				),
			),
		);
	}


	/**
	 * Define the markup for the my_custom_field_type type field.
	 *
	 * @param array $field The field properties.
	 * @param bool|true $echo Should the setting markup be echoed.
	 */
	public function settings_custom_icon_type( $field, $echo = true ) {

		// get the text field settings from the main field and then render the text field
		$text_field = $field['args']['text'];
		$this->settings_text( $text_field );
				
	    echo   '<button style="margin-left:20px;" class="set_custom_tpicon button">Select Icon</button>';
	}






/**
 * Configures the settings which should be rendered on the add-on settings tab.
 *
 * @return array
 */
public function plugin_settings_fields() {
    return array(
        array(
            'title'  => esc_html__( 'Easy Gravity Forms Tooltip', 'easy-gravity-tooltip' ),
            'fields' => array(
                array(
                    'name'                => 'easy_gf_tp_license_key',
                    'label'               => esc_html__( 'License Key', 'easy-gravity-tooltip' ),
                    'type'                => 'text',
                    'input_type'          => 'password',
                    'validation_callback' => array( $this, 'license_validation' ),
                    'feedback_callback'   => array( $this, 'license_feedback' ),
                    'error_message'       => esc_html__( 'Invalid license', 'easy-gravity-tooltip' ),
                    'class'               => 'fieldwidth-4',
                    'default_value'       => '',
                ),
            ),
        ),
    );
}

/**
 * Determine if the license key is valid so the appropriate icon can be displayed next to the field.
 *
 * @param string $value The current value of the license_key field.
 * @param array $field The field properties.
 *
 * @return bool|null
 */
public function license_feedback( $value, $field ) {
    if ( empty( $value ) ) {
        return null;
    }
 
    // Send the remote request to check the license is valid
    $license_data = $this->perform_edd_license_request( 'check_license', $value );
 
    $valid = null;
    if ( empty( $license_data ) || $license_data->license == 'invalid' ) {
        $valid = false;
    } elseif ( $license_data->license == 'valid' ) {
        $valid = true;
    }
 	if (!empty($license_data) && is_object($license_data) && property_exists($license_data, 'license')) {
			update_option('easy_gf_tp_license_status', $license_data->license);
	}
    return $valid;
}
/**
 * Handle license key activation or deactivation.
 *
 * @param array $field The field properties.
 * @param string $field_setting The submitted value of the license_key field.
 */
public function license_validation( $field, $field_setting ) {
    $old_license = $this->get_plugin_setting( 'easy_gf_tp_license_key' );
 
    if ( $old_license && $field_setting != $old_license ) {
        // Send the remote request to deactivate the old license
        $this->perform_edd_license_request( 'deactivate_license', $old_license );
		if ( !empty($response) && is_object($response) && property_exists($response, 'license') && $response->license == 'deactivated' ) {
			delete_option('easy_gf_tp_license_status');
		}
    }
 
    if ( ! empty( $field_setting ) ) {
        // Send the remote request to activate the new license
        $this->perform_edd_license_request( 'activate_license', $field_setting );
		if ( !empty($response) && is_object($response) && property_exists($response, 'license') ) {
			update_option('easy_gf_tp_license_status', $response->license);
		}
    }
}
/**
 * Send a request to the EDD store url.
 *
 * @param string $edd_action The action to perform (check_license, activate_license, or deactivate_license).
 * @param string $license The license key.
 *
 * @return object
 */
public function perform_edd_license_request( $edd_action, $license ) {
    // Prepare the request arguments
    $args = array(
        'timeout'   => 10,
        'sslverify' => false,
        'body'      => array(
            'edd_action' => $edd_action,
            'license'    => trim( $license ),
            'item_name'  => urlencode( GF_EASY_TOOlTIP_PRO_NAME ),
            'url'        => home_url(),
        ),
    );
 
    // Send the remote request
    $response = wp_remote_post( GF_EASY_TOOlTIP_PRO_STORE_URL, $args );
 
    return json_decode( wp_remote_retrieve_body( $response ) );
}


	/**
	 * Add custom messages after plugin row based on license status
	 */

	public function gf_plugin_row($plugin_file='', $plugin_data=array(), $status='') {
		$row = array();
		$license_key = trim($this->get_plugin_setting('easy_gf_tp_license_key'));
		$license_status = get_option('easy_gf_tp_license_status', '');
		if (empty($license_key) || empty($license_status)) {
			$row = array(
				'<tr class="plugin-update-tr">',
					'<td colspan="3" class="plugin-update gf_color_picker-plugin-update">',
						'<div class="update-message">',
							'<a href="' . admin_url('admin.php?page=gf_settings&subview=' . $this->_slug) . '">Activate</a> your license to receive plugin updates and support. Need a license key? <a href="' . $this->_url . '" target="_blank">Purchase one now</a>.',
						'</div>',
						'<style type="text/css">',
						'.plugin-update.gf_color_picker-plugin-update .update-message:before {',
							'content: "\f348";',
							'margin-top: 0;',
							'font-family: dashicons;',
							'font-size: 20px;',
							'position: relative;',
							'top: 5px;',
							'color: orange;',
							'margin-right: 8px;',
						'}',
						'.plugin-update.gf_color_picker-plugin-update {',
							'background-color: #fff6e5;',
						'}',
						'.plugin-update.gf_color_picker-plugin-update .update-message {',
							'margin: 0 20px 6px 40px !important;',
							'line-height: 28px;',
						'}',
						'</style>',
					'</td>',
				'</tr>'
			);
		}
		elseif(!empty($license_key) && $license_status != 'valid') {
			$row = array(
				'<tr class="plugin-update-tr">',
					'<td colspan="3" class="plugin-update gf_color_picker-plugin-update">',
						'<div class="update-message">',
							'Your license is invalid or expired. <a href="'.admin_url('admin.php?page=gf_settings&subview='.$this->_slug).'">Enter valid license key</a> or <a href="'.$this->_url.'" target="_blank">purchase a new one</a>.',
							'<style type="text/css">',
								'.plugin-update.gf_color_picker-plugin-update .update-message:before {',
									'content: "\f348";',
									'margin-top: 0;',
									'font-family: dashicons;',
									'font-size: 20px;',
									'position: relative;',
									'top: 5px;',
									'color: #d54e21;',
									'margin-right: 8px;',
								'}',
								'.plugin-update.gf_color_picker-plugin-update {',
									'background-color: #ffe5e5;',
								'}',
								'.plugin-update.gf_color_picker-plugin-update .update-message {',
									'margin: 0 20px 6px 40px !important;',
									'line-height: 28px;',
								'}',
							'</style>',
						'</div>',
					'</td>',
				'</tr>'
			);
		}

		echo implode('', $row);
	}







	// # HELPERS -------------------------------------------------------------------------------------------------------

	/**
	 * The feedback callback for the 'mytextbox' setting on the plugin settings page and the 'mytext' setting on the form settings page.
	 *
	 * @param string $value The setting value.
	 *
	 * @return bool
	 */
	public function is_valid_setting( $value ) {
		return is_numeric( $value );
	}







	function update_detector( $data, $response ) {
		if( isset( $data['update'] ) ) {
			set_transient( 'easy_gftp_update', true);
		}
		else {
			delete_transient( 'easy_gftp_update' );
		}
	}





    public function update_available_notice() {
       if(! get_transient( 'easy_gftp_update' ) ) {
            return;
        } ?>
        <div class="notice easy-gftp-update">
            <div class="easy-gftp-update_notice">
                <strong><?php esc_html__e( 'Easy gravity tooltip New Update available', 'easy-gravity-tooltip' ); ?></strong>
                <p><?php printf( esc_html__e( 'Please <a href="%s">update</a> <strong>Easy Gravity Tooltip</strong> to fix bugs and add new features.', 'easy-gravity-tooltip' ), admin_url( 'plugins.php' ) ); ?></p>
            </div>

        </div>

        <style>
            .notice.easy-gftp-update {
                display: flex;
                align-items: center;
                padding: 15px 10px;
                border: 1px solid #e4e4e4;
                border-left: 4px solid #46b44e;
                background-image: url('<?php echo GF_EASY_TOOlTIP_PRO_URL ; ?>assests/img/notification-banner.svg');
                background-repeat: no-repeat;
                background-position: bottom right;
            }

            .easy-gftp-update_notice {
                flex-basis: 100%;
            }


        </style>
        <?php
    }
	
}