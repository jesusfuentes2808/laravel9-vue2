<template>
    <div class="container-fluid">
        <h1>Facturas</h1>
        <p>Crear factura</p>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <button  class="btn btn-success mb-10"  style="margin-bottom: 10px; float: right" @click="saveInvoiceForm">
                        Actualizar VENTA
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div id="div_id_catagory" class="form-group required col-6">
                            <label for="id_catagory" class="control-label col-md-4  requiredField"> Nombre Cliente<span class="asteriskField">*</span> </label>
                            <div class="controls col-md-12 ">
                                <input v-model="form.data.client_name" class="input-md textinput textInput form-control" id="id_catagory" name="catagory" placeholder="skills catagory" style="margin-bottom: 10px" type="text" />
                            </div>
                        </div>
                        <div id="div_id_catagory" class="form-group required col-6">
                            <label for="id_catagory" class="control-label col-md-4  requiredField"> Nit Cliente<span class="asteriskField">*</span> </label>
                            <div class="controls col-md-12 ">
                                <input v-model="form.data.client_nit" class="input-md textinput textInput form-control" id="id_catagory" name="catagory" placeholder="skills catagory" style="margin-bottom: 10px" type="text" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="div_id_catagory" class="form-group required col-6">
                            <label for="id_catagory" class="control-label col-md-4  requiredField"> Nombre Emisor<span class="asteriskField">*</span> </label>
                            <div class="controls col-md-12 ">
                                <input v-model="form.data.business_name" class="input-md textinput textInput form-control" id="id_catagory" name="catagory" placeholder="skills catagory" style="margin-bottom: 10px" type="text" />
                            </div>
                        </div>
                        <div id="div_id_catagory" class="form-group required col-6">
                            <label for="id_catagory" class="control-label col-md-4  requiredField"> Nit Emisor<span class="asteriskField">*</span> </label>
                            <div class="controls col-md-12 ">
                                <input v-model="form.data.business_nit" class="input-md textinput textInput form-control" id="id_catagory" name="catagory" placeholder="skills catagory" style="margin-bottom: 10px" type="text" />
                            </div>
                        </div>
                    </div>

                    <div v-if="errorInvoiceCreate.submit" >
                        <template v-if="errorInvoiceCreate.response.status === 'FAIL' && errorInvoiceCreate.response.message == 'Validar datos'">
                            <span v-for="(item, index) in errorInvoiceCreate.response.data" style="color: #d93545; font-weight: bold; font-size: 12px">
                                error "{{index}}":
                                <ul v-if="item.length > 0">
                                    <li v-for="itemIn in item">{{itemIn}}</li>
                                </ul>
                            </span>
                        </template>
                        <template v-if="errorInvoiceCreate.response.status === 'OK'">
                            <span style="color: #1a871a; font-weight: bold; font-size: 12px; margin-bottom: 15px; display: inline-block">
                                Success register
                            </span>
                        </template>
                    </div>
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Còdigo de producto</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Precio Unidad</th>
                    <th>Precio Total</th>
                    <th style="width: 40px">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(product, index) in form.products">
                    <td>{{product.sku}}</td>
                    <td>
                        <select name="" id="" v-model="product.sku" style="width: 300px;" @change="selectProduct(product.sku, index)">
                            <option value="">Seleccionar Producto</option>
                            <option v-for="optProduct in productList" :value="optProduct.sku">{{optProduct.name}}</option>
                        </select>
                    </td>
                    <td>
                        <select name="" id="" v-model="product.qty" @change="selectProduct(product.sku, index)">
                            <option v-for="i in 10" :value="i">{{i}}</option>
                        </select>
                    </td>
                    <td style="text-align: right;">{{product.price_unity}}</td>
                    <td style="text-align: right;">{{product.price_total}}</td>
                    <td>
                        <p>
                            <a href ="#" @click.prevent="saveProduct(index)" >Agregar</a>
                            <a href="#"  @click.prevent="deleteProduct(index)"  >Eliminar</a>
                        </p>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">SUB TOTAL</td>
                        <td style="text-align: right;"><b>{{form.subtotal}}</b></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="4">IMPUESTOS ({{iva}} %)</td>
                        <td style="text-align: right;"><b>{{form.iva}}</b></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="4">TOTAL</td>
                        <td style="text-align: right;"><b>{{form.total}}</b></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>
