<template>
    <modal :modalId="modalId">
        <template slot="header">
            Change the project name
        </template>
        <template slot="body">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action='/we-have-to-change-elater' accept-charset="UTF-8" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
                        <div class="form-group">
                            <label :for="idInput">Project name</label>
                            <input :id="idInput" v-model="form.project_name"  class="form-control" name="project_name" type="text" >
                            <span class="help is-danger invalid-feedback" style="display: inline" v-if="form.errors.has('project_name')" v-text="form.errors.get('project_name')"></span>
                        </div>
                        <input type="submit" :disabled="form.errors.any()" class="btn btn-primary" value="submit">
                    </form>
                </div>
                <div class="card-footer">
                    <div class="alert alert-danger" role="alert" v-if="form.errors.has('otherError')" v-text="form.errors.get('otherError')">
                    </div>
                </div>
            </div>
        </template>
    </modal>
</template>

<script>
    import modal from './modal.vue';
    import Form from "../forms/form";
    export default {
        name: "editprojectname",
        components:{'modal':modal},
        props:['project_id','project_name'],
        data(){
            return {
                form: new Form({
                    project_name:this.project_name
                })
            }},
        methods: {
            onSubmit() {
                this.form.patch(`http://localhost:8000/projects/${this.project_id}/change_project_name`)
                    .then(response => window.location.href = 'http://localhost:8000/projects')
                        // `http://localhost:8000${response}`);
            }
        },
        computed:{
            modalId(){
                return this.project_name.concat(this.project_id,'edit_name')
            },
            idInput(){
                return "edit_project_name_input".concat(this.project_id)
            }
        },
        mounted(){
            var that=this
            $("#".concat(`${this.modalId}`)).on('hidden.bs.modal', function (e) {
                that.form.reset();
                that.form.project_name=that.project_name;
            })
        },
    }
</script>

<style scoped>

</style>