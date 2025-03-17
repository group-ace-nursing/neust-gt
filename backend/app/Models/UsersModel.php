<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table      = 'tblusers';
    protected $typeTable  = 'tblusertypes';
    protected $addressTable = 'tbladdresses';
    protected $trainingTable = 'tbltrainings';
    protected $employmentTable = 'tblemployment';
    protected $employabilityTable = 'tblemployability';
    protected $examTable = 'tblexams';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'username',
        'password',
        'fullName',
        'email',
        'contact',
        'telephone',
        'civilStatus',
        'sex',
        'birthDate',
        'courseId',
        'universityId',
        'yearGraduated',
        'educationalStatus',
        'degreeDetails',
        'reasonsTogetTheCourse',
        'isEmployed',
        'reasonNotEmployed',
        'reasonToPursue',
        'reasonToPursueOthers',
        'termsAccepted',
        'surveyTaken',
        'userType'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'createdDate';
    protected $updatedField  = 'updatedDate';
    // protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    public function getUserInfo($where){

        // $query = $this->db->table($this->table)->join($this->addressTable, '')->where('id', $id)->get();
        // $results = $query->getRow();
        // return $results;

        $sql = "SELECT * FROM ".$this->table." a LEFT JOIN tbladdresses b ON a.id = b.gradId WHERE a.id = :id:";
       
        $query = $this->db->query($sql, $where);
        $results = $query->getRow();

        return $results;

    }

    public function getAllUserInfo(){

        $query = $this->db->table($this->table)->get();
        $results = $query->getResult('array');

        $all = array_map(function($el){
            // foreach($el as $key => $val){
            //     $type = $this->db->table($this->typeTable)->where('id', $el['userType'])->get()->getRow();
            //     $el['userTypeDescription'] = $type->description;
            // }
            return $el;
        }, $results);

        return $all;
    }

    public function updateUserInfo($where, $setData){

        $query = $this->db->table($this->table)->set($setData)->where($where)->update();
        return $query ? true : false;

    }

    public function updatePassword($where, $setData){

        $query = $this->db->table($this->table)->set($setData)->where($where)->update();
        return $query ? true : false;

    }

    public function insertTrainings($data){
        $query = $this->db->table($this->trainingTable)->insert($data);
        return $query ? true : false;
    }
    public function insertEmployement($data){
        $query = $this->db->table($this->employmentTable)->insert($data);
        return $query ? true : false;
    }
    public function insertExam($data){
        $query = $this->db->table($this->examTable)->insert($data);
        return $query ? true : false;
    }
    public function insertEmployability($data){
        $query = $this->db->table($this->employabilityTable)->insert($data);
        return $query ? true : false;
    }




}