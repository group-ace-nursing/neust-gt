<?php namespace App\Controllers;

use CodeIgniter\HTTP\IncomingRequest;
use App\Models\UsersModel;
use App\Models\AuthModel;
use App\Models\MiscModel;
use App\Models\AddressModel;
use \Firebase\JWT\JWT;

class Users extends BaseController
{
    public function __construct(){
        //Models
        $this->userModel = new UsersModel();
        $this->authModel = new AuthModel();
        $this->miscModel = new MiscModel();
        $this->addressModel = new AddressModel();
    }

    public function getUserDetails(){
        //Get API Request Data from NuxtJs
        $data = $this->request->getJSON(); 

        //Select Query for finding User Information
        $user = $this->userModel->getUserInfo(['id' => $data->id]);
        
        //Set Api Response return to the FE
        if($user){

            return $this->response
                        ->setStatusCode(200)
                        ->setContentType('application/json')
                        ->setBody(json_encode($user));
            
        } else {
            $response = [
                'title' => 'Please contact admin',
                'message' => 'Please check your data.'
            ];

            return $this->response
                    ->setStatusCode(404)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
        }


        // print_r(json_encode($data));
        
    }

    public function registerUser(){
        //Get API Request Data from NuxtJs
        $data = $this->request->getJSON(); 
        $data->password = sha1($data->password);
        
        $query = $this->userModel->insert($data);

        if($query){
            $lastId = $this->userModel->getInsertID();
            $this->addressModel->insert([
                'gradId' => $lastId,
                'permanentAddress' => $data->permanentAddress,
                'region' => $data->region,
                'province' => $data->province,
                'locality' => $data->locality,
            ]);

            $response = [
                'title' => 'Registration Complete',
                'message' => 'User data has been successfully submitted.'
            ];
 
            return $this->response
                    ->setStatusCode(200)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
            
        } else {
            $response = [
                'title' => 'Registration Failed!',
                'message' => 'Please check your data.'
            ];
 
            return $this->response
                    ->setStatusCode(400)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
        }
        // print_r($data);
        // exit();
        
    }

    public function submitSurvey(){
        //Get API Request Data from NuxtJs
        $data = $this->request->getJSON();
        
        // First Update the table user for some details
        // degreeDetails, reasonsTogetTheCourse, isEmployed, reasonToPursue, reasonToPursueOthers, surveyTaken
        $where = [
            "id" => $data->userId
        ];
        $setData = [
            "degreeDetails" => json_encode($data->degreeDetails),
            "reasonsTogetTheCourse" => json_encode($data->reasonsTogetTheCourse),
            "isEmployed" => $data->isEmployed,
            "reasonToPursue" => json_encode($data->reasonToPursue),
            "reasonToPursueOthers" => $data->reasonToPursueOthers,
            "surveyTaken" => 2
        ];
        
        $query = $this->userModel->updateUserInfo($where, $setData);
        
        if($query){

            // Inserts on tbl Exam & Employment & Trainings
            foreach($data->trainings as $key => $value){
                $value->gradId = $data->userId;
                $trainings = json_decode(json_encode($value), true);
                
                $this->userModel->insertTrainings($trainings);
            }

            foreach($data->examPassed as $key => $value){
                $value->gradId = $data->userId;
                $exam = json_decode(json_encode($value), true);
                
                $this->userModel->insertExam($exam);
            }

            $employeeData = [
                'gradId' => $data->userId,
                'employmentStatus' => $data->employmentStatus,
                'reasonNotEmployed' => json_encode($data->reasonNotEmployed),
                'othersReason' => $data->othersReason,
                'occupation' => $data->occupation,
                'ifSelfEmployed' => $data->ifSelfEmployed,
                'typeOfCompany' => $data->typeOfCompany,
                'placeOfWork' => $data->placeOfWork,
                'isFirstJob' => $data->isFirstJob,
                'reasonsToStayJob' => json_encode($data->reasonToStayJob),
                'reasonToStayJobOthers' => $data->reasonToStayJobOthers,
                'reasonToAcceptJob' => json_encode($data->reasonToAcceptJob),
                'reasonToAcceptJobOthers' => $data->reasonToAcceptJobOthers,
                'reasonToChangeJob' => json_encode($data->reasonToChangeJob),
                'reasonToChangeJobOthers' => $data->reasonToChangeJobOthers,
                'stayInJob' => $data->stayInJob,
                'stayInJobOthers' => $data->stayInJobOthers,
                'howLongToGetJob' => $data->howLongToGetJob,
                'howLongToGetJobOthers' => $data->howLongToGetJobOthers,
                'howFirstJobFind' => $data->howFirstJobFind,
                'howFirstJobFindOthers' => $data->howFirstJobFindOthers,
                'jobLevel' => json_encode($data->jobLevel),
                'grossMonthly' => $data->grossMonthly,
                'competenciesLearn' => json_encode($data->competenciesLearn),
                'competenciesLearnOthers' => $data->competenciesLearnOthers,
                'recommendation' => $data->recommendation
            ];
            $this->userModel->insertEmployement($employeeData);


            $empbilityData =  [
                'gradId' => $data->userId,
                "response" => json_encode($data->employability)
            ];
            $this->userModel->insertEmployability($empbilityData);


            $response = [
                'title' => 'Survey Submitted',
                'message' => 'User data has been successfully saved.'
            ];
 
            return $this->response
                    ->setStatusCode(200)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
            
        } else {
            $response = [
                'title' => 'Registration Failed!',
                'message' => 'Please check your data.'
            ];
 
            return $this->response
                    ->setStatusCode(400)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
        }
    }
    
