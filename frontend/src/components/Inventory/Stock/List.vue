<template>
    <div>
        <div class="q-pa-md" style="width: 100%;">
            <div class="row">
                <div class="col col-md-6">
                    <span class="title">In Stock</span>
                </div>
                <div class="col col-md-6 text-right">
                    <q-btn 
                        unelevated 
                        @click="openAddModal"
                        color="primary"
                    >
                        <q-icon left name="add" />
                        <div>New Stock</div>
                    </q-btn>
                </div>
                <div v-if="isLoading" class="col col-md-12 text-center">
                    <q-spinner-orbit
                        color="primary"
                        size="3em"
                    />
                </div>
                <div v-if="tableRow.length === 0" class="col col-md-12 text-center">
                    <noData />
                </div>
                <div v-if="tableRow.length > 0" class="col col-md-12 q-mt-md">
                    <q-table
                        :rows="tableRow"
                        :filter="filter"
                        :columns="tableColumns"
                        row-key="productName"
                        
                    >
                        <template v-slot:top-right>
                            <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
                                <template v-slot:append>
                                    <q-icon name="search" />
                                </template>
                            </q-input>
                        </template>
                        <template v-slot:body-cell-actions="props">
                            <q-td :props="props">
                                <q-btn 
                                    dense
                                    flat
                                    outline
                                    size="md"
                                    color="primary" 
                                    label="Add Stock"
                                    @click="addStock(props.row)"
                                />
                                <q-btn 
                                    dense
                                    flat
                                    outline
                                    size="md"
                                    color="red" 
                                    label="Delete"
                                    @click="deleleSaveData(props.row)"
                                />
                            </q-td>
                        </template>
                    </q-table>
                </div>
            </div>
        </div>

        <stockModal
            :modalStatus="openModal" 
            @updateModalStatus="closeAddModal"
            @refreshData="getList"
        />
    </div>
</template>

<script>
import moment from 'moment';
import { LocalStorage, SessionStorage } from 'quasar'
import noData from '../../Templates/NoData.vue';
import stockModal from '../../Inventory/Modals/StockModal.vue';
import jwt_decode from 'jwt-decode'
import { api } from 'boot/axios'

export default{
    name: 'StockList',
    data(){
        return {
            isPwd: true,
            isLoading: false,
            submitting: false,
            tableRow: [],
            filter: '',
            saveDetails: null,
            saveId: null,
            insertedID: null,
            openModal: false,
            appId: 0,
        }
    },
    components: {
        noData,
        stockModal
    },
    created(){
        this.getList();
    },
    methods: {
        moment,
        openAddModal(){
            this.openModal = true
        },
        closeAddModal(val){
            this.openModal = val
        },
        async addStock(info){
            console.log(info)
            this.$q.dialog({
                title: 'Add Stock',
                message: 'Quantity of Stocks to Add',
                prompt: {
                    model: 0,
                    type: 'number' // optional
                },
                cancel: true,
                persistent: true
            }).onOk(data => {
                // console.log('>>>> OK, received', data)
                let payload = {
                    id: info.id,
                    quantity: Number(data),
                }

                api.post('stock/add', payload).then((response) => {
                    const data = {...response.data};
                    if(!data.error){
                        this.$q.notify({
                            color: 'positive',
                            position: 'top-right',
                            title:data.title,
                            message: data.message,
                            icon: 'report_problem'
                        })
                        this.getList();
                    } else {
                        this.$q.notify({
                            color: 'negative',
                            position: 'top-right',
                            title: data.title,
                            message: data.message,
                            icon: 'report_problem'
                        })
                    }

                })

            })
           
        },
        async submitData(frmData){
            this.openModal = false;

            api.post('inventory/stock/save_details', frmData).then((response) => {
                const data = {...response.data};
                if(!data.error){
                    this.$q.notify({
                        color: 'positive',
                        position: 'top-right',
                        title:data.title,
                        message: data.message,
                        icon: 'report_problem'
                    })
                    this.getList();
                } else {
                    this.$q.notify({
                        color: 'negative',
                        position: 'top-right',
                        title: data.title,
                        message: data.message,
                        icon: 'report_problem'
                    })
                }

            })
        },
        async getList(){
            this.tableRow = [];
            this.isLoading = true;
            let vm = this;
            
            api.post('stock/get/stocks').then((response) => {
                const data = {...response.data};
                if(!data.error){
                    this.tableRow = response.status < 300 ? data.list : [];
                } else {
                    this.$q.notify({
                        color: 'negative',
                        position: 'top-right',
                        title:data.title,
                        message: error,
                        icon: 'report_problem'
                    })
                }

            })

            this.isLoading = false;
        },
        
        // end
    },
    computed: {
        user: function(){
            let profile = LocalStorage.getItem('userData');
            return jwt_decode(profile);
        },
        tableColumns: function(){
           
            return [
                {
                    name: 'productName',
                    required: true,
                    label: 'Product Name',
                    align: 'left',
                    field: row => row.productName,
                    format: val => `${val}`,
                    sortable: true
                },
                { 
                    name: 'quantity',
                    required: true,
                    label: 'Quantity', 
                    align: 'left',
                    field:  row => row.quantity,
                    format: val => `${val}`,
                    sortable: true
                },
                { 
                    name: 'stockNotice',
                    required: true,
                    label: 'Stock Notice', 
                    align: 'left',
                    field:  row => row.stockNotice,
                    format: val => `${val}`,
                    sortable: true
                },
                { name: 'createdDate', label: 'Date Created', field: 'createdDate' },
                { name: 'actions', label: 'Action', field: 'actions' }
               
                
                
                
            ]
        }
    }
}
</script>

<style lang="scss" scoped>
.title{
    font-weight: 600;
    font-size: 18pt;
}
</style>
