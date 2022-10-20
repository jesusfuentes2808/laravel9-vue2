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
            <div v-if="userNotificationLogin.submit" class="message">
                <span v-if="userNotificationLogin.response.status === 'FAIL'" style="display: inline-block; margin-bottom: 10px; color: #d93545; font-weight: bold; font-size: 12px">
                    Por favor, revise sus credenciales
                </span>
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
                        email: "",
                        password: "",
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
        computed: mapGetters(["userNotificationLogin"]),
        watch: {
            userNotificationLogin(newVal) {
                if(newVal.submit === true){
                    if(newVal.response.status === "OK"){
                        this.$router.push({
                            path: "/admin/invoice"
                        })
                    }
                }
            },
        }
    }
</script>
<style>

</style>