    public function updateUser(){
        //Get API Request Data from NuxtJs
        $data = $this->request->getJSON();
        $where = [
            "id" => $data->uid
        ];
        $setData = [
            "firstName" => $data->firstName,
            "lastName" => $data->lastName,
            "middleName" => $data->middleName,
            "suffix" => $data->suffix,
            "email" => $data->email,
            "contact" => $data->contact,
            "userType" => $data->userType,
        ];
        
        $query = $this->userModel->updateUserInfo($where, $setData);

        if($query){

            $response = [
                'title' => 'Update Information',
                'message' => 'User data has been successfully updated.'
            ];
 
            return $this->response
                    ->setStatusCode(200)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
            
        } else {
            $response = [
                'title' => 'Registration Failed!',
                'message' => 'Please check your data.'
            ];
 
            return $this->response
                    ->setStatusCode(400)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
        }
        // print_r($data);
        // exit();
        
    }
    public function ChangePassword(){
        
        //Get API Request Data from NuxtJs
        $payload = $this->request->getJSON();
        $payload->newPassword = sha1($payload->newPassword);

        $where = ['id' => $payload->id];
        $updateData = ['password' => $payload->newPassword];

        $updatePassword =  $this->userModel->updatePassword($where, $updateData);

        if($updatePassword){
            $response = [
                'title' => 'Change Password',
                'message' => 'Your successfully change password.'
            ];
 
            return $this->response
                    ->setStatusCode(200)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
        } else {
            $response = [
                'title' => 'Change Password Failed!',
                'message' => 'Please check your data.'
            ];
 
            return $this->response
                    ->setStatusCode(400)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
        }
    }
    public function updateUserStatus(){
        //Get API Request Data from NuxtJs
        $data = $this->request->getJSON();
        $where = [
            "id" => $data->uid
        ];
        $setData = [
            "status" => $data->status,
        ];
        
        $query = $this->userModel->updateUserInfo($where, $setData);

        if($query){

            $response = [
                'title' => 'Update Information',
                'message' => 'User data has been successfully updated.'
            ];
 
            return $this->response
                    ->setStatusCode(200)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
            
        } else {
            $response = [
                'title' => 'Registration Failed!',
                'message' => 'Please check your data.'
            ];
 
            return $this->response
                    ->setStatusCode(400)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
        }
        // print_r($data);
        // exit();
        
    }
    
