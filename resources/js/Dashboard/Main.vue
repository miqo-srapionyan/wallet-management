<template>
    <div>
        <div class="row justify-content-center">

        <h3 class="w-100 text-center d-inline-block heading">
            Dashboard
            <span>Here you can add wallets</span>
        </h3>
        <h3 class="w-100 text-right d-inline-block heading">
            Total: {{total}}$
        </h3>

        <div class="row">
            <div class="col-md-2">
                <v-boilerplate v-if="cardsLoading"
                    class="mb-6"
                    type="article, actions"
                ></v-boilerplate>
                <v-card v-if="!cardsLoading"
                    class="mx-auto custom-card"
                    max-width="344"
                    outlined
                    @click.stop="openAddWallet"
                >
                    <i class="fa fa-plus vh-centered"></i>
                </v-card>
            </div>
            <div class="col-md-2" v-for="wallet in wallets" >
                <v-card
                    class="mx-auto card-hover"
                    max-width="344"
                    outlined
                    :class="{selected: wallet.id == selectedWallet}"
                    @click="selectedWallet && selectedWallet == wallet.id ? selectedWallet = null : selectedWallet = wallet.id"
                >
                    <v-list-item three-line>
                        <v-list-item-content>
                            <v-list-item-title class="headline mb-1" :title="wallet.name">{{wallet.name}}</v-list-item-title>
                            <v-list-item-subtitle v-if="wallet.type === 'credit_card'">
                                Credit Card
                                <i class="fas fa-credit-card"></i>
                            </v-list-item-subtitle>
                            <v-list-item-subtitle v-else>
                                Cash
                                <i class="fas fa-wallet"></i>
                            </v-list-item-subtitle>
                        </v-list-item-content>

                        <v-list-item-avatar
                            tile
                            size="80"
                            color="grey"
                        ></v-list-item-avatar>
                        <span class="amount">{{wallet.total | numberFormat}}$</span>
                    </v-list-item>

                    <v-card-actions class="absolute-actions">
                        <i class="fas fa-pencil-alt" @click.stop="openEditWallet(wallet)"></i>
                        <i class="fas fa-trash-alt" @click.stop="deleteWallet(wallet)"></i>
                    </v-card-actions>
                </v-card>
            </div>
        </div>

            <el-dialog title="Add new wallet" :visible.sync="addWalletDialog">
                <el-form :model="wallet" ref="wallet_form">
                    <div class="row">
                        <div class="col-md-3">
                            <el-form-item label="Name" >
                                <el-input v-model="wallet.name" autocomplete="off"></el-input>
                                <label ref="name" class="error"></label>
                            </el-form-item>
                        </div>
                        <div class="col-md-6 custom-input">
                            <el-form-item label="Type" >
                                <el-select v-model="wallet.type" placeholder="Please select a type" >
                                    <el-option label="Credit Card" value="credit_card"></el-option>
                                    <el-option label="Cash" value="cash"></el-option>
                                </el-select>
                                <label ref="type" class="error"></label>
                            </el-form-item>
                        </div>
                    </div>



                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="addWalletDialog = false">Cancel</el-button>
                    <el-button type="primary" @click="handleCreateWallet" :disabled="addWalletLoading">Confirm <i class="fa fa-spinner fa-spin" v-if="addWalletLoading"></i></el-button>
                  </span>
            </el-dialog>

            <el-dialog title="Add new balance" :visible.sync="addWalletBalanceDialog">
                <el-form :model="balance" ref="wallet_form">
                    <div class="row">
                        <div class="col-md-3">
                            <el-form-item label="Amount" >
                                <el-input v-model="balance.amount" type="number" autocomplete="off"></el-input>
                                <label ref="amount" class="error"></label>
                            </el-form-item>
                        </div>
                    </div>



                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="addWalletBalanceDialog = false">Cancel</el-button>
                    <el-button type="primary" @click="handleCreateWalletBalanace" :disabled="addWalletBalanceLoading">Confirm <i class="fa fa-spinner fa-spin" v-if="addWalletBalanceLoading"></i></el-button>
                  </span>
            </el-dialog>

    </div>

    <div class="row" v-if="selectedWallet">
        <div class="col-md-6">
            <el-button type="success" @click="openCredit('credit')">Credit</el-button>
            <el-button type="danger" @click="openCredit('debit')">Debit</el-button>
            <hr>

            <el-table
                :empty-text="'No Data'"
                :data="tableData"
                style="width: 100%">
                <el-table-column
                    prop="amount"
                    label="Amount"
                    width="180">
                </el-table-column>
                <el-table-column
                    prop="type"
                    label="Type"
                    width="180">
                </el-table-column>
            </el-table>
        </div>


    </div>
    </div>
