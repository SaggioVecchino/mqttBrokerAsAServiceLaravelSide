<template>
    <div class="card">
        <div class="card-header"><b>ID:</b> {{ project.id }}<br><b>Project name:</b> {{ project.project_name }}
        </div>
        <div class="card-body">
            <ul>
                <li>
                <specifydata :project_id="project.id" :project_name="project.project_name"></specifydata></li>
                <li><a :href="'/projects/'+project.id"><button type="button" class="btn btn-primary">show groups</button></a></li>
                <li  v-if="userid === theproject.owner">
                    <confirm v-if="userid === theproject.owner" btncontent="Delete" :modalId="'delete'+project.id"
                             :title="'Are you sure to delete the project: '+project.project_name+' with id :'+project.id"
                             confirmButtonText="YES" denyButtonText="No" type="danger" @confirm="deleteProject"></confirm>
                </li>
                <li  v-if="userid === theproject.owner">
                    <contributors :projectname="project.project_name" :projectid="project.id"
                                  :userid="userid" :modalid="'contributors'+project.id">

                    </contributors>
                </li>
                <li  v-if="userid === theproject.owner">
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
                </li>
            </ul>

            <editprojectname :project_id="project.id"
                             :project_name="project.project_name"></editprojectname>

            <editprojectpassword :project_id="project.id"
                                 :project_name="project.project_name"></editprojectpassword>

        </div>
    </div>
</template>

<script>
import editprojectname from "./editprojectname.vue";
import editprojectpassword from "./editprojectpassword.vue";
import confirm from "./confirm.vue";
import specifydata from "./specifydata.vue";
import contributors from "./contributors";

export default {
  name: "project",
  components: {
    editprojectname: editprojectname,
    editprojectpassword: editprojectpassword,
    confirm: confirm,
    contributors: contributors,
    specifydata: specifydata
  },
  data() {
    return {
      theproject: this.project
    };
  },
  props: ["project", "userid"],
  methods: {
    deleteProject() {
      var url = `http://iot2.brainiac.dz/projects/${this.project.id}`;
      axios.delete(url).then(response => {
        this.$emit("projectDeleted", this.project.id);
        $("#".concat("delete", this.project.id)).modal("hide");
      });
    }
  }
};
</script>

<style scoped>
</style>