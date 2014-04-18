<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//include the main class file
require_once( get_template_directory() . '/includes/Tax-meta-class/Tax-meta-class.php' );

if ( is_admin() ){
  /* 
   * prefix of meta keys, optional
   */
  $prefix = 'thaim_';
  /* 
   * configure your meta box
   */
  $config = array(
    'id'             => 'thaim_meta_box',
    'title'          => 'Thaim Meta Box',
    'pages'          => array('category', 'post_tag'),
    'context'        => 'normal',
    'fields'         => array(),
    'local_images'   => false,
    'use_with_theme' => get_template_directory_uri() . '/includes/Tax-meta-class/'
  );
  
  
  /*
   * Initiate your meta box
   */
  $thaim_meta =  new Tax_Meta_Class( $config );
  
  /*
   * Add fields to your meta box
   */
  
  //text field
  $thaim_meta->addText( $prefix.'tax_icon', array( 'name'=> __( 'Icon', 'thaim' ), 'desc' => sprintf( __('You can choose an icon in this <a href="%s?TB_iframe=true" class="thickbox" title="Copy &amp; Paste the desired icon">list</a>', 'thaim'), get_template_directory_uri() .'/fonts/thaimicons/index.html' ) ) );
  
  $thaim_meta->addImage($prefix.'tax_image', array( 'name'=> __( 'Image header','thaim' ) ) );

  //Finish Meta Box Decleration
  $thaim_meta->Finish();
}

// custom filters added to the class.

// 1 no html tags in fields !
add_filter( 'tax_meta_class_update_meta', 'tax_meta_class_update_meta_filter');

function tax_meta_class_update_meta_filter( $meta ) {
	return wp_kses( $meta, array() );
}

// 2 well i need to htmlentities some values !
add_filter( 'tax_meta_class_show_field_text', 'thaim_tax_meta_class_show_field_text_filter', 10, 2);

function thaim_tax_meta_class_show_field_text_filter( $meta, $field ) {
	if ( $field['id'] == 'thaim_tax_icon' )
		$meta = htmlentities( $meta );
		
	return $meta;
}