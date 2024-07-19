<template>

    <div v-field-container>
        <field v-for="role of roles" :label="role.name">
            <user-select v-model="users[role.id]" :multiple="true" :allow-empty="true"/>
        </field>
    </div>
</template>

<script>

import Field from "@/Components/Field.vue";
import UserSelect from "@/Components/UserSelect.vue";
export default {
    components: {UserSelect, Field},

    props: {
        roles: Array,
        items: Array
    },
    data(){
        let users = {};
        for (const role of this.roles) {
            users[role.id] = [];
            for (const item of this.items) {
                if(item.role == role.id){
                    users[role.id].push(item.user);
                }
            }
        }
        console.log(users);

       return {
           users: users,
       }
    },
    emits: ['update:items'],
    methods: {},
    watch:{
        users: {
            deep: true,
            handler() {
                let items = [];
                for (const role in this.users) {
                    for (const user of this.users[role]) {
                        items.push({
                            role: role,
                            user_id: user.id
                        })
                    }
                }
                this.$emit('update:items', items);
            }
        }
    }
}
</script>

<style scoped lang="scss">
@import "resources/css/admin-vars";

</style>