    public function getAllUserList(){

        // $header = $this->request->getHeader("");
        
        $list = [];
        $list['list'] = $this->userModel->getAllUserInfo();

        if($list){
            return $this->response
                    ->setStatusCode(200)
                    ->setContentType('application/json')
                    ->setBody(json_encode($list));
        } else {
            $response = [
                'title' => 'Error',
                'message' => 'No Data Found'
            ];

            return $this->response
                    ->setStatusCode(400)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
        }
    }


    public function getListOfSurvey(){

        // $header = $this->request->getHeader("");
        
        $list = [];
        $query = $this->userModel->getSurveyResponses();

        foreach ($query as $key => $value){
            // Exaination
            $examsName = [];
            $datesTaken = [];
            $ratings = [];
            foreach ($value->examList as $tkey => $tvalue) {
                $examsName[$tkey] = $tvalue->examName ?? 'N/A'; // Assuming 'title' is a property
                $datesTaken[$tkey] = $tvalue->dateTaken ?? 'N/A'; // Assuming 'duration' is a property
                $ratings[$tkey] = $tvalue->rating ?? 'N/A'; // Assuming 'institution' is a property
            }
            
            // Combine the training details into a single array
            $examinations = [
                'Name of Examination' => implode(', ', $examsName),
                'Date Taken' => implode(', ', $datesTaken),
                'Rating' => implode(', ', $ratings),
            ];


            //reason_for_taking_the_course
            $reason_for_taking_the_course = [];
            foreach ($value->reasonsTogetTheCourse as $rkey => $rvalue) {
                $keyVal = 'Reason_for_taking_the_course_or_pursuing_degree - '. $rvalue->label;
                $resValue = "";
                if($rvalue->valueUndergrd){
                    $resValue = "Undergraduate/AB/BS (Bachelor of Arts/Bachelor of Science)";
                } else if($rvalue->valueUndergrd){
                    $resValue = "Graduate/MS/MA/PhD (Master of Science/Masteral/Doctorate)";
                }

                $reason_for_taking_the_course[$keyVal] = $resValue;
            } 

            // training details
            
            $titlesTraining = [];
            $durationTraining = [];
            $instiTraining = [];
            foreach ($value->trainingList as $tkey => $tvalue) {
                $titlesTraining[$tkey] = $tvalue->title ?? 'N/A'; // Assuming 'title' is a property
                $durationTraining[$tkey] = $tvalue->duration ?? 'N/A'; // Assuming 'duration' is a property
                $instiTraining[$tkey] = $tvalue->institution ?? 'N/A'; // Assuming 'institution' is a property
            }
            
            // Combine the training details into a single array
            $training_details = [
                'Title of Training or Advance Study' => implode(', ', $titlesTraining),
                'Duration and Credits Earned' => implode(', ', $durationTraining),
                'Name of Training Institution/College/University' => implode(', ', $instiTraining),
            ];

            //  Employment
            $employment = [
                'What made you pursue advanced studies' => $value->reasonToPursue ?? $value->reasonToPursueOthers,
                'Are you presently employed' => $value->isEmployed,
                'Reason(s) why you are not yet employed' => implode(', ', $value->reasonNotEmployed) ?? $value->otherReason,
                'Present Employment Status' => $value->employmentStatus,
                'If self-employed, what skills acquired in college were you able to apply in your work' => $value->ifSelfEmployed,
                'Present occupation' => $value->occupation,
                'Major line of business of the company are presently employed' => $value->typeOfCompany,
                'Place of work' => $value->placeOfWork,
                'Is first job after college' => $value->isFirstJob,
                'Reason(s) for staying on the job' => implode(', ', $value->reasonsToStayJob) ?? $value->reasonsToStayJobOthers,
                'Reasons for accepting the job' => implode(', ', $value->reasonToAcceptJob) ?? $value->reasonToAcceptJobOthers,
                'Reason(s) for changing your job' => implode(', ', $value->reasonToChangeJob) ?? $value->reasonToChangeJobOthers,
                'Stay in first job' => $value->stayInJob ?? $value->stayInJobOthers,
                'How did you find your first job' => $value->howFirstJobFind ?? $value->howFirstJobFindOthers,
                'How long did it take you to land your first job' => $value->howLongToGetJob ?? $value->howLongToGetJobOthers
            ];


            // Job Level
            $job_level = [];
            foreach ($value->jobLevel as $jkey => $jvalue) {
                $keyjVal = 'Job Level ['. $jvalue->label .']';
                $resjValue = "";
                if($jvalue->firstJob){
                    $resjValue = "First Job";
                } else if($jvalue->presentJob){
                    $resjValue = "Present Job";
                } else if($jvalue->firstJob && $jvalue->presentJob) {
                    $resjValue = "First Job & Present Job";
                }

                $job_level[$keyjVal] = $resjValue;
            } 

            // Employment 2
            $employment2 = [
                'Initial gross monthly earnings in your first job after college' => $value->grossMonthly
            ];
            

            // Employability
            $employability = [];
            foreach ($value->response as $ekey => $evalue) {
                foreach ($evalue->questions as $qkey => $qvalue) {
                    $keyqVal = $evalue->title .' ['. $qvalue->title .']';
                    $resqValue = $qvalue->score;

                    $employability[$keyqVal] = $resqValue;
                }
            } 

            // competencies
            $competencies = [
                'Competencies learned in college did you find very useful in your first job' => implode(', ', $value->competenciesLearn) ?? $value->competenciesLearnOthers,
                'List down suggestions to further improve your program curriculum.' => $value->recommendation
            ];
            
            
            $list['list'][$key] = array_merge([
                'Timestamp' => $value->survey_timestamp,
                'Name' => $value->fullName,
                'Permanent Address' => $value->permanentAddress,
                'Email' => $value->email,
                'Telephone' => $value->telephone,
                'Mobile' => $value->contact,
                'Civil Status' => $value->civilStatus,
                'Sex' => $value->sex,
                'Birthday' => $value->birthDate,
                'Region Of Origin' => $value->region,
                'Province' => $value->province,
                'Location Of Residence' => $value->locality,
                'Degree And Specialization' => $value->degreeDetails[0]->degree,
                'College Or University' => $value->degreeDetails[0]->university,
                'Year Graduate' => $value->degreeDetails[0]->year,
                'Honor Or Awards Received' => $value->degreeDetails[0]->awards,
                
            ], $examinations, $reason_for_taking_the_course, $training_details, $employment, $job_level, $employment2, $employability, $competencies);
        }

        if($list){
            return $this->response
                    ->setStatusCode(200)
                    ->setContentType('application/json')
                    ->setBody(json_encode($list));
        } else {
            $response = [
                'title' => 'Error',
                'message' => 'No Data Found'
            ];

            return $this->response
                    ->setStatusCode(400)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
        }
    }

