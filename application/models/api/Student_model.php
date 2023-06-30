<?php
class Student_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    //getting all the results from the table.

    function get_students()
    {
        $this->db->select('*');
        $this->db->from('tbl_students');
        $query =  $this->db->get();
        return $query->result();
    }

    //insert data into the table
    public function insert_student($data = array())
    {
        return $this->db->insert("tbl_students", $data);
    }

    //delet data
    public function delete_student($student_id)
    {
        $this->db->where('id', $student_id);
        return  $this->db->delete('tbl_students');
    }

    //update a student data
    public function updateStudentData($id, $information)
    {
        $this->db->where('id', $id);
        return  $this->db->update("tbl_students", $information);
    }
}
