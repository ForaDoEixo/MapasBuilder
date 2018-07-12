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
		add_action( 'admin_enqueue_scripts', array(&$this, 'addAdminScripts') );

		function custom_wpautop($content) {
			if (has_shortcode( get_the_content(), 'list_entities') || has_shortcode(get_the_content(), 'et_pb_mcwp_list_entities'))
				return $content;
			else
				return wpautop($content);
		}

		remove_filter('the_content', 'wpautop');
		add_filter('the_content', 'custom_wpautop');
	}

	function addScripts() {
		global $post;
		if( is_a( $post, 'WP_Post' ) && !has_shortcode( $post->post_content, 'list_entities') && !has_shortcode($post->post_content, 'et_pb_mcwp_list_entities') ) {
			return;
		}
		wp_enqueue_script('mapas-builder-proxy-ajax-script', plugin_dir_url( __FILE__ ) . 'js/app.js', array('jquery'));

		wp_enqueue_script('mustache', plugin_dir_url( __FILE__ ) . 'bower_components/mustache.js/mustache.min.js');

		wp_enqueue_style('list-entities-shortcode', plugin_dir_url( __FILE__ ) . 'css/mcwp-list-entities.css' );


		wp_localize_script(
			'mapas-builder-proxy-ajax-script',
			'mapas_builder_proxy_ajax_obj',
			array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
			);

	}
	function addAdminScripts() {
		wp_enqueue_style('list-entities-shortcode-admin', plugin_dir_url( __FILE__ ) . 'css/mcwp-list-entities-admin.css' );
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

		if (isset($atts['filters'])) {
			$filters  = $atts['filters'];
		}

		if (isset($atts['filters_input'])) {
			$filters_input  = $atts['filters_input'];
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

function mapas_builder_proxy_request() {
	if ( isset($_REQUEST) ) {

		$url = $_REQUEST['url'];
		$cache_id = md5($url);
		$chkch = get_transient($cache_id);
		if ($chkch)
		{
			$response = $chkch;
		}
		else
		{
			$response = wp_remote_get($url, array('timeout' => '120'));
			set_transient($cache_id,$response,60*60);
		}


		header("API-Metadata: ".$response["headers"]["api-metadata"]);
		header("Access-Control-Expose-Headers: API-Metadata");
		header("Content-Type: application/json");
		echo $response["body"];

	}

	die();
}

add_action( 'wp_ajax_mapas_builder_proxy_request', 'mapas_builder_proxy_request' );

add_action( 'wp_ajax_nopriv_mapas_builder_proxy_request', 'mapas_builder_proxy_request' );
