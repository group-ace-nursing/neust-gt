<template>
    <div class="q-pa-md" style="width: 100%;">
        <div class="row">
            <div class="col-12 text-h5 q-mb-md">
                Survey Form
            </div>
        </div>

        <q-stepper
            v-model="step"
            color="primary"
            animated
        >
            <q-step
                :name="1"
                title="Educational Background"
                icon="school"
                :done="step > 1"
            >
                <div class="row">
                    <div class="col-12 text-h6">
                        Please provide the required information regarding your educational background. Fill in the blanks accurately and check (✔) the appropriate boxes where applicable.

                    </div>
                    <div class="col-12 q-mt-md">
                        <q-table
                            :rows="form.degreeDetails"
                            :columns="degreeColumns"
                            row-key="degree"
                        >
                            <template v-slot:top>
                                <label class="text-h6">Educational Attainment (Baccalaureate Degree only)</label>
                                <q-space />
                            </template>
                            <template v-slot:body-cell-awards="props">
                                <q-td key="awards" :props="props">
                                    <q-input
                                        dense
                                        v-model="props.row.awards"
                                        placeholder="Awards"
                                        outlined 
                                        stack-label
                                    ></q-input>
                                </q-td>
                            </template>
                        </q-table>
                    </div>
                    <div class="col-12 q-mt-md">
                        <q-table
                            :rows="form.examPassed"
                            :columns="examsColumns"
                            row-key="degree"
                        >
                            <template v-slot:top>
                                <label class="text-h6">Professional Examinations Passed</label>
                                <q-space />
                                <q-btn @click="addExam" size="sm" color="primary" label="Add Exam" />
                            </template>
                            <template v-slot:body-cell-examName="props">
                                <q-td key="examName" :props="props">
                                    <q-input
                                        dense
                                        v-model="props.row.examName"
                                        placeholder="Exam Name"
                                        outlined 
                                        stack-label
                                    ></q-input>
                                </q-td>
                            </template>
                            <template v-slot:body-cell-dateTaken="props">
                                <q-td key="dateTaken" :props="props">
                                    <q-input
                                        dense
                                        type="date"
                                        v-model="props.row.dateTaken"
                                        placeholder="MM/DD/YYYY"
                                        outlined 
                                        stack-label
                                    ></q-input>
                                </q-td>
                            </template>
                            <template v-slot:body-cell-ratings="props">
                                <q-td key="ratings" :props="props">
                                    <q-input
                                        dense
                                        v-model="props.row.ratings"
                                        placeholder="Rating"
                                        outlined 
                                        stack-label
                                    ></q-input>
                                </q-td>
                            </template>
                            <template v-slot:body-cell-action="props">
                                <q-td key="action" :props="props">
                                    <q-btn @click="removeExam(props.row)" size="sm" color="negative" icon="delete" />
                                </q-td>
                            </template>
                        </q-table>
                    </div>
                    <div class="col-12 q-mt-md">
                        <q-table
                            :pagination="initialPagination"
                            :rows="form.reasonsTogetTheCourse"
                            :columns="reasonsColumns"
                            row-key="degree"
                        >
                            <template v-slot:top>
                                <label class="text-h6">Reason(s) for taking the course(s) or pursuing degree(s). You may check more than one answer</label>
                            </template>
                            <template v-slot:body-cell-label="props">
                                <q-td key="label" :props="props">
                                    <span v-if="props.row.label !== 'Others, please specify: '">{{ props.row.label }}</span>
                                    <div v-else>
                                        <q-input
                                            dense
                                            v-model="props.row.label"
                                            outlined 
                                            stack-label
                                        ></q-input>
                                    </div>
                                </q-td>
                            </template>
                            <template v-slot:body-cell-valueUndergrd="props">
                                <q-td key="valueUndergrd" :props="props">
                                    <q-checkbox v-model="props.row.valueUndergrd" color="orange" />
                                </q-td>
                            </template>
                            <template v-slot:body-cell-valueMasteral="props">
                                <q-td key="valueMasteral" :props="props">
                                    <q-checkbox v-model="props.row.valueMasteral" color="orange" />
                                </q-td>
                            </template>
                            
                        </q-table>
                    </div>
                    <div class="col-12 q-mt-md">
                        <q-table
                            :rows="form.trainings"
                            :columns="trainingColumns"
                            row-key="degree"
                        >
                            <template v-slot:top>
                                <label class="text-h6">Training(s) or Advance Studies Attended After College</label>
                                <q-space />
                                <q-btn @click="addTraining" size="sm" color="primary" label="Add Training" />
                            </template>
                            <template v-slot:body-cell-trainingTitle="props">
                                <q-td key="trainingTitle" :props="props">
                                    <q-input
                                        dense
                                        v-model="props.row.trainingTitle"
                                        outlined 
                                        stack-label
                                    ></q-input>
                                </q-td>
                            </template>
                            <template v-slot:body-cell-trainingDuration="props">
                                <q-td key="trainingDuration" :props="props">
                                    <q-input
                                        dense
                                        v-model="props.row.trainingDuration"
                                        outlined 
                                        stack-label
                                    ></q-input>
                                </q-td>
                            </template>
                            <template v-slot:body-cell-trainingInstitution="props">
                                <q-td key="trainingInstitution" :props="props">
                                    <q-input
                                        dense
                                        v-model="props.row.trainingInstitution"
                                        outlined 
                                        stack-label
                                    ></q-input>
                                </q-td>
                            </template>
                            <template v-slot:body-cell-action="props">
                                <q-td key="action" :props="props">
                                    <q-btn @click="removeTraining(props.row)" size="sm" color="negative" icon="delete" />
                                </q-td>
                            </template>
                        </q-table>
                    </div>
                    <div class="col-12 q-mt-md">
                        <span class="text-h6">What made you pursue advanced studies?*</span>
                        <q-select 
                            outlined 
                            dense
                            v-model="form.reasonToPursue" 
                            :options="pursueOpt"
                            stack-label 
                            options-dense
                        >
                        </q-select>

                        <div v-if="form.reasonToPursue === 'Others, please specify'">
                            <q-input
                                dense
                                v-model="form.reasonToPursueOthers"
                                outlined 
                                stack-label
                            ></q-input>
                        </div>
                    </div>
                </div>

                <q-stepper-navigation>
                    <q-btn @click="step = 2" color="primary" label="Continue" />
                </q-stepper-navigation>
            </q-step>

            <q-step
                :name="2"
                title="Employment"
                icon="business_center"
                :done="step > 2"
            >
            <div class="row">
                    <div class="col-12 text-h6">
                        Please provide information about your current employment status. If you are not
                        employed or have never been employed, proceed to Question 18 and check (✔) all
                        applicable reasons. If you are employed, proceed to Questions 19 to 22 and provide the
                        necessary details. Check (✔) the appropriate boxes and fill in the blanks where needed.

                    </div>
                    <div class="col-3 q-mt-md q-pa-sm">
                        <span class="text-bold">Are you presently employed?</span>
                        <q-select 
                            outlined 
                            dense
                            v-model="form.isEmployed" 
                            :options="['Yes', 'No', 'Never Employed']"
                            stack-label 
                            options-dense
                        >
                        </q-select>
                    </div>
                    <div class="col-9 q-mt-md q-pa-sm">
                        <div v-if="form.isEmployed === 'Never Employed' || form.isEmployed === 'No'">
                            <span class="text-bold">Please state the reason(s) why you are not yet employed. You may select more than one answer.</span>
                            <div class="q-gutter-sm">
                                <q-checkbox 
                                    v-for="(item, index) in checklistRNE" 
                                    :key="index" 
                                    v-model="form.reasonNotEmployed" 
                                    :val="item.value" 
                                    :label="item.label" 
                                    color="orange" 
                                />
                            </div>
                            <div class="q-mt-sm" v-if="form.reasonNotEmployed === 'Others, please specify'">
                                <q-input
                                    dense
                                    v-model="form.othersReason"
                                    outlined 
                                    stack-label
                                ></q-input>
                            </div>
                        </div>
                        <div v-else>
                            <span class="text-bold">Present Employment Status</span>
                            <div class="q-gutter-sm">
                                <q-radio 
                                    v-for="(item, index) in checklistEmployStats" 
                                    :key="index" 
                                    v-model="form.employmentStatus" 
                                    :val="item.value" 
                                    :label="item.label" 
                                    color="orange" 
                                />
                            </div>
                            <div class="q-mt-sm" v-if="form.employmentStatus === 'Self-employed'">
                                <span class="text-bold">If self-employed, what skills acquired in college were you able to apply inyour work?</span>
                                
                                <q-input
                                    dense
                                    v-model="form.ifSelfEmployed"
                                    outlined 
                                    stack-label
                                ></q-input>
                            </div>
                            <q-space />
                            <div class="q-mt-sm">
                                <span class="text-bold">Present occupation (Ex. Grade School Teacher, Electrical Engineer, Self-employed)?</span>
                                
                                <q-input
                                    dense
                                    v-model="form.occupation"
                                    outlined 
                                    stack-label
                                ></q-input>
                            </div>
                            <q-space />
                            <div class="q-mt-sm">
                                <span class="text-bold">Major line of business of the company you are presently employed in. Select one only</span>
                                
                                <q-select 
                                    outlined 
                                    dense
                                    v-model="form.typeOfCompany" 
                                    :options="companyTypeOpt"
                                    stack-label 
                                    options-dense
                                >
                                </q-select>
                            </div>
                            <q-space />
                            <div class="q-mt-sm">
                                <span class="text-bold">Place of Work</span>
                                <div class="q-gutter-sm">
                                    <q-radio 
                                        v-for="(item, index) in ['Local', 'Abroad']" 
                                        :key="index" 
                                        v-model="form.placeOfWork" 
                                        :val="item" 
                                        :label="item" 
                                        color="orange" 
                                    />
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-bold q-mb-md">
                        Please provide details about your employment history. If this is your first job after college
                    </div>
                    <div class="col-3">
                        
                        <div class="q-gutter-sm">
                            <span class="text-bold">Is this your first job after college?</span>
                            <q-radio 
                                v-for="(item, index) in ['Yes', 'No']" 
                                :key="index" 
                                v-model="form.isFirstJob" 
                                :val="item" 
                                :label="item" 
                                color="orange" 
                            />
                        </div>
                    </div>
                    <div class="col-9">
                        <div v-if="form.isFirstJob === 'Yes'">
                            <span class="text-bold">What were your reason(s) for staying your job? You may select more than one answer.</span>
                            <div class="q-gutter-sm">
                                <q-checkbox 
                                    v-for="(item, index) in getChecklist.stayingJobReason" 
                                    :key="index" 
                                    v-model="form.reasonToStayJob" 
                                    :val="item" 
                                    :label="item" 
                                    color="orange" 
                                />
                            </div>
                            <div class="q-mt-sm" v-if="form.reasonToStayJob.includes('Other reason(s), please specify')">
                                <q-input
                                    dense
                                    v-model="form.reasonToStayJobOthers"
                                    outlined 
                                    stack-label
                                ></q-input>
                            </div>
                        </div>
                        <div class="q-mt-md" v-if="form.isFirstJob === 'Yes'">
                            <span class="text-bold">What were your reasons for accepting the job? You may select more than one answer</span>
                            <div class="q-gutter-sm">
                                <q-checkbox 
                                    v-for="(item, index) in getChecklist.acceptingChangingJobReason" 
                                    :key="index" 
                                    v-model="form.reasonToAcceptJob" 
                                    :val="item" 
                                    :label="item" 
                                    color="orange" 
                                />
                            </div>
                            <div class="q-mt-sm" v-if="form.reasonToAcceptJob.includes('Other reason(s), please specify')">
                                <q-input
                                    dense
                                    v-model="form.reasonToAcceptJobOthers"
                                    outlined 
                                    stack-label
                                ></q-input>
                            </div>
                        </div>
                        <div class="q-mt-md" v-if="form.isFirstJob === 'Yes'">
                            <span class="text-bold">What were your reason(s) for changing your job? You may select more than one answer.</span>
                            <div class="q-gutter-sm">
                                <q-checkbox 
                                    v-for="(item, index) in getChecklist.acceptingChangingJobReason" 
                                    :key="index" 
                                    v-model="form.reasonToChangeJob" 
                                    :val="item" 
                                    :label="item" 
                                    color="orange" 
                                />
                            </div>
                            <div class="q-mt-sm" v-if="form.reasonToChangeJob.includes('Other reason(s), please specify')">
                                <q-input
                                    dense
                                    v-model="form.reasonToChangeJobOthers"
                                    outlined 
                                    stack-label
                                ></q-input>
                            </div>
                        </div>
                        <div class="q-mt-md" v-if="form.isFirstJob === 'Yes' || form.isFirstJob === 'No'">
                            <span class="text-bold">How long did you stay in your first job?</span>
                            <div class="q-gutter-sm">
                                <q-radio 
                                    v-for="(item, index) in getChecklist.stayingInJob" 
                                    :key="index" 
                                    v-model="form.stayInJob" 
                                    :val="item" 
                                    :label="item" 
                                    color="orange" 
                                />
                            </div>
                            <div class="q-mt-sm" v-if="form.stayInJob === 'Other reason(s), please specify'">
                                <q-input
                                    dense
                                    v-model="form.stayInJobOthers"
                                    outlined 
                                    stack-label
                                ></q-input>
                            </div>
                        </div>
                        <div class="q-mt-md" v-if="form.isFirstJob === 'Yes' || form.isFirstJob === 'No'">
                            <span class="text-bold">How did you find your first job?</span>
                            <div class="q-gutter-sm">
                                <q-radio 
                                    v-for="(item, index) in getChecklist.findFirstJob" 
                                    :key="index" 
                                    v-model="form.howFirstJobFind" 
                                    :val="item" 
                                    :label="item" 
                                    color="orange" 
                                />
                            </div>
                            <div class="q-mt-sm" v-if="form.howFirstJobFind === 'Other reason(s), please specify'">
                                <q-input
                                    dense
                                    v-model="form.howFirstJobFindOthers"
                                    outlined 
                                    stack-label
                                ></q-input>
                            </div>
                        </div>
                        <div class="q-mt-md" v-if="form.isFirstJob === 'Yes' || form.isFirstJob === 'No'">
                            <span class="text-bold">How long did it take you to land your first job?</span>
                            <div class="q-gutter-sm">
                                <q-radio 
                                    v-for="(item, index) in getChecklist.stayingInJob" 
                                    :key="index" 
                                    v-model="form.howLongToGetJob" 
                                    :val="item" 
                                    :label="item" 
                                    color="orange" 
                                />
                            </div>
                            <div class="q-mt-sm" v-if="form.howLongToGetJob === 'Other reason(s), please specify'">
                                <q-input
                                    dense
                                    v-model="form.howLongToGetJobOthers"
                                    outlined 
                                    stack-label
                                ></q-input>
                            </div>
                        </div>
                        
                        <div class="q-mt-md" v-if="form.isFirstJob === 'Yes' || form.isFirstJob === 'No'">
                            <span class="text-bold">Job Level Position</span>
                            <q-table
                                :pagination="initialPagination"
                                :rows="form.jobLevel"
                                :columns="jobLevelColumns"
                                row-key="degree"
                            >
                                <template v-slot:top>
                                    <label class="text-h6">Reason(s) for taking the course(s) or pursuing degree(s). You may check more than one answer</label>
                                </template>
                                <template v-slot:body-cell-label="props">
                                    <q-td key="label" :props="props">
                                        <span v-if="props.row.label !== 'Others, please specify: '">{{ props.row.label }}</span>
                                        <div v-else>
                                            <q-input
                                                dense
                                                v-model="props.row.label"
                                                outlined 
                                                stack-label
                                            ></q-input>
                                        </div>
                                    </q-td>
                                </template>
                                <template v-slot:body-cell-firstJob="props">
                                    <q-td key="firstJob" :props="props">
                                        <q-checkbox v-model="props.row.firstJob" color="orange" />
                                    </q-td>
                                </template>
                                <template v-slot:body-cell-presentJob="props">
                                    <q-td key="presentJob" :props="props">
                                        <q-checkbox v-model="props.row.presentJob" color="orange" />
                                    </q-td>
                                </template>
                                
                            </q-table>
                        </div>
                        <div class="q-mt-md" v-if="form.isFirstJob === 'Yes' || form.isFirstJob === 'No'">
                            <span class="text-bold">What is your initial gross monthly earnings in your first job after college?</span>
                            <div class="q-gutter-sm">
                                <q-radio 
                                    v-for="(item, index) in getChecklist.salary" 
                                    :key="index" 
                                    v-model="form.grossMonthly" 
                                    :val="item" 
                                    :label="item" 
                                    color="orange" 
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <q-stepper-navigation>
                    <q-btn @click="step = 3" color="primary" label="Continue" />
                    <q-btn flat @click="step = 1" color="primary" label="Back" class="q-ml-sm" />
                </q-stepper-navigation>
            </q-step>

            <q-step
                :name="3"
                title="Employability Survey"
                icon="assignment"
            >
                <div class="row">
                    <div class="col-12">
                        DIRECTIONS: The following statements assess various aspects of employability among BSN
                        graduates. Please indicate the extent to which you agree or disagree with each statement using
                        the rating scale below:
                    </div>
                    <div class="col-12">
                        <span class="text-bold">Rating Scale:</span> <br/>
                        <span>4 - Strongly Agree</span><br/>
                        <span>3 - Agree</span><br/>
                        <span>2 - Disagree</span><br/>
                        <span>1 - Strongly Disagree</span><br/>
                    </div>
                    <div class="col-12">
                        How can you describe your employability based on the following:
                    </div>
                    <div
                        v-for="(item, index) in form.employability"
                        :key="index"
                        class="col-12 q-mt-md"
                    >
                        <span class="text-bold">{{ item.title }}</span>

                        <q-list>
                            <q-item v-for="(qItem, idx) in item.questions" :key="idx">
                                <q-item-section>
                                    <q-item-label>
                                        {{ qItem.order }}. {{ qItem.title }}
                                    </q-item-label>
                                </q-item-section>

                                <q-item-section class="text-center" v-for="(rItem, rindx) in item.rangeScore" :key="rindx" side top>
                                    <q-item-label>
                                        <q-radio 
                                            v-model="qItem.score" 
                                            :val="rItem"
                                            :label="rItem"
                                            color="orange" 
                                        />
                                    </q-item-label>
                                    
                                </q-item-section>
                            </q-item>
                        </q-list>
                    </div>
                </div>

                <q-stepper-navigation>
                    <q-btn @click="step = 4" color="primary" label="Continue" />
                    <q-btn flat @click="step = 2" color="primary" label="Back" class="q-ml-sm" />
                </q-stepper-navigation>
            </q-step>

            <q-step
                :name="4"
                title="Competencies"
                icon="add_comment"
            >
                <div class="row">
                    <div class="col-12">
                        <span class="text-h6">
                            If this is your first job, what competencies learned in college did you find very useful in your first job? You may check () more than one answer.
                        </span>
                    </div>
                    <div class="col-12">
                        <span class="text-bold">What were your reason(s) for changing your job? You may select more than one answer.</span>
                        <div class="q-gutter-sm">
                            <q-checkbox 
                                v-for="(item, index) in getChecklist.competencies" 
                                :key="index" 
                                v-model="form.competenciesLearn" 
                                :val="item" 
                                :label="item" 
                                color="orange" 
                            />
                        </div>
                        <div class="q-mt-sm" v-if="form.competenciesLearn.includes('Other reason(s), please specify')">
                            <q-input
                                dense
                                v-model="form.competenciesLearnOthers"
                                outlined 
                                stack-label
                            ></q-input>
                        </div>
                    </div>
                    <div class="col-12">
                        <span class="text-bold">List down suggestions to further improve your program curriculum.</span>
                        <q-space />
                        <q-input
                            type="textarea"
                            dense
                            v-model="form.recommendation"
                            outlined 
                            stack-label
                        ></q-input>
                    </div>
                </div>

                <q-stepper-navigation>
                    <q-btn @click="submitFormSurvey" color="primary" label="Submit" />
                    <q-btn flat @click="step = 3" color="primary" label="Back" class="q-ml-sm" />
                </q-stepper-navigation>
            </q-step>
        </q-stepper>
    </div>
