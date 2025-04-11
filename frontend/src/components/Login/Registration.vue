<template>
    <div class="row">
        <div class="col-12 text-grey-8 q-mb-md">Embrace the new year with a fresh opportunity! Join with us to create you future.</div>
        <div class="col-12 text-center">
          <div class="q-gutter-sm">
            <q-radio 
              v-model="form.userType" 
              checked-icon="task_alt" 
              unchecked-icon="panorama_fish_eye" 
              val="2" 
              label="I am Allumni" 
            />
            <q-radio 
              v-model="form.userType" 
              checked-icon="task_alt" 
              unchecked-icon="panorama_fish_eye" 
              val="1" 
              label="I am Staff" 
            />
          </div>
        </div>
        <div v-if="form.userType === '2'" class="col-12 col-md-12 text-grey-8 q-pa-xs">
          <q-select 
            outlined 
            v-model="form.batchYear" 
            :options="yearMultipleOpts" 
            label="Year Batch" 
            stack-label 
            options-dense
          >
          </q-select>
        </div>
        <div class="col-12 col-md-6 text-grey-8 q-pa-xs">
            <q-input
                v-model="form.username"
                label="Username"
                v-bind="formRules.username"
                placeholder="Username"
                outlined 
                stack-label
            >
                <template v-slot:prepend>
                <q-icon name="account_circle" />
                </template>
            </q-input>
        </div>
        <div class="col-12 col-md-6 text-grey-8 q-pa-xs">
            <q-input 
                v-model="form.password"
                v-bind="formRules.password"
                :type="isPwd ? 'password' : 'text'"
                label="Password"
                placeholder="***********"
                outlined  
                stack-label
            >
                <template v-slot:prepend>
                  <q-icon name="lock" />
                </template>
                <template v-slot:append>
                  <q-icon
                    :name="isPwd ? 'visibility_off' : 'visibility'"
                    class="cursor-pointer"
                    @click="isPwd = !isPwd"
                  />
                </template>
            </q-input>
        </div>

        <div class="col-12 col-md-12 text-grey-8 q-pa-xs">
          <q-input
            v-model="form.fullName"
            label="Full Name"
            placeholder="Ex. (Last Name, First Name Suffix Middle Name)"
            outlined 
            stack-label
          >
          </q-input>
        </div>
        <div class="col-12 col-md-4 text-grey-8 q-pa-xs">
          <q-select 
            outlined 
            v-model="form.sex" 
            :options="sexOpt" 
            label="Gender" 
            stack-label 
            options-dense
          >
          </q-select>
        </div>
        <div class="col-12 col-md-4 text-grey-8 q-pa-xs">
          <q-select 
            outlined 
            v-model="form.civilStatus" 
            :options="statusOpt" 
            label="Civil Status" 
            stack-label 
            options-dense
          >
          </q-select>
        </div>
        <div class="col-12 col-md-4 text-grey-8 q-pa-xs">
          <q-input
            type="date"
            v-model="form.birthDate"
            label="Birth Date"
            placeholder="MM/DD/YYYY"
            outlined 
            stack-label
          >
          </q-input>
        </div>
        <div class="col-12 col-md-12 text-grey-8 q-pa-xs">
          <q-input
            v-model="form.email"
            label="Email"
            placeholder="Enter Email Address"
            outlined 
            stack-label
          >
          </q-input>
        </div>
        <div class="col-12 col-md-6 text-grey-8 q-pa-xs">
          <q-input
            v-model="form.contact"
            label="Contact Number/Mobile"
            placeholder="Enter Contact Number"
            outlined 
            stack-label
            mask="###########"
          >
          </q-input>
        </div>
        <div class="col-12 col-md-6 text-grey-8 q-pa-xs">
          <q-input
            v-model="form.telephone"
            label="Telephone"
            placeholder="Enter Telephone Number"
            outlined 
            stack-label
            mask="###########"
          >
          </q-input>
        </div>
        <div class="col-12 text-grey-8 q-pa-xs">
          <q-input
            v-model="form.permanentAddress"
            type="textarea"
            label="Permanent Address"
            placeholder="Enter Permanent Address"
            outlined
            stack-label
          >
          </q-input>
        </div>
        <div v-if="form.userType === '2'" class="col-12 col-md-6 text-grey-8 q-pa-xs">
          <q-select 
            outlined 
            v-model="form.region" 
            :options="regionList" 
            label="Region of Origin" 
            stack-label 
            options-dense
          >
          </q-select>
        </div>
        <div v-if="form.userType === '2'" class="col-12 col-md-6 text-grey-8 q-pa-xs">
          <q-select 
            outlined 
            v-model="form.locality" 
            :options="localityList" 
            label="Location Residence" 
            stack-label 
            options-dense
          >
          </q-select>
        </div>
        <div v-if="form.userType === '2'" class="col-12 col-md-12 text-grey-8 q-pa-xs">
          <q-input
            v-model="form.province"
            label="Province"
            placeholder="Enter Province"
            outlined 
            stack-label
          >
          </q-input>
        </div>
        <div class="col-12 q-mt-lg">
            <q-btn
                type="submit"
                class="full-width q-pa-md btn-custom-border" 
                unelevated
                :loading="loginLoad"
                no-caps 
                color="primary" 
                label="Register"
                @click="submitRegister"
            />
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import { LocalStorage } from 'quasar'

export default {
  name:"RegisterComponent",
  data() {
    return {
      tab: "login",
      isPwd: true,
      keepLogin: false,
      loginLoad: false,
      sexOpt: [
        "Male",
        "Female",
        "Prefer Not To Say"
      ],
      statusOpt: [
        "Single",
        "Married",
        "Widowed",
        "Separated",
      ],
      courseOpt: [],
      form: {
        username: "",
        password: "",
        // Graduate Info
        fullName: '',
        email: '',
        contact: '',
        telephone: '',
        sex: '',
        civilStatus: '',
        birthDate: '',
        userType: '2',

        // Address 
        permanentAddress: '',
        region: '',
        province: '',
        locality: '',

        // Educ Background
        universityId: 1,
        courseId: 1,
        batchYear: "2022",
      },
      localityList: [
        'City',
        'Municipality'
      ],

      regionList: [
        'Region 1',
        'Region 2',
        'Region 3',
        'Region 4',
        'Region 5',
        'Region 6',
        'Region 7',
        'Region 8',
        'Region 9',
        'Region 10',
        'Region 11',
        'Region 12',
        'NCR',
        'CAR',
        'ARMM',
        'CARAGA'
      ],
      formRules: {
        username: {
          rules: [
            value => !!value || 'This field must be filled!',
          ]
        },
        password: {
          rules: [
            val => !!val || 'This field must be filled!',
          ]
        },
      },
    }
  },
  computed: {
    yearMultipleOpts() {
			let res = [];
			let startDate = 1997;
			let currentYear = new Date().getFullYear();

			for (let year = startDate; year <= currentYear; year++) {
				res.push({
          label: year.toString(),
          value: year.toString()
				});
			}

			return res;
		},
    enableRegister(){
      let checkItemVal = 0;
      let unvalidate = "telephone, birthDate, userType"
      for(const obj in this.form){
        if(
          this.form[obj] === "" &&
          !unvalidate.includes(obj)
        ){
          checkItemVal += 1
        }
      }

      return checkItemVal > 1
    }
  },
  methods: {
    moment,
    async submitRegister(){
      this.$q.loading.show();
      this.loginLoad = true;
      let vm = this;
      let payload = {
        ...vm.form,
        birthDate: vm.form.birthDate ? moment(vm.form.birthDate).format('LL') : "",
        surveyTaken: this.form.userType === '2' ? 5 : 0
      };

      this.$api.post('users/create', payload).then(async (response) => {
        const data = {...response.data};
        if(!data.error){
          this.$q.notify({
            position: 'top-left',
            type: 'positive',
            message: data.title,
            caption: data.message,
            icon: 'mdi_information'
          })
          this.clearForm();
          this.$q.loading.hide();
          this.loginLoad = false;
          this.$emit('goToLogin');
        } else {
          this.$q.loading.hide();
          this.loginLoad = false;
          this.$q.notify({
            position: 'top-left',
            type: 'negative',
            message: data.title,
            caption: data.message,
            icon: 'report_problem'
          })
        }
      })
    },

    clearForm(){
      this.form = {
        username: "",
        password: "",
        // Graduate Info
        fullName: '',
        email: '',
        contact: '',
        telephone: '',
        sex: '',
        civilStatus: '',
        birthDate: '',
        userType: '2',

        // Address 
        permanentAddress: '',
        region: '',
        province: '',
        locality: '',

        // Educ Background
        school: 1,
        course: 1,
        batchYear: "2022",
      }
    }
  }
}
</script>