    public function getListOfSurveyNoRespond(){

        // $header = $this->request->getHeader("");
        
        $list = [];
        $query = $this->userModel->getSurveyNoResponses();

        foreach ($query as $key => $value){

            
            $list['list'][$key] = [
                'name' => $value->fullName,
                'email' => $value->email,
                'telephone' => $value->telephone,
                'mobile' => $value->contact,
                'civilStatus' => $value->civilStatus,
                'sex' => $value->sex,
                'birthday' => $value->birthDate,
                'degree_and_specialization' => $value->degreeDetails[0]->degree ?? 'N/A',
                'college_or_university' => $value->degreeDetails[0]->university ?? 'N/A',
                'year_Graduate' => $value->degreeDetails[0]->year ??  $value->yearGraduated,
                'honor_or_awards_received' => $value->degreeDetails[0]->awards ?? 'N/A',
            ];
        }

        if($list){
            return $this->response
                    ->setStatusCode(200)
                    ->setContentType('application/json')
                    ->setBody(json_encode($list));
        } else {
            $response = [
                'title' => 'Error',
                'message' => 'No Data Found'
            ];

            return $this->response
                    ->setStatusCode(400)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
        }
    }


    public function getAllInactiveUserList(){
        
        $list = [];
        $list['list'] = $this->userModel->getAllUserInfo(['userType' => 1, 'status' => 0]);

        if($list){
            return $this->response
                    ->setStatusCode(200)
                    ->setContentType('application/json')
                    ->setBody(json_encode($list));
        } else {
            $response = [
                'title' => 'Error',
                'message' => 'No Data Found'
            ];

            return $this->response
                    ->setStatusCode(400)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
        }
    } 

    public function updateTenantStatus(){
        
        //Get API Request Data from NuxtJs
        $data = $this->request->getJSON();

        $where = ['id' => $data->userId];
        
        if($data->action == 'ACTIVE'){
            $setData = [
                'status' => 1,
                'reasonVacancy' => $data->reason,
            ];
        } else if ($data->action == 'DEACTIVE') {
            $setData = [
                'status' => 0,
                'reasonVacancy' => $data->reason,
            ];
        } else if ($data->action == 'VACATE') {
            $setData = [
                'status' => 2,
                'isFirstLogin' => 0,
                'premise' => '',
                'password' => '',
                'reasonVacancy' => $data->reason
            ];
        } else if($data->action == 'RENEW'){
            $setData = [
                'status' => 1,
                'startDate' =>  date('j-M-y', strtotime($data->startDate)),
                'endDate' => date('j-M-y', strtotime($data->endDate)),
                'reasonVacancy' => $data->reason
            ];
        } else if($data->action == 'RESET'){
            $setData = [
                'status' => 1,
                'isFirstLogin' => 0,
                'password' => 'password',
                'reasonVacancy' => $data->reason
            ];
        } else {
            $setData = [
                'reasonVacancy' => 'Something went wrong',
            ];
        }

        $update = $this->userModel->updateTenantInfo($where, $setData);

        if($update){
            if($data->action == 'VACATE'){
                $whereU = ['id' => $data->buildingId];
                $updateData = ['userId' => 0];
                $this->buildingModel->updateBuildingInfo($whereU, $updateData);
            }
            $response = [
                'title' => 'Tenant Information',
                'message' => 'Tenant details successfully updated'
            ];
 
            return $this->response
                    ->setStatusCode(200)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
        } else {
            $response = [
                'title' => 'Update Failed!',
                'message' => 'Please check your data.'
            ];
 
            return $this->response
                    ->setStatusCode(400)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
        }
    }

    public function assignExistingTenant(){
        
        //Get API Request Data from NuxtJs
        $data = $this->request->getJSON();

        $where = ['id' => $data->userId];
        
        $setData = [
            'status' => 1,
            'isFirstLogin' => 0,
            'premise' => $data->premise,
            'password' => 'password',
            'startDate' => $data->startDate,
            'endDate' => $data->endDate,
        ];

        $update = $this->userModel->updateTenantInfo($where, $setData);

        if($update){
            if($data->action == 'VACANT'){
                $whereU = ['id' => $data->buildingId];
                $updateData = ['userId' => $data->userId];
                $this->buildingModel->updateBuildingInfo($whereU, $updateData);
            }
            $response = [
                'title' => 'Tenant Information',
                'message' => 'Tenant details successfully updated'
            ];
 
            return $this->response
                    ->setStatusCode(200)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
        } else {
            $response = [
                'title' => 'Update Failed!',
                'message' => 'Please check your data.'
            ];
 
            return $this->response
                    ->setStatusCode(400)
                    ->setContentType('application/json')
                    ->setBody(json_encode($response));
        }
    }

}