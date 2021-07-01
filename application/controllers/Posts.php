<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('post');
        // load CSV library
        $this->load->library('CSVReader');
        // Load file helper
        $this->load->helper('file');
    }
    
    public function index(){
        $data = array();
        
        //get messages from the session
        if($this->session->userdata('success_msg')){
            $data['success_msg'] = $this->session->userdata('success_msg');
            $this->session->unset_userdata('success_msg');
        }
        if($this->session->userdata('error_msg')){
            $data['error_msg'] = $this->session->userdata('error_msg');
            $this->session->unset_userdata('error_msg');
        }

        $data['posts'] = $this->post->getRows();
        $data['title'] = 'Post Archive';
        
        //load the list page view
        $this->load->view('templates/header', $data);
        $this->load->view('posts/index', $data);
        $this->load->view('templates/footer');
    }
    
    /*
     * Post details
     */
    public function view($id){
        $data = array();
        
        //check whether post id is not empty
        if(!empty($id)){
            $data['post'] = $this->post->getRows($id);
            $data['title'] = $data['post']['title'];
            
            //load the details page view
            $this->load->view('templates/header', $data);
            $this->load->view('posts/view', $data);
            $this->load->view('templates/footer');
        }else{
            redirect('/posts');
        }
    }
    
    /*
     * Add post content
     */
    public function add(){
        $data = array();
        $postData = array();
        
        //if add request is submitted
        if($this->input->post('postSubmit')){
            //form field validation rules
            $this->form_validation->set_rules('title', 'post title', 'required');
            $this->form_validation->set_rules('content', 'post content', 'required');
            
            //prepare post data
            $postData = array(
                'title' => $this->input->post('title'),
                'content' => $this->input->post('content')
            );
            
            //validate submitted form data
            if($this->form_validation->run() == true){
                //insert post data
                $insert = $this->post->insert($postData);

                if($insert){
                    $this->session->set_userdata('success_msg', 'Post has been added successfully.');
                    redirect('/posts');
                }else{
                    $data['error_msg'] = 'Some problems occurred, please try again.';
                }
            }
        }
        
        $data['post'] = $postData;
        $data['title'] = 'Create Post';
        $data['action'] = 'Add';
        
        //load the add page view
        $this->load->view('templates/header', $data);
        $this->load->view('posts/add-edit', $data);
        $this->load->view('templates/footer');
    }
    
    /*
     * Update post content
     */
    public function edit($id){
        $data = array();
        
        //get post data
        $postData = $this->post->getRows($id);
        
        //if update request is submitted
        if($this->input->post('postSubmit')){
            //form field validation rules
            $this->form_validation->set_rules('title', 'post title', 'required');
            $this->form_validation->set_rules('content', 'post content', 'required');
            
            //prepare cms page data
            $postData = array(
                'title' => $this->input->post('title'),
                'content' => $this->input->post('content')
            );
            
            //validate submitted form data
            if($this->form_validation->run() == true){
                //update post data
                $update = $this->post->update($postData, $id);

                if($update){
                    $this->session->set_userdata('success_msg', 'Post has been updated successfully.');
                    redirect('/posts');
                }else{
                    $data['error_msg'] = 'Some problems occurred, please try again.';
                }
            }
        }

        
        $data['post'] = $postData;
        $data['title'] = 'Update Post';
        $data['action'] = 'Edit';
        
        //load the edit page view
        $this->load->view('templates/header', $data);
        $this->load->view('posts/add-edit', $data);
        $this->load->view('templates/footer');
    }
    
    /*
     * Delete post data
     */
    public function delete($id){
        //check whether post id is not empty
        if($id){
            //delete post
            $delete = $this->post->delete($id);
            
            if($delete){
                $this->session->set_userdata('success_msg', 'Post has been removed successfully.');
            } else {
                $this->session->set_userdata('error_msg', 'Some problems occurred, please try again.');
            }
        }

        redirect('/posts');
    }

    /*
     * import posts
     */
    public function import() {
        $postData = array();
        
        $data['post'] = $postData;
        $data['title'] = 'Import Posts';
        $data['action'] = 'Import';

        //load the add page view
        $this->load->view('templates/header', $data);
        $this->load->view('posts/import', $data);
        $this->load->view('templates/footer');
    }

    /*
     * Save posts
     */
    public function save() {
        $this->form_validation->set_rules('fileURL', 'Upload File', 'callback_checkFileValidation');
        if($this->form_validation->run() == false) {
            $this->session->set_userdata('error_msg', 'Some problems occurred, please try again.');
            redirect('posts/import');
        } else {
           // If file uploaded
           if(is_uploaded_file($_FILES['fileURL']['tmp_name'])) {                            
               // Parse data from CSV file
               $csvData = $this->csvreader->parse_csv($_FILES['fileURL']['tmp_name']);            
               // create array from CSV file
               if(!empty($csvData)){
                   foreach($csvData as $element){                    
                       // Prepare data for DB insertion
                       $data[] = array(
                           'title' => $element['Title'],
                           'content' => $element['Content'],
                       );
                   }
               }
           }

           // insert/update data into database
           foreach($data as $element) {
                $newData = array(
                    'title' => $element['title'],
                    'content' => $element['content']
                );
                $this->post->insert($newData);
           }
           $this->session->set_userdata('success_msg', 'Ports Import successfully.');
           redirect('/posts');
        }              
    }

    /*
     * Check file validation
     */
    public function checkFileValidation($str) {
        $mime_types = array(
            'text/csv',
            'text/x-csv', 
            'application/csv', 
            'application/x-csv', 
            'application/excel',
            'text/x-comma-separated-values', 
            'text/comma-separated-values', 
            'application/octet-stream', 
            'application/vnd.ms-excel',
            'application/vnd.msexcel', 
            'text/plain',
        );
        if(isset($_FILES['fileURL']['name']) && $_FILES['fileURL']['name'] != ""){
            // get mime by extension
            $mime = get_mime_by_extension($_FILES['fileURL']['name']);
            $fileExt = explode('.', $_FILES['fileURL']['name']);
            $ext = end($fileExt);
            if(($ext == 'csv') && in_array($mime, $mime_types)){
                return true;
            } else {
                $this->form_validation->set_message('checkFileValidation', 'Please choose correct file.');
                return false;
            }
        } else {
            $this->form_validation->set_message('checkFileValidation', 'Please choose a file.');
            return false;
        }
    }

    /*
     * Export data
     */
    public function exportData() {
        $storData = array();
        $metaData[] = array('title' => 'Title', 'content' => 'Content');
        $posts = $this->post->getRows(); 
        foreach($posts as $key=>$element) {
            $storData[] = array(
                'Title' => $element['title'],
                'Content' => $element['content'],
            );
        }
        $data = array_merge($metaData,$storData);
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"csv-sample-posts".".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        foreach ($data as $data) {
            fputcsv($handle, $data);
        }
            fclose($handle);
        exit;
    }

}