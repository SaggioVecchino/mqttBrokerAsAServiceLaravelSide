<template>
    <modalcontainer :modalId="'new_group'+project_id" btncontent="Create a new group">
        <template slot="header">Create a new group in the project with ID: {{project_id}}
        </template>
        <template slot="body">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="/device_groups" accept-charset="UTF-8" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
                        <input name="project_id" type="hidden" :value="this.project_id">
                        <div class="form-group">
                            <label for="group_name_input">Group name</label>
                            <input id="group_name_input" v-model="form.group_name" placeholder="Group name" class="form-control" name="group_name" type="text"
                                value="">
                            <span class="help is-danger invalid-feedback" style="display: inline" v-if="form.errors.has('group_name')" v-text="form.errors.get('group_name')"></span>
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
        name: "newgroupform",
        components: { 'modalcontainer': modalcontainer },
        props: ['project_id'],
        data() {
            return {
                form: new Form({
                    group_name: '',
                    project_id: this.project_id
                })
            }
        },
        methods: {
            onSubmit() {
                this.form.post('http://iot2.brainiac.dz/device_groups')
                    .then(response => window.location.href = `http://iot2.brainiac.dz${response}`);
            }
        },
        mounted() {
            var that = this
            $("#".concat(`${this.project_id}`)).on('hidden.bs.modal', function (e) {
                that.form.reset();
                that.form.project_id = that.project_id;
            })
        }
    }
</script>


<style scoped>
</style>