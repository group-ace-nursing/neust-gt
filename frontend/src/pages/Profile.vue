<template>
    <div class="q-pa-md" style="width: 100%;">
        <div class="row">
            <!-- Users Count Overview -->
            <div v-if="profile !== null" class="col-12 col-xs-12 col-sm-12 col-md-6 q-pa-xs">
                <q-card
                    flat
                    class="my-card bg-white"
                >
                    <q-card-section >
                        <div class="row">
                            <div class="col-12 col-md-12 q-pa-xs">
                                <span class="text-title text-bold">Personal Information</span>
                            </div>
                            <div class="col-12">
                                <q-separator />
                            </div>
                            <div class="col-3 q-mb-sm q-mt-md">
                                <span class="text-caption text-grey q-mr-xl">Full Name</span>
                            </div>
                            <div class="col-9 q-mb-sm q-mt-md">
                                <span class="text-title text-bold">{{`${profile.fullName || '--'}`}}</span>
                            </div>
                            <!-- <div class="col-4 q-mb-sm">
                                <span class="text-title text-bold">{{`${profile.civilStatus || '--'}`}}</span><br/>
                                <span class="text-caption text-grey"></span>
                            </div> -->
                            <div class="col-3 q-mb-sm">
                                <span class="text-caption text-grey q-mr-xl">Date of Birth</span>
                            </div>
                            <div class="col-9 q-mb-sm">
                                <span class="text-title text-bold">{{`${moment(profile.birthDate).format("MMMM DD, YYYY") || '--'}`}}</span>
                            </div>
                            <div class="col-3 q-mb-sm">
                                <span class="text-caption text-grey q-mr-xl">Civil Status</span>
                            </div>
                            <div class="col-9 q-mb-sm">
                                <span class="text-title text-bold">{{`${profile.civilStatus || '--'}`}}</span>
                            </div>
                            <div class="col-3 q-mb-sm">
                                <span class="text-caption text-grey q-mr-xl">Contact/Telephone</span>
                            </div>
                            <div class="col-9 q-mb-sm">
                                <span class="text-title text-bold">{{`${profile.contact || '--'}`}} / {{`${profile.telephone || '--'}`}}</span>
                            </div>
                            <div class="col-3 q-mb-sm">
                                <span class="text-caption text-grey q-mr-xl">Email</span>
                            </div>
                            <div class="col-9 q-mb-sm">
                                <span class="text-title text-bold">{{`${profile.email || '--'}`}}</span>
                            </div>
                        </div>
                    </q-card-section>
                </q-card>
            </div>
            <div v-if="profile !== null" class="col-12 col-xs-12 col-sm-12 col-md-6 q-pa-xs">
                <q-card
                    flat
                    class="my-card bg-white"
                >
                    <q-card-section >
                        <div class="row">
                            <div class="col-12 col-md-12 q-pa-xs">
                                <span class="text-title text-bold">Other Information</span>
                            </div>
                            <div class="col-12">
                                <q-separator />
                            </div>
                            <div class="col-12 q-mb-sm">
                                <span class="text-title text-bold">{{`${profile.permanentAddress || '--'}`}}</span><br/>
                                <span class="text-caption text-grey">Address</span>
                            </div>
                            
                            <div class="col-3 q-mb-sm">
                                <span class="text-title text-bold">{{`${profile.region || '--'}`}}</span><br/>
                                <span class="text-caption text-grey">Region</span>
                            </div>
                            <div class="col-3 q-mb-sm">
                                <span class="text-title text-bold">{{`${profile.province || '--'}`}}</span><br/>
                                <span class="text-caption text-grey">Province</span>
                            </div>
                            <div class="col-3 q-mb-sm">
                                <span class="text-title text-bold">{{`${profile.locality || '--'}`}}</span><br/>
                                <span class="text-caption text-grey">Locality</span>
                            </div>
                        </div>
                    </q-card-section>
                </q-card>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
import { LocalStorage } from 'quasar'
import jwt_decode from 'jwt-decode'

const dateNow = moment().format('YYYY-MM-DD');

export default {
    name: 'UserDashboard',
    data(){
        return {
            profile: null,
            profilePicture: null,

            fileRacker: null,
            fileName: '',
            fileSize: '',
            uploadFile: '',
            remarks: '',
            currDate: dateNow,
            reqStatus: '',
            fileDetails: null
        }
    },
    computed: {
        user: function(){
            let profile = LocalStorage.getItem('userData');
            return jwt_decode(profile);
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
        this.getUserDetails();
    },
    methods: {
        moment,
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
        getUserDetails(){
            let payload = {
                id: this.user.userId,
            }

            this.$api.post('users/getUserById', payload).then(async (response) => {
                const data = {...response.data};
                this.profile = data
            })
        },
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
