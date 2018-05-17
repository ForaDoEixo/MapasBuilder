<?php
/*
Plugin Name: Mapas Builder Plugin
Plugin URI: http://plugins.redelivre.org.br/mapas-builder
Description: List entities from mapas culturais in shortcode and Divi Module formats.
Author: Caipira Lab
Version: 0.1
Text Domain:
*/

Class MCWP_Entities_List
{
	function MCWP_Entities_list()
	{
		add_shortcode('list_entities', array(&$this, 'shortcode'));
		add_action( 'wp_enqueue_scripts', array(&$this, 'addScripts') );
	}
	function addScripts() {
		global $post;
		if( is_a( $post, 'WP_Post' ) && !has_shortcode( $post->post_content, 'list_entities') && !has_shortcode($post->post_content, 'et_pb_mcwp_list_entities') ) {
			return;
		}
		wp_enqueue_script('list-entities-ajax-script', plugin_dir_url( __FILE__ ) . 'js/app.js', array('jquery'));

		wp_enqueue_script('mustache', 'https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.3.0/mustache.min.js');

		wp_enqueue_style('list-entities-shortcode', plugin_dir_url( __FILE__ ) . 'css/mcwp-list-entities.css' );

	}

	function shortcode($atts, $content) {
		if (!is_array($atts))
		{
			echo "Error! No arguments found.";
			return;
		}
		if (!isset($atts['url']) || (empty($atts['url'])))
		{
			echo "Error! Url not found.";
			return;
		}

		$atts['url'] = rtrim($atts['url'],"/");

		$select = 'name,shortDescription,singleUrl';
		if (isset($atts['select']))
		{
			$select = $atts['select'];
		}
		$files = '(header.header,avatar.avatarBig):url';
		if (isset($atts['files']))
		{
			$files = $atts['files'];
		}
		$entity = 'space';
		if (isset($atts['entity'])) {
			$entity   = $atts['entity'];
		}
		$order = 'id ASC';
		if (isset($atts['order'])) {
			$order = $atts['order'];
		}
		if (isset($atts['pagination'])) {
			$pagination  = "data-pagination='true'";
		}
		$limit = "data-limit='10'";
		if (isset($atts['limit'])) {
			$limit  = "data-limit='".$atts['limit']."'";
		}

		if (isset($atts['seals'])) {
			//$seals  = $atts['seals'];
		}

		if (isset($atts['profiles'])) {
			//$profiles  = $atts['profiles'];
		}

		if (isset($content))
		{
			$mtemplate = $content;
		}

		$params = [
		'@files'     => $files,
		'@select'    => $select,
		'@order'	 => $order,
		];
		if (isset($seals))
		{
			$saux = [
			'@seals'     => $seals
			];
			$params = array_merge($params, $saux);
		}
		if (isset($profiles))
		{
			$paux = [
			'@profiles'     => $profiles
			];
			$params = array_merge($params, $paux);
		}
		$url = add_query_arg($params, $atts['url'] . '/api/'.$entity.'/find');

		ob_start();
		include('template.php');
		$html = ob_get_clean();
		return $html;
	}
}


add_action('init', function() {
	$MCWP_Entities_List = new MCWP_Entities_List;
});

add_action('et_builder_ready', 'mcwp_load_the_module');
function mcwp_load_the_module() {
	require 'mcwp_entities_module.php';
}
