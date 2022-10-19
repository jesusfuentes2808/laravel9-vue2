<template>
    <div class="container-fluid">
        <h1>Facturas</h1>
        <p>Listado de facturas</p>
        <div class="card-body">
            <b-alert v-if="messageInvoiceCreated != null" variant="success" show dismissible fade>Factura nro "{{messageInvoiceCreated.response.data.id}}" creada </b-alert>
            <div class="row">
                <div class="col-12">
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
                <tr v-if="invoiceList.length == 0">
                    <td colspan="6" class="text-center"> No existen registros</td>
                </tr>
                <tr v-else v-for="invoice in invoiceList">
                    <td>{{invoice.invoice_number}}</td>
                    <td>{{invoice.client_name}}</td>
                    <td>{{invoice.sale_user.name}}</td>
                    <td>{{invoice.total}}</td>
                    <td>{{invoice.created_at}}</td>
                    <td>
                        <p>
                            <router-link :to="{ name: 'admin.invoice.edit', params: { id: invoice.id }}">Actualizar</router-link>
                            <a href="#">Eliminar</a>
                        </p>
                    </td>
                </tr>
                </tbody>
            </table>
            {{prevRoute}}
        </div>
    </div>
</template>
<script>
    import {mapActions, mapGetters} from "vuex";
    export default {
        data(){
            return{
                messageInvoiceCreated: null,
                prevRoute: null
            }
        },
        mounted() {
            this.fetchInvoices();
            console.log("this.errorInvoiceCreate");
            console.log(this.errorInvoiceCreate);
            if(this.errorInvoiceCreate.submit != false){
                if(this.errorInvoiceCreate.response.status === 'OK' && this.errorInvoiceCreate.submit === true){
                    this.messageInvoiceCreated = this.errorInvoiceCreate;
                    this.resetErrorInvoiceCreate();
                }
            }

        },
        computed: mapGetters(["invoiceList", "errorInvoiceCreate"]),
        methods: {
            ...mapActions(["fetchInvoices", "resetErrorInvoiceCreate"]),
        },
    }
</script>
