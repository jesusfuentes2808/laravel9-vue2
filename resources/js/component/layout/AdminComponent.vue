<template>
    <div id="app-layout">

        <nav class="navbar navbar-expand navbar-dark bg-primary">
            <b-button  v-b-toggle.sidebar-variant id="menu-toggle" class="navbar-brand">
                <span class="navbar-toggler-icon"></span>
            </b-button>
            <b-button  v-b-toggle.sidebar-variant class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample02" aria-controls="navbarsExample02" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </b-button>
            <div class="collapse navbar-collapse" id="navbarsExample02">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <router-link to="/">Home</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/example">Example</router-link>
                    </li>
                </ul>
                <form class="form-inline my-2 my-md-0"> </form>
                <div style="color: white; float: right">
                    <a href="#" style="color: white; text-decoration: underline" @click.prevent="closeSessionLocal">Cerrar Sesiòn</a></div>
            </div>
        </nav>
        <div id="wrapper" class="toggled">
            <div>
                <!--<b-button v-b-toggle.sidebar-variant>Toggle Sidebar</b-button>-->
                <b-sidebar id="sidebar-variant" title="Sidebar" bg-variant="dark" text-variant="light" shadow>
                    <div class="px-3 py-2">
                        <ul class="sidebar-nav">
                            <li> <router-link to="/admin/invoice/">Facturas</router-link></li>
                            <li> <router-link to="/admin/invoice/create">CREAR Factura</router-link></li>
                        </ul>
                    </div>
                </b-sidebar>
            </div>
            <!-- Sidebar -->
            <div id="sidebar-wrapper">

            </div> <!-- /#sidebar-wrapper -->
            <!-- Page Content -->
            <div id="page-content-wrapper">
                <router-view></router-view>
            </div> <!-- /#page-content-wrapper -->
        </div> <!-- /#wrapper -->
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

    export default {
        mounted() {
            this.setIVA();
        },
        methods: {
            ...mapActions(["setIVA", "closeSession"]),
            closeSessionLocal: function(){
                this.closeSession();
            }
        },
        computed: mapGetters(["token"]),
        watch:{
            token(newValue){
                if(newValue === null){
                    this.$router.push({
                        path: "/"
                    })
                }
            }
        }
    }
</script>
<style>

</style>
