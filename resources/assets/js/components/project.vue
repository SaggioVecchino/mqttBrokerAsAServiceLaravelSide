<template>
    <div class="card">
        <div class="card-header"><b>ID:</b> {{ project.id }}<br><b>Project name:</b> {{ project.project_name }}
        </div>
        <div class="card-body">
            <ul>
                <li><a :href="'/projects/'+project.id+'/specify_data'">show statistics</a></li>
                <li><a :href="'/projects/'+project.id">show groups</a></li>

            </ul>
            <editprojectname :project_id="project.id"
                             :project_name="project.project_name"></editprojectname>
            <editprojectpassword :project_id="project.id"
                                 :project_name="project.project_name"></editprojectpassword>
            <div class="btn-group dropright">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    edit
                </button>
                <div class="dropdown-menu">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            :data-target="'#'+project.project_name+project.id+'edit_name'">name
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            :data-target="'#'+project.project_name+project.id+'edit_password'">password
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import editprojectname from './editprojectname.vue';
    import editprojectpassword from './editprojectpassword.vue';

    export default {
        name: "project",
        components:
            {
                'editprojectname': editprojectname,
                'editprojectpassword':editprojectpassword
            },
        data() {
            return {
                theproject: this.project
            }
        },
        props: ['project']
    }
</script>

<style scoped>

</style>