<template>
    <h1>Bienvenido WELCOME component {{usersList}}</h1>
</template>
<script>
    import {
        mapGetters,
        mapActions
    } from "vuex";
    export default {
        name: 'Welcome',
        methods: {
            ...mapActions(["fetchUsers", "deleteUser"])
        },
        computed: mapGetters(["usersList"]),
        created() {
            this.fetchUsers()
        }
    }
</script>