</template>

<script>
    import _ from 'lodash'
    import axios from 'axios'

    export default {
        name: "Main",
        data() {
            return {
                endpointUrl: 'http://localhost:8000',
                addWalletDialog: false,
                cardsLoading: false,
                addWalletLoading: false,
                isEditing: false,
                wallets: [],
                wallet: {
                    name: null,
                    type: 'credit_card'
                },
                balance: {
                    amount: null,
                    type: 'credit'
                },
                selectedWallet: null,
                addWalletBalanceLoading: false,
                addWalletBalanceDialog: false,

                tableData: []

            };
        },
        computed: {
            total: function (){
                return _.sumBy(this.wallets, 'total');
            }
        },
        watch: {
            selectedWallet: function (id){
                if(id){
                    axios.get(this.endpointUrl+"/wallets/"+id+"/balance").then(response => {

                        this.tableData = response.data;
                    }).catch(e => {

                    });
                }
            }
        },
        components:{
            VBoilerplate: {
                functional: true,

                render(h, {data, props, children}) {
                    return h('v-skeleton-loader', {
                        ...data,
                        props: {
                            boilerplate: true,
                            elevation: 2,
                            ...props,
                        },
                    }, children)
                },
            }
        },
        methods: {
            async handleCreateWallet(){

                let handleCallback =  (response) => {
                    if(this.isEditing){
                        let wallet = this.wallets.filter(wallet => wallet.id == response.data.id);
                        wallet = response.data;
                    }else{
                        this.wallets.push(response.data)
                    }

                    this.addWalletLoading = false;
                    this.addWalletDialog = false;
                    this.wallet = {
                        amount: null,
                        type: 'credit_card'
                    };
                    this.$refs['name'].innerText = '';
                    this.$refs['type'].innerText = '';
                };
                this.addWalletLoading = true;
                if(this.isEditing){
                    axios.patch(this.endpointUrl+"/wallets/"+this.wallet.id, this.wallet).then(handleCallback).catch(e => {
                        this.addWalletLoading = false;

                        Object.keys(e.response.data.errors).map(key => {
                            this.$refs[key].innerText = e.response.data.errors[key][0]
                        });

                    });
                }else{
                    axios.post(this.endpointUrl+"/wallets", this.wallet).then(handleCallback).catch(e => {
                        this.addWalletLoading = false;
                        Object.keys(e.response.data.errors).map(key => {
                            this.$refs[key].innerText = e.response.data.errors[key][0]
                        });
                    });
                }

            },

            openAddWallet(){
                this.addWalletDialog = true;
                this.isEditing = false;
                this.wallet = {
                    amount: null,
                    type: 'credit_card'
                };
            },

            getWallets(){
                this.cardsLoading = true;
                axios.get(this.endpointUrl+"/wallets").then(response => {
                    this.wallets = response.data;
                    this.cardsLoading = false;
                }).catch(e => {
                    this.cardsLoading = false;

                });
            },

            openEditWallet(item){
                this.isEditing = true;
                this.wallet = item;
                this.addWalletDialog = true;
            },

            deleteWallet(item){
                let c = confirm("Are you sure?");
                if(c){
                    axios.delete(this.endpointUrl+"/wallets/"+item.id).then(response => {
                        this.wallets = this.wallets.filter(wallet => wallet.id != item.id);
                        if(this.selectedWallet === item.id){
                            this.selectedWallet = null;
                            this.tableData = [];
                        }
                    }).catch(e => {

                    });
                }
            },
            openCredit(type){
                this.addWalletBalanceDialog = true;
                this.balance.type = type;
            },
            handleCreateWalletBalanace(){
                this.addWalletBalanceLoading = true;
                axios.post(this.endpointUrl+"/wallets/"+this.selectedWallet+"/balance", this.balance).then((response) => {
                    this.wallet = this.wallets.filter(wallet => wallet.id == this.selectedWallet)[0];
                    this.wallet.total = response.data.wallet.total;
                    this.tableData.push(response.data);

                    this.addWalletBalanceLoading = false;
                    this.addWalletBalanceDialog = false;
                    this.balance = {};
                    this.$refs['amount'].innerText = '';
                }).catch(e => {
                    this.addWalletBalanceLoading = false;
                    Object.keys(e.response.data.errors).map(key => {
                        this.$refs[key].innerText = e.response.data.errors[key][0]
                    });

                });
            }
        },
        created() {
            this.getWallets();
        },
    }
</script>

<style scoped>
    .card-hover >>> .headline {
        font-size: 22px;
    }
    .custom-input >>> .el-form-item{
        display: inline-block;
    }
</style>
