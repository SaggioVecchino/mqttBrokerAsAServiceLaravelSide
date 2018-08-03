<template>
    <div class="card">
        <div class="card-header">
            <b>Id: </b>{{ group.id }}
            <br> <b>Group name: </b> {{ group.group_name }}
        </div>
        <div class="card-body">
            <ul>
                <li><a :href="'/device_groups/'+group.id"><button type="button" class="btn btn-primary">show</button></a></li>
                <li>
                    <editgroup :group_name="group.group_name " :group_id="group.id"></editgroup>
                </li>
                <li>
                    <confirm btncontent="Delete" :modalId="'delete'+group.id"
                             :title="'Are you sure to delete the group: '+group.group_name+' with id :'+group.id"
                             confirmButtonText="YES" denyButtonText="No" type="danger" @confirm="deleteGroup"></confirm>

                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import editgroup from "./editgroupform";
import confirm from "./confirm.vue";

export default {
  name: "group",
  components: {
    editgroup: editgroup,
    confirm: confirm
  },
  data() {
    return {
      thegroup: this.group
    };
  },
  props: ["group"],
  methods: {
    deleteGroup() {
      var url = `http://localhost:8000/device_groups/${this.group.id}`;
      axios.delete(url).then(response => {
        this.$emit("groupDeleted", this.group.id);
        $("#".concat("delete", this.group.id)).modal("hide");
      });
    }
  }
};
</script>

<style scoped>
</style>