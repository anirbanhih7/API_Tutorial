<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Student extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();

        //to load Database
        $this->load->database();

        //to load database

        $this->load->model(array('api/Student_model'));

        //to load formvalidation library

        $this->load->library(array("form_validation"));

        //for security
        $this->load->helper("security");
    }
    /*
        INSERT : POST REQUEST TYPE
        UPDATE : PUT REQUEST TYPE
        DELETE : DELETE REQUEST TYPE
        LIST   : GET REQUEST TYPE
    */

    //for insert
    public function index_post()
    {

        //collecting data inputs
        $name = $this->security->xss_clean($this->input->post('name'));
        $email = $this->security->xss_clean($this->input->post('email'));
        $mobile = $this->security->xss_clean($this->input->post('mobile'));
        $course = $this->security->xss_clean($this->input->post('course'));

        //form validation for inputs
        $this->form_validation->set_rules('name', "Name", 'required');
        $this->form_validation->set_rules('email', "Email", 'required|valid_email');
        $this->form_validation->set_rules('mobile', "Mobile", 'required');
        $this->form_validation->set_rules('course', "Course", 'required');


        //checking the form submission have any error or not.
        if ($this->form_validation->run() === false) {
            $this->response(array(
                'Status' => 0,
                'messsage' => 'All Fields needed!',
            ), REST_Controller::HTTP_NOT_FOUND);
        } else {
            if (!empty($name) && !empty($email) && !empty($mobile) && !empty($course)) {
                $students = array(
                    'name' => $name,
                    'email' => $email,
                    'mobile' => $mobile,
                    'course' => $course,
                );

                if ($this->Student_model->insert_student($students)) {
                    $this->response(array(
                        'Status' => 1,
                        'messsage' => 'Student Inserted Successfully!',
                    ), REST_Controller::HTTP_OK);
                } else {
                    $this->response(array(
                        'Status' => 0,
                        'messsage' => 'Student Insertion Failed!',
                    ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                $this->response(array(
                    'Status' => 0,
                    'messsage' => 'All Fields needed!',
                    // 'data' => $students
                ), REST_Controller::HTTP_NOT_FOUND);
            }
        }
        /* 
       $data = json_decode(file_get_contents("php://input"));

        $name = isset($data->name) ? $data->name : '';
        $email = isset($data->email) ? $data->email : '';
        $mobile = isset($data->mobile) ? $data->mobile : '';
        $course = isset($data->course) ? $data->course : '';
        //  $status = isset($data->status) ? $data->status : '';
        */
    }

    //for update
    public function index_put()
    {
        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->name) && isset($data->email) && isset($data->mobile) && isset($data->course)) {
            $student_id = $data->id;
            $studentInfo = array(
                'name' => $data->name,
                'email' => $data->email,
                'mobile' => $data->mobile,
                'course' => $data->course,
            );

            if ($this->Student_model->updateStudentData($student_id, $studentInfo)) {
                $this->response(array(
                    'Status' => 1,
                    'messsage' => 'Records Update successfully!',
                ), REST_Controller::HTTP_OK);
            } else {
                $this->response(array(
                    'Status' => 0,
                    'messsage' => 'Updation failed',
                ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            $this->response(array(
                'Status' => 0,
                'messsage' => 'All Fields needed!',
                // 'data' => $students
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    //for delete
    public function index_delete()
    {
        $data = json_decode(file_get_contents("php://input"));
        $student_id = $this->security->xss_clean($data->student_id);


        if ($this->Student_model->delete_student($student_id)) {
            $this->response(array(
                'Status' => 1,
                'messsage' => 'Records Deleted successfully!',
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                'Status' => 0,
                'messsage' => 'Delete operation Failed',
            ), REST_Controller::HTTP_NOT_FOUND);
        }

        // print_r($student_id);
    }

    // for get
    public function index_get()
    {


        $students = $this->Student_model->get_students();
        if (count($students) > 0) {
            $this->response(array(
                'Status' => 1,
                'messsage' => 'Records found successfully!',
                'data' => $students
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                'Status' => 0,
                'messsage' => 'No records found!',
                'data' => $students
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