</template>

<script>
import moment from 'moment'
import { LocalStorage } from 'quasar'
import jwt_decode from 'jwt-decode'
import jsonList from './checklist.json'

const dateNow = moment().format('YYYY-MM-DD');

export default {
    name: 'UserDashboard',
    data(){
        return {
            step: 1,
            profile: null,
            profilePicture: null,
            pursueOpt: [
                'For promotion',
                'For professional development',
                'Others, please specify',
            ],
            fileRacker: null,
            fileName: '',
            fileSize: '',
            uploadFile: '',
            remarks: '',
            currDate: dateNow,
            reqStatus: '',
            fileDetails: null,
            course: '',
            university: '',
            initialPagination: {
                page: 1,
                rowsPerPage: 20
            },
            form: {
                // Educ Background
                degreeDetails: [
                    {
                        degree: '',
                        university: '',
                        year: '',
                        awards: '',
                    }
                ],
                examPassed: [],
                reasonsTogetTheCourse: [
                    {  
                        label: 'High grades in the course or subject area(s) related to the course',
                        valueUndergrd: false,
                        valueMasteral: false
                    },
                    {  
                        label: 'Good grades in high school',
                        valueUndergrd: false,
                        valueMasteral: false
                    },
                    {  
                        label: 'Influence of parents or relatives ',
                        valueUndergrd: false,
                        valueMasteral: false
                    },
                    {  
                        label: 'Peer Influence ',
                        valueUndergrd: false,
                        valueMasteral: false
                    },
                    {  
                        label: 'Inspired by a role model ',
                        valueUndergrd: false,
                        valueMasteral: false
                    },
                    {  
                        label: 'Strong passion for the profession',
                        valueUndergrd: false,
                        valueMasteral: false
                    },
                    {  
                        label: 'Prospect for immediate employment ',
                        valueUndergrd: false,
                        valueMasteral: false
                    },
                    {  
                        label: 'Status or prestige of the profession ',
                        valueUndergrd: false,
                        valueMasteral: false
                    },
                    {  
                        label: 'Availability of course offering in chosen institution',
                        valueUndergrd: false,
                        valueMasteral: false
                    },
                    {  
                        label: 'Prospect of career advancement ',
                        valueUndergrd: false,
                        valueMasteral: false
                    },
                    {  
                        label: 'Affordable for the family ',
                        valueUndergrd: false,
                        valueMasteral: false
                    },
                    {  
                        label: 'Prospect of attractive compensation',
                        valueUndergrd: false,
                        valueMasteral: false
                    },
                    {  
                        label: 'Opportunity for employment abroad',
                        valueUndergrd: false,
                        valueMasteral: false
                    },
                    {  
                        label: 'No particular choice or no better idea ',
                        valueUndergrd: false,
                        valueMasteral: false
                    },
                    {  
                        label: 'Others, please specify: ',
                        valueUndergrd: false,
                        valueMasteral: false
                    }
                ],
                trainings: [],
                reasonToPursue: '',
                reasonToPursueOthers: '',

                // Employment
                isEmployed: 'No',
                employmentStatus: '',
                reasonNotEmployed: [],
                othersReason: '',
                occupation: '',
                ifSelfEmployed: '',
                typeOfCompany: '',
                placeOfWork: '',
                isFirstJob: 'Yes',
                reasonToStayJob: [],
                reasonToStayJobOthers: '',
                reasonToAcceptJob: [],
                reasonToAcceptJobOthers: '',
                reasonToChangeJob: [],
                reasonToChangeJobOthers: '',
                stayInJob: '',
                stayInJobOthers: '',
                howLongToGetJob: '',
                howLongToGetJobOthers: '',
                howFirstJobFind: '',
                howFirstJobFindOthers: '',
                jobLevel: [
                    {  
                        label: 'Rank or Clerical',
                        firstJob: false,
                        presentJob: false
                    },
                    {  
                        label: 'Professional, Technical or Supervisory',
                        firstJob: false,
                        presentJob: false
                    },
                    {  
                        label: 'Managerial or Executive',
                        firstJob: false,
                        presentJob: false
                    },
                    {  
                        label: 'Self-employed',
                        firstJob: false,
                        presentJob: false
                    },
                ],
                grossMonthly: '',
                competenciesLearn: [],
                competenciesLearnOthers: '',
                recommendation: '',
                employability: [
                    {
                        title: "Understanding (Knowledge of the field)",
                        rangeScore: [4,3,2,1],
                        questions: [
                            {
                                title: "I have a clear comprehension of my job roles and responsibilities",
                                order: 1,
                                score: 0
                            },
                            {
                                title: "I understand how my work contributes to the overall goals of my organization",
                                order: 2,
                                score: 0
                            },
                            {
                                title: "I am aware of the industry standards relevant to my profession.",
                                order: 3,
                                score: 0
                            },
                            {
                                title: "I keep myself informed about the latest developments in my field.",
                                order: 4,
                                score: 0
                            },
                            {
                                title: "I understand the expectations of my employer regarding my performance.",
                                order: 5,
                                score: 0
                            },
                        ]
                    },
                    {
                        title: "Skills (Practical and transferable abilities)",
                        rangeScore: [4,3,2,1],
                        questions: [
                            {
                                title: "Technical Skills: I possess the technical skills necessary to perform my job effectively.",
                                order: 6,
                                score: 0
                            },
                            {
                                title: "Critical Thinking and Problem-Solving: I can analyze complex problems and develop effective solutions.",
                                order: 7,
                                score: 0
                            },
                            {
                                title: "Soft Skills: I communicate effectively with colleagues and clients.",
                                order: 8,
                                score: 0
                            },
                            {
                                title: "Leadership and Management: I can manage team members efficiently.",
                                order: 9,
                                score: 0
                            },
                            {
                                title: "Adaptability and Continuous Learning: I quickly adapt to new work environments.",
                                order: 10,
                                score: 0
                            },
                        ]
                    },
                    {
                        title: "Efficacy (Confidence in capabilities)",
                        rangeScore: [4,3,2,1],
                        questions: [
                            {
                                title: "I have confidence in my ability to perform my job successfully.",
                                order: 11,
                                score: 0
                            },
                            {
                                title: "I believe I can overcome work-related challenges.",
                                order: 12,
                                score: 0
                            },
                            {
                                title: "I am motivated to achieve my professional goals.",
                                order: 13,
                                score: 0
                            },
                            {
                                title: "I take proactive steps to improve my performance and career prospects.",
                                order: 14,
                                score: 0
                            },
                            {
                                title: "I maintain a positive mindset and resilience when facing setbacks.",
                                order: 15,
                                score: 0
                            },
                        ]
                    },
                    {
                        title: "Metacognition (Self-awareness in learning)",
                        rangeScore: [4,3,2,1],
                        questions: [
                            {
                                title: "I regularly reflect on my performance to identify areas for improvement.",
                                order: 16,
                                score: 0
                            },
                            {
                                title: "I am aware of my strengths and weaknesses in my professional abilities.",
                                order: 17,
                                score: 0
                            },
                            {
                                title: "I set specific goals for my professional development and work towards achieving them.",
                                order: 18,
                                score: 0
                            },
                            {
                                title: "I adjust my work strategies based on feedback and self-evaluation",
                                order: 19,
                                score: 0
                            },
                            {
                                title: "I seek out learning opportunities to enhance my knowledge and skills.",
                                order: 20,
                                score: 0
                            },
                        ]
                    }
                ]
            },
        }
    },
    computed: {
        user: function(){
            let profile = LocalStorage.getItem('userData');
            return jwt_decode(profile);
        },
        getChecklist(){
            return jsonList
        },
        companyTypeOpt(){
            return [
                'Agriculture, Hunting, and Forestry',
                'Fishing',
                'Mining and Quarrying',
                'Manufacturing',
                'Electricity, Gas and Water Supply',
                'Construction',
                'Wholesale and Retail Trade, repair of motor vehicles, motorcycles and personal and household goods',
                'Hotels and Restaurants',
                'Transport Storage and Communication',
                'Financial Intermediation',
                'Real Estate, Renting and Business Activities',
                'Public Administration and Defense; Compulsory',
                'Social Security',
                'Education',
                'Health and Social Work',
                'Other Community, Social, and Personal Service Activities',
                'Private Households with Employed Persons',
                'Extra-territorial Organizations and Bodies'
            ]
        },
        checklistEmployStats(){
            return [
                {  
                    label: 'Regular or Permanent',
                    value: 'Regular or Permanent',
                },
                {  
                    label: 'Temporary',
                    value: 'Temporary',
                },
                {  
                    label: 'Casual',
                    value: 'Casual',
                },
                {  
                    label: 'Contractual',
                    value: 'Contractual',
                },
                {  
                    label: 'Self-employed',
                    value: 'Self-employed',
                }
            ]
        },
        checklistRNE(){
            return [
                {  
                    label: 'Advance or further study',
                    value: 'afs',
                },
                {  
                    label: 'Family concern and decided not to find a job',
                    value: 'fcndnj',
                },
                {  
                    label: 'Health-related reason(s)',
                    value: 'hrr',
                },
                {  
                    label: ' No job opportunity',
                    value: 'njo',
                },
                {  
                    label: 'Did not look for a job',
                    value: 'dnlj',
                },
                {  
                    label: 'Lack of work experience',
                    value: 'lwe',
                },
                {  
                    label: 'Other reason(s), please specify',
                    value: 'others',
                },
                
            ]
        },
        degreeColumns(){
            return [
                {
                    name: 'degree',
                    required: true,
                    label: 'Degree(s) and Specialization(s)',
                    align: 'left',
                    field: row => row.degree,
                    format: val => `${val}`,
                    sortable: true
                },
                {
                    name: 'university',
                    required: true,
                    label: 'College or University ',
                    align: 'left',
                    field: row => row.university,
                    format: val => `${val}`,
                    sortable: true
                },
                {
                    name: 'year',
                    required: true,
                    label: 'Year Graduated',
                    align: 'left',
                    field: row => row.year,
                    format: val => `${val}`,
                    sortable: true
                },
                {
                    name: 'awards',
                    required: true,
                    label: 'Honor(s) or Award(s) Received',
                    align: 'left',
                    field: row => row.awards,
                    format: val => `${val}`,
                    sortable: true
                },
            ]
        },
        examsColumns(){
            return [
                {
                    name: 'examName',
                    required: true,
                    label: 'Name of Examination',
                    align: 'left',
                    field: row => row.examName,
                    format: val => `${val}`,
                    sortable: true
                },
                {
                    name: 'dateTaken',
                    required: true,
                    label: 'Date Taken',
                    align: 'left',
                    field: row => row.dateTaken,
                    format: val => `${val}`,
                    sortable: true
                },
                {
                    name: 'ratings',
                    required: true,
                    label: 'Rating',
                    align: 'left',
                    field: row => row.ratings,
                    format: val => `${val}`,
                    sortable: true
                },
                {
                    name: 'action',
                    label: '',
                    align: 'left',
                    field: 'action',
                },
            ]
        },
        reasonsColumns(){
            return [
                {
                    name: 'label',
                    required: true,
                    label: 'Job Level ',
                    align: 'left',
                    field: row => row.label,
                    format: val => `${val}`,
                    sortable: true
                },
                {
                    name: 'valueUndergrd',
                    required: true,
                    label: 'Undergraduate/AB/BS (Bachelor of Arts/Bachelor of Science)',
                    align: 'center',
                    field: row => row.valueUndergrd,
                    format: val => `${val}`,
                    sortable: true
                },
                {
                    name: 'valueMasteral',
                    required: true,
                    label: 'Graduate/MS/MA/PhD (Master of Science/Masteral/Doctorate)',
                    align: 'center',
                    field: row => row.valueMasteral,
                    format: val => `${val}`,
                    sortable: true
                },
            ]
        },
        jobLevelColumns(){
            return [
                {
                    name: 'label',
                    required: true,
                    label: '',
                    align: 'left',
                    field: row => row.label,
                    format: val => `${val}`,
                    sortable: true
                },
                {
                    name: 'firstJob',
                    required: true,
                    label: 'First Job',
                    align: 'center',
                    field: row => row.firstJob,
                    format: val => `${val}`,
                    sortable: true
                },
                {
                    name: 'presentJob',
                    required: true,
                    label: 'Current or Present Job',
                    align: 'center',
                    field: row => row.presentJob,
                    format: val => `${val}`,
                    sortable: true
                },
            ]
            
        },
        trainingColumns(){
            return [
                {
                    name: 'trainingTitle',
                    required: true,
                    label: 'Title of Training or Advance Study',
                    align: 'left',
                    field: row => row.trainingTitle,
                    format: val => `${val}`,
                    sortable: true
                },
                {
                    name: 'trainingDuration',
                    required: true,
                    label: 'Duration and Credits Earned',
                    align: 'left',
                    field: row => row.trainingDuration,
                    format: val => `${val}`,
                    sortable: true
                },
                {
                    name: 'trainingInstitution',
                    required: true,
                    label: 'Name of Training Institution/College/University',
                    align: 'left',
                    field: row => row.trainingInstitution,
                    format: val => `${val}`,
                    sortable: true
                },
                {
                    name: 'action',
                    label: '',
                    align: 'left',
                    field: 'action',
                },
                
            ]
        },
    },
    watch: {
        fileRacker: async function(newVal){
            let convertedFile = await this.getBase64(newVal)
            this.fileName = newVal.name
            this.fileSize = newVal.size
            this.uploadFile = convertedFile
        }
    },
    created(){
        this.getCourseList().then(() => {
            this.getSchoolList().then(() => {
                this.getUserDetails();
            })
        })
        
    },
    methods: {
        moment,
        addExam(){
            this.form.examPassed.push({
                examName: '',
                dateTaken: '',
                ratings: '',
            })
        },
        removeExam(item){
            const index = this.form.examPassed.indexOf(item);
            this.form.examPassed.splice(index, 1)
        },
        addTraining(){
            this.form.trainings.push({
                trainingTitle: '',
                trainingDuration: '',
                trainingInstitution: '',
            })
        },
        removeTraining(item){
            const index = this.form.trainings.indexOf(item);
            this.form.trainings.splice(index, 1)
        },
        removeFile(){
            this.fileRacker = null;
            this.fileName = ''
            this.fileSize = ''
        },
        async getBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        },
        async getCourseList(){
            this.$api.get("misc/getCourses").then((response) => {
                let data = response.data.list
                this.course = data.length > 0 ? data[0].description : '--'
            })
        },
        async getSchoolList(){
            this.$api.get("misc/getSchools").then((response) => {
                let data = response.data.list
                this.university = data.length > 0 ? data[0].schoolName : '--'
            })
        },
        getUserDetails(){
            let payload = {
                id: this.user.userId,
            }

            this.$api.post('users/getUserById', payload).then(async (response) => {
                const data = {...response.data};
                this.profile = data

                this.$nextTick(() => {
                    this.form.degreeDetails[0].degree = this.course
                    this.form.degreeDetails[0].university = this.university
                    this.form.degreeDetails[0].year = data.yearGraduated
                })
            })
        },
        async submitFormSurvey(){
            this.$q.dialog({
                title: 'Submit Survey',
                message: 'Would you like to finalize your data and submit your answer?',
                ok: {
                    label: 'Yes'
                },
                cancel: {
                    label: 'No',
                    color: 'negative'
                },
                persistent: true
            }).onOk(() => {
                this.form.userId = this.user.userId

                this.$api.post('users/submit/survey', this.form).then((response) => {
                    const data = {...response.data};
                    if(!data.error){
                        this.$q.notify({
                            color: 'positive',
                            position: 'top-right',
                            message: 'Order Successfully Created',
                            icon: 'verified'
                        })
                    } else {
                        this.$q.notify({
                            color: 'negative',
                            position: 'top-right',
                            title:data.title,
                            message: this.$t(`errors.${data.error}`),
                            icon: 'report_problem'
                        })
                    }

                })
            })
        }
    }
}
</script>
<style scoped>
.alignTextContent {
    display: flex;
    align-content: flex-start !important;
    justify-content: flex-start;
    align-items: self-start;
    text-align: left !important;
}
.my-card{
    border-radius: 15px;
    box-shadow: 0px 0px 3px -2px !important;
}
.my-card-item{
    border-radius: 10px;
}
.generatedDash{
  color: white;
  background: rgb(0,177,250);
  background: linear-gradient(122deg, rgb(255 251 176) 0%, rgb(0 55 255 / 61%) 89%);
}
.generatedDash2{
  color: white;
  background: rgb(0,177,250);
  background: linear-gradient(122deg, rgb(38 148 243) 0%, rgb(45 253 215 / 61%) 89%);
}
</style>
