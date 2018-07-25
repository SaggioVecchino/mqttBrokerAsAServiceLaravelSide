<template>
    <modalcontainer modalId="Create-your-new-project" btncontent="Create a new project">
        <template slot="header">Create your new project</template>
        <template slot="body">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="/projects" accept-charset="UTF-8" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
                        <div class="form-group">
                            <label for="project_name_input">Project name</label>
                            <input id="project_name_input" v-model="form.project_name" placeholder="Project name" class="form-control" name="project_name" type="text" value="">
                            <span class="help is-danger invalid-feedback" style="display: inline" v-if="form.errors.has('project_name')" v-text="form.errors.get('project_name')"></span>
                            <small id="emailHelp" class="form-text text-muted">We'll never share your project name with anyone else.</small>
                        </div>
                        <div class="form-group">
                            <label for="project_name_pass">Password</label>
                            <input id="project_name_pass" v-model="form.password" placeholder="password" class="form-control" name="password" type="password" value="">
                            <span class="help is-danger invalid-feedback" style="display: inline" v-if="form.errors.has('password')" v-text="form.errors.get('password')"></span>
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
    </modalcontainer>
</template>

<script>
    import modalcontainer from './modalcontainer.vue';
    import Form from "../forms/form";

    export default {
        name: "newprojectform",
        components:{'modalcontainer':modalcontainer},
        data(){return {
            form: new Form({
                project_name: '',
                password: ''
            })
        }},

        methods: {
            onSubmit() {
                this.form.post('http://localhost:8000/projects')
                    .then(response => window.location.href = `http://localhost:8000${response}`);
            }
        }
    }
</script>
