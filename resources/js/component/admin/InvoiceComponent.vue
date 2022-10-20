<template>
    <div class="container-fluid">
        <h1>Facturas</h1>
        <p>Listado de facturas</p>
        <div class="card-body">
            <b-alert v-if="messageInvoiceCreated != null" variant="success" show dismissible fade>Factura nro "{{messageInvoiceCreated.response.data.id}}" creada </b-alert>
            <div class="row">
                <div class="col-12">
                    <div>
                        <label for="find">Buscar</label>
                        <input id="find" type="text" v-model="filter" @change="filterInvoice">
                    </div>
                    <router-link  class="btn btn-success mb-10"  style="margin-bottom: 10px; float: right" to="/admin/invoice/create">
                        NUEVO
                    </router-link>
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Nro Factura</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                    <th>Monto de venta</th>
                    <th>Fecha</th>
                    <th style="width: 40px">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="invoiceList.list.length == 0">
                    <td colspan="6" class="text-center"> No existen registros</td>
                </tr>
                <tr v-else v-for="invoice in invoiceList.list">
                    <td>{{invoice.invoice_number}}</td>
                    <td>{{invoice.client_name}}</td>
                    <td>{{invoice.sale_user.name}}</td>
                    <td>{{invoice.total}}</td>
                    <td>{{invoice.created_at}}</td>
                    <td>
                        <p>
                            <router-link :to="{ name: 'admin.invoice.edit', params: { id: invoice.id }}">Actualizar</router-link>
                            <a href="#" @click="deleteInvoiceLocal({ id: invoice.id })">Eliminar</a>
                        </p>
                    </td>
                </tr>
                </tbody>
            </table>
            <b-pagination
                v-model="currentPage"
                :total-rows="invoiceList.table.total"
                :per-page="invoiceList.table.pagination"
                first-number
            ></b-pagination>
        </div>
    </div>
</template>
<script>
    import {mapActions, mapGetters} from "vuex";
    export default {
        data(){
            return{
                currentPage: 1,
                filter: "",
                table: {
                    currentPage: 1,
                    totalRows: 100,
                    perPage: 5,
                },
                messageInvoiceCreated: null,
            }
        },
        mounted() {
            this.fetchInvoices();
            if(this.errorInvoiceCreate.submit != false){
                if(this.errorInvoiceCreate.response.status === 'OK' && this.errorInvoiceCreate.submit === true){
                    this.messageInvoiceCreated = this.errorInvoiceCreate;
                    this.resetErrorInvoiceCreate();
                }
            }

        },
        computed: mapGetters(["invoiceList", "errorInvoiceCreate"]),
        methods: {
            ...mapActions(["fetchInvoices", "resetErrorInvoiceCreate", "deleteInvoice"]),
            changeDataTable: function(currentPage = 1){
                const myData = {};
                myData['page'] = currentPage;
                if(this.filter!='')
                    myData['filter'] = this.filter;

                var out = [];

                for (var key in myData) {
                    if (myData.hasOwnProperty(key)) {
                        out.push(key + '=' + encodeURIComponent(myData[key]));
                    }
                }

                out = out.join('&');
                this.fetchInvoices(out);
            },
            deleteInvoiceLocal: function(data) {
                var dialog = confirm("¿Desea eliminar esta factura?");
                if (dialog) {
                    this.deleteInvoice(data);
                }
            },
            filterInvoice: function(){
                //this.changeDataTable();
            }
        },

        watch: {
            currentPage(newVal) {
               this.changeDataTable(newVal);
            },
            filter(newVal){
                this.changeDataTable();
            }
        },
    }
</script>
