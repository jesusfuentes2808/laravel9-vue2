<template>
    <div class="card-body register-card-body">
        <p class="login-box-msg">Register a new membership</p>

        <form action="../../index.html" method="post">
            <div class="input-group mb-3">
                <input v-model="form.user.name" type="text" class="form-control" placeholder="Full name">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>
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
            <div v-if="userNotificationErrorCreate.submit" >
                <template v-if="userNotificationErrorCreate.response.status === 'FAIL' && userNotificationErrorCreate.response.message == 'Validar datos'">
                    <span v-for="(item, index) in userNotificationErrorCreate.response.data" style="color: #d93545; font-weight: bold; font-size: 12px">
                        error "{{index}}":
                        <ul v-if="item.length > 0">
                            <li v-for="itemIn in item">{{itemIn}}</li>
                        </ul>
                    </span>
                </template>
                <template v-if="userNotificationErrorCreate.response.status === 'OK'">
                    <span style="color: #1a871a; font-weight: bold; font-size: 12px; margin-bottom: 15px; display: inline-block">
                        Success register
                    </span>
                </template>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block" @click.prevent="submitRegister">Registrar</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <p class="text-center mt-10">
            <router-link to="/login">Ya soy miembro</router-link>
        </p>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";
    export default {
        name: 'Register',
        data(){
            return {
                form: {
                    user:{
                        name: "JESUS FUENTES",
                        email: "jesusfuentes2808@gmail.com",
                        password: "jesusfuentes"
                    }
                }
            }
        },
        methods: {
            ...mapActions(["addUser"]),
            submitRegister(){
                this.addUser(this.form.user);
            }
        },
        computed:mapGetters(["userNotificationErrorCreate"]),
    }
</script>

<style scoped>

</style>
