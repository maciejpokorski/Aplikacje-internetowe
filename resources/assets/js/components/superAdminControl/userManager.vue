<template>
    <ul id="example-1">
        <li v-for="user in users">
            {{ user.email }}
        </li>
    </ul>
</template>

<script>
    export default {
        props: ['size', "offset"],

        data() {
            return {
                users: [],

            };
        },

        mounted() {
            this.getUsers();
        },

        methods:{
            getUsers(){
                axios.get('/api/users')
                    .then(response => {
                        this.users = response.data;
                    });
            }
        
        }
    }
</script>
