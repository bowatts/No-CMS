<?php

/**
 * WYSIWYG Editor
 *
 * @author gofrendi
 */
class Wysiwyg extends CMS_Controller {

    public function __construct() {
        parent::__construct();        
    }
    
    private function check_allow(){
        $result = $this->cms_allow_navigate("wysiwyg_index");
        if(!$result){
            redirect('main/login');
        }
        return $result;
    }

    public function index() {
        $data = NULL;
        $data['site_favicon'] = $this->cms_get_config('site_favicon');
        $data['site_logo'] = $this->cms_get_config('site_logo');
        $data['site_name'] = $this->cms_get_config('site_name');
        $data['site_slogan'] = $this->cms_get_config('site_slogan');
        $data['site_footer'] = $this->cms_get_config('site_footer');
        $data['site_theme'] = $this->cms_get_config('site_theme');
        $data['site_logo'] = $this->cms_get_config('site_logo');
        $data['site_favicon'] = $this->cms_get_config('site_favicon');
        
        $data['navigation_list'] =array();
        $this->load->model('wysiwyg/navigation_model');
        $navigation_list = $this->navigation_model->get_all_navigation();
        foreach($navigation_list as $navigation){
            $data['navigation_list'][$navigation["id"]] = 
                '{'.$navigation["name"].'} - '.$navigation["title"];
        }
        
        $data['language_list'] =array();
        $this->load->model('wysiwyg/language_model');
        $language_list = $this->language_model->get_language();
        foreach($language_list as $language){
            $data['language_list'][$language] = $language;
        }
        $data['language'] = $this->cms_get_config('site_language');
        
        $this->view('wysiwyg_index', $data, 'wysiwyg_index');
    }
    
    public function change_name(){
        $this->check_allow();
        $value = $this->input->post('value');
        $this->cms_set_config('site_name', $value);
    }
    public function change_slogan(){
        $this->check_allow();
        $value = $this->input->post('value');
        $this->cms_set_config('site_slogan', $value);
    }
    public function change_footer(){
        $this->check_allow();
        $value = $this->input->post('value');
        $this->cms_set_config('site_footer', $value);
    }
    public function change_language(){
        $this->check_allow();
        $value = $this->input->post('value');
        $this->cms_set_config('site_language', $value);
    }
    
    
    public function get_navigation(){
        $this->check_allow();
        $this->load->model('wysiwyg/navigation_model');
        $result = $this->navigation_model->get_navigation();
        echo json_encode($result);
    }
    
    public function toggle_navigation(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/navigation_model');
        $this->navigation_model->toggle_navigation($id);
    }
    
    public function up_navigation(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/navigation_model');
        $this->navigation_model->up_navigation($id);
    }
    
    public function down_navigation(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/navigation_model');
        $this->navigation_model->down_navigation($id);
    }
    
    public function promote_navigation(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/navigation_model');
        $this->navigation_model->promote_navigation($id);
    }
    
    public function demote_navigation(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/navigation_model');
        $this->navigation_model->demote_navigation($id);
    }
    
    public function get_quicklink(){
        $this->check_allow();
        $this->load->model('wysiwyg/quicklink_model');
        $result = $this->quicklink_model->get_quicklink();
        echo json_encode($result);
    }
    
    public function left_quicklink(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/quicklink_model');
        $this->quicklink_model->left_quicklink($id);
    }
    
    public function right_quicklink(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/quicklink_model');
        $this->quicklink_model->right_quicklink($id);
    }
    
    public function add_quicklink(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/quicklink_model');
        $this->quicklink_model->add_quicklink($id);
    }
    
    public function remove_quicklink(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/quicklink_model');
        $this->quicklink_model->remove_quicklink($id);
    }
    
    public function get_widget($slug){
        $this->check_allow();
        $slug = isset($slug)? $slug : $this->input->post('slug');
        $this->load->model('wysiwyg/widget_model');
        $result = $this->widget_model->get_widget($slug);
        echo json_encode($result);
    }
    
    public function up_widget(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/widget_model');
        $this->widget_model->up_widget($id);
    }
    
    public function down_widget(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/widget_model');
        $this->widget_model->down_widget($id);
    }
    
    public function toggle_widget(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/widget_model');
        $this->widget_model->toggle_widget($id);
    }
    
    public function upload_favicon(){
        $this->check_allow();
        
        include(APPPATH.'../modules/wysiwyg/fileuploader_library/php.php');
        
        // list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $allowedExtensions = array('jpeg', 'jpg', 'gif', 'png', 'ico');
        // max file size in bytes
        $sizeLimit = 10 * 1024 * 1024;

        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload(APPPATH.'../assets/nocms/images/custom_favicon/');
        $this->cms_set_config('site_favicon', '@base_url/assets/nocms/images/custom_favicon/'.$result["filename"]);
        // to pass data through iframe you will need to encode all html tags
        echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
    }
    
    public function upload_logo(){
        $this->check_allow();
        
        include(APPPATH.'../modules/wysiwyg/fileuploader_library/php.php');
        
        // list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $allowedExtensions = array('jpeg', 'jpg', 'gif', 'png', 'ico');
        // max file size in bytes
        $sizeLimit = 10 * 1024 * 1024;

        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);        
        $result = $uploader->handleUpload(APPPATH.'../assets/nocms/images/custom_logo/');
        $this->cms_set_config('site_logo', '@base_url/assets/nocms/images/custom_logo/'.$result["filename"]);
        // to pass data through iframe you will need to encode all html tags
        echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
    }
    
    public function get_favicon(){
        $this->check_allow();
        $result['value'] = $this->cms_get_config('site_favicon');
        echo json_encode($result);
    }
    
    public function get_logo(){
        $this->check_allow();
        $result['value'] = $this->cms_get_config('site_logo');
        echo json_encode($result);
    }
    
    
}