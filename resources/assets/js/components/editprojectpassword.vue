<template>
    <modal :modalId="modalId">
        <template slot="header">
            Change <b>password</b>
        </template>
        <template slot="body">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action='/we-have-to-change-elater' accept-charset="UTF-8" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
                        <div class="form-group">

                            <label :for="passInputId(1)">Old password</label>

                            <input :id="passInputId(1)" v-model="form.old_password"type="password" placeholder="Old password" class="form-control" name="old_password"  >

                            <span class="help is-danger invalid-feedback" style="display: inline" v-if="form.errors.has('old_password')" v-text="form.errors.get('old_password')"></span>

                        </div>

                        <div class="form-group">

                            <label :for="passInputId(2)">New password</label>

                            <input :id="passInputId(2)" v-model="form.password" type="password" placeholder="New password" class="form-control" name="password"  >

                            <span class="help is-danger invalid-feedback" style="display: inline" v-if="form.errors.has('password')" v-text="form.errors.get('password')"></span>

                        </div>
                        <input type="submit" :disabled="form.errors.any()" class="btn btn-primary" value="submit">

                    </form>
                </div>
            </div>
        </template>
    </modal>

</template>

<script>
    import modal from './modal.vue';
    import Form from "../forms/form";
    export default {

        name: "editprojectpassword"
        ,
        components:{'modal':modal},
        props:['project_id','project_name'],
        data(){
            return {
                form: new Form({
                    old_password:'',
                    password:''
                })
            }},
        methods: {
            onSubmit() {
                var help=this.addError
                this.form.patch(`http://localhost:8000/projects/${this.project_id}/change_password`)
                    .then(function (response ){
                        if (response.errors)
                        {
                            help(response.errors)
                        }
                        else
                            window.location.href = 'http://localhost:8000/projects'
                    })
            },
            addError(e){
                this.form.onFail(e);
            },
            passInputId(i){
                return `edit_project_password_input${i}`.concat(this.project_id)
            }
        },
        computed:{
            modalId(){
                return this.project_name.concat(this.project_id,'edit_password')
            },
            idInput(){
                return `edit_project_password_input`.concat(this.project_id)
            }
        }
    }
</script>

<style scoped>

</style>