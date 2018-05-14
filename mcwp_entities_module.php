<?php
class ET_Builder_Module_Divi_List_Entities extends ET_Builder_Module {
    public $slug       = 'et_pb_mcwp_list_entities';
    public $vb_support = 'on';
    public function init() {
        $this->name = esc_html__( 'Mapas Culturais: List Entities', 'mcwp_list_entities' );
    }
    public function get_fields() {
        return array(
            'url'     => array(
                'label'           => esc_html__( 'URL', 'mcwp_list_entities' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                ),
            'entity'     => array(
                'label'           => esc_html__( 'Entity', 'mcwp_list_entities' ),
                'type'            => 'select',
                'option_category' => 'basic_option',
                'options' => array(
                    'agent' => esc_html__( 'Agent', 'mcwp_list_entities' ),
                    'space' => esc_html__( 'Space', 'mcwp_list_entities' ),
                    'project' => esc_html__( 'Project', 'mcwp_list_entities' ),
                    'event' => esc_html__( 'Event', 'mcwp_list_entities' ),
                    'subsite' => esc_html__( 'Subsite', 'mcwp_list_entities' ),
                    'seal' => esc_html__( 'Seal', 'mcwp_list_entities' ),
                    ),
                ),
            'fields' => array(
                'label' => esc_html__('Fields', 'mcwp_list_entities'),
                'type' => 'multiple_checkboxes',
                'option_category' => 'basic_option',
                'description' => esc_html__('Fields to be returned.', 'mcwp_list_entities'),
                'options' => array(
                    'name' => esc_html__( 'Name', 'mcwp_list_entities'),
                    'shortDescription'  => esc_html__( 'Short Description', 'mcwp_list_entities'),
                    'singleUrl'  => esc_html__( 'Single URL', 'mcwp_list_entities'),
                    ),
                ),
            'fields' => array(
                'label' => esc_html__('Fields', 'mcwp_list_entities'),
                'type' => 'multiple_checkboxes',
                'option_category' => 'basic_option',
                'description' => esc_html__('Fields to be returned.', 'mcwp_list_entities'),
                'options' => array(
                    'name' => esc_html__( 'Name', 'mcwp_list_entities'),
                    'shortDescription'  => esc_html__( 'Short Description', 'mcwp_list_entities'),
                    'singleUrl'  => esc_html__( 'Single URL', 'mcwp_list_entities'),
                    ),
                ),
            'toggle_sort' => array(
                'label' => esc_html__('Sort results', 'mcwp_list_entities'),
                'type' => 'yes_no_button',
                'option_category' => 'basic_option',
                'description' => esc_html__('Sort the results.', 'mcwp_list_entities'),
                'options' => array(
                    'off' => esc_html__( 'No', 'mcwp_list_entities'),
                    'on'  => esc_html__( 'Yes', 'mcwp_list_entities'),
                    ),
                'affects' => array(
                    '#et_pb_sort_field',
                    '#et_pb_sort_order'
                    ),

                ),
            'sort_field'     => array(
                'label'           => esc_html__( 'Sort field', 'mcwp_list_entities' ),
                'type'            => 'select',
                'option_category' => 'basic_option',
                'options' => array(
                    'id' => esc_html__( 'ID', 'mcwp_list_entities' ),
                    'name' => esc_html__( 'Name', 'mcwp_list_entities' ),
                    ),
                'depends_show_if' => 'on',
                ),
            'sort_order'     => array(
                'label'           => esc_html__( 'Sort order', 'mcwp_list_entities' ),
                'type'            => 'select',
                'option_category' => 'basic_option',
                'options' => array(
                    'DESC' => esc_html__( 'Descending', 'mcwp_list_entities' ),
                    'ASC' => esc_html__( 'Ascending', 'mcwp_list_entities' ),
                    ),
                'depends_show_if' => 'on',
                ),
            'limit'     => array(
                'label'           => esc_html__( 'Limit of results per page', 'mcwp_list_entities' ),
                'type'            => 'range',
                'option_category' => 'basic_option',
                'range_settings'  => array(
                    'min'   => '1',
                    'max'   => '50',
                    'step'  => '1'
                    ),
                ),
            'toggle_pagination' => array(
                'label' => esc_html__('Add pagination', 'mcwp_list_entities'),
                'type' => 'yes_no_button',
                'option_category' => 'basic_option',
                'description' => esc_html__('Add pagination.', 'mcwp_list_entities'),
                'options' => array(
                    'off' => esc_html__( 'No', 'mcwp_list_entities'),
                    'on'  => esc_html__( 'Yes', 'mcwp_list_entities'),
                    ),

                ),
            'toggle_template' => array(
                'label' => esc_html__('Change default template', 'mcwp_list_entities'),
                'type' => 'yes_no_button',
                'option_category' => 'basic_option',
                'description' => esc_html__('Change the rendering template.', 'mcwp_list_entities'),
                'options' => array(
                    'off' => esc_html__( 'No', 'mcwp_list_entities'),
                    'on'  => esc_html__( 'Yes', 'mcwp_list_entities'),
                    ),
                'affects' => array(
                    '#et_pb_template'
                    ),

                ),
            'template'     => array(
                'label'           => esc_html__( 'Template', 'mcwp_list_entities' ),
                'type'            => 'textarea',
                'option_category' => 'basic_option',
                'depends_show_if' => 'on',
                ),
            );
}
function shortcode_callback( $atts, $content = null, $function_name ) {
    $extra_fields_str='';
    $template_str='';

    if ($atts['toggle_sort'] == 'on')
    {
        $extra_fields_str .= " order='".$atts['sort_field']." ".$atts['sort_order']."' ";
    }
    if ($atts['toggle_pagination'] == 'on')
    {
        $extra_fields_str .= " pagination='true' ";
    }
    if (isset($atts['limit']))
    {
        $extra_fields_str .= " limit='".$atts['limit']."' ";
    }
    if ($atts['toggle_template'] == 'on')
    {
        if (isset($atts['template']))
        {
            $template_str = str_replace('%22','"',$atts['template'])."[/list_entities]";
        }
    }
    return do_shortcode("[list_entities url='".$atts['url']."' entity='".$atts['entity']."'".$extra_fields_str."]".$template_str);
}


}
new ET_Builder_Module_Divi_List_Entities;
