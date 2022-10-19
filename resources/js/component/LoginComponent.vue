<template>
    <div class="card-body">
        <p class="login-box-msg">Iniciar sesión</p>
        <form action="../../index3.html" method="post">
            <div class="input-group mb-3">
                <input v-model="form.user.email" type="email" class="form-control" placeholder="Email">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input v-model="form.user.password" type="password" class="form-control" placeholder="Password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- /.col -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block" @click.prevent="submitLogin" >Ingresar</button>
                </div>
                <!-- /.col -->
            </div>
        </form>


        <p class="mt-10 text-center">
            <router-link to="/register">Registrate</router-link>
        </p>
    </div>
</template>
<script>
import {mapActions, mapGetters, mapState} from "vuex";

    export default {
        name: 'Welcome',
        data(){
            return {
                form: {
                    user:{
                        email: "jesusfuentes2808@gmail.com",
                        password: "jesusfuentes",
                    }
                }
            }
        },
        methods: {
            ...mapActions(["login"]),
            submitLogin(){
                this.login(this.form.user);
            },
        },
        computed: mapGetters(["token"]),
        mounted() {
            console.log("MOUNTED");
        }
    }
</script>
<style>

</style>