<script>
    import {mapActions, mapGetters} from "vuex";
    import VueSelect from "vue-select2";
    import Autocomplete from 'vue2-autocomplete-js'
    export default {
        components:{
            VueSelect
        },
        data(){
            return{
                form:{
                    data:{
                        id: 0,
                        client_nit: '',
                        client_name: '',
                        business_nit: '',
                        business_name: '',
                        invoice_details: [],
                    },
                    products: [{
                        sku: '',
                        name: '',
                        qty: 1,
                        price_unity:0,
                        price_total:0,
                    }],
                    total: 0,
                    subtotal: 0,
                    iva: 0

                }
            }
        },
        mounted() {
            this.fetchProducts();
            this.form.data.id = this.$route.params.id;
            this.getByIdInvoice(this.$route.params.id);
        },
        computed: mapGetters(["productList", "iva", "errorInvoiceCreate", "invoice"]),
        methods: {
            ...mapActions(["fetchProducts", "updateInvoice", "getByIdInvoice"]),
            getTotal: function(){
                this.form.total =0;
                this.form.products.forEach((item)=>{
                    console.log(parseFloat(item.price_total));
                    this.form.total += parseFloat(item.price_total);
                });
                this.form.total = this.form.total.toFixed(2);
                this.form.iva = (this.form.total * (this.iva/100)).toFixed(2);
                this.form.subtotal = (this.form.total - this.form.iva).toFixed(2);
            },
            selectProduct: function(sku, index){
                if(sku == undefined || sku == '') return false;
                const productSelect = this.productList.find((item) => item.sku === sku);
                console.log(productSelect);
                const priceTotal = parseFloat(this.form.products[index].qty * productSelect.price);
                this.form.products[index].sku = productSelect.sku;
                this.form.products[index].name = productSelect.name;
                this.form.products[index].price_unity = parseFloat(productSelect.price).toFixed(2);
                this.form.products[index].price_total = priceTotal.toFixed(2);
                this.getTotal();
            },
            saveProduct(index){
                if(this.form.products[index].sku !== ''){
                    this.form.products.push({
                        sku: '',
                        name: '',
                        qty: 1,
                        price_unity:0,
                        price_total:0,
                    });
                } else {
                    alert("Agregar un producto");
                }
            },
            deleteProduct(index){
                if(this.form.products.length > 1){
                    this.form.products.splice(index, 1);
                    this.getTotal();
                } else {
                    alert("Ud debe tener al menos un producto");
                }
            },
            saveInvoiceForm(){
                const validListProductEmpty = this.form.products.some((item) => item.sku === '' || item.sku === undefined);
                if(validListProductEmpty){
                    alert("Por favor, completar todos los productos");
                } else {
                    this.form.data.invoice_details = this.form.products.map((item) => ({ sku: item.sku, qty: item.qty }));
                    console.log(this.form.data);
                    this.updateInvoice(this.form.data);
                }
            }
        },
        watch: {
            invoice: function(newValue, oldValue){
                if(newValue != null) {
                    this.form.data = {
                        client_nit: newValue.client_nit,
                        client_name: newValue.client_name,
                        business_nit: newValue.business_nit,
                        business_name: newValue.business_name,
                        //invoice_details: newValue.invoice_details,
                        id: this.$route.params.id
                    };
                    this.form.products = [];

                    newValue.invoice_details.forEach((item) => {
                        this.form.products.push({
                            sku: item.product.sku,
                            name: item.product_description,
                            qty: parseInt(item.qty),
                            price_unity: item.product_price_sale,
                            price_total: item.product_price_total,
                        })
                    });

                    this.getTotal();
                }
            },
            errorInvoiceCreate(newValue, oldValue) {
                if(newValue.submit == true && newValue.response.status === 'OK'){
                    this.$router.push({
                        path: "/admin/invoice"
                    })
                }
            }

        }
    }
</script>
