<template>
    <modalcontainer :modalId="'specify_data'+project_id" btncontent="Show statistics">
        <template slot="header">Specify data you want to see from the project:
            <br>
            <b>ID</b>: {{this.project_id}}
            <br>
            <b>Project name</b>: {{this.project_name}}
        </template>
        <template slot="body">
            <div class="card">
                <form method="POST" :action="actionLink" accept-charset="UTF-8">
                <div class="card-body">
                    <!-- -->
                    <div class="requestSets" v-for="index in nbSets" :key="index">
                        <requestset :numset="index-1"></requestset>
                    </div>
                    <button @click="addRequestSet()" type="button">Add a set</button>

                </div>
                <br>
                <b>Interval :</b>
                <select name="interval">
                    <option value="Y">Year</option>
                    <option value="M">Month</option>
                    <option value="W">Week</option>
                    <option value="D">Day</option>
                    <option value="H">Hour</option>
                </select>
                <br>
                <b>Frequence: </b>
                <select name="freq">
                    <option value="M">Month</option>
                    <option value="W">Week</option>
                    <option value="D">Day</option>
                    <option value="H">Hour</option>
                    <option value="Mn">Minute</option>
                </select>
                <br>
                <b>Aggregate: </b>
                <select name="agg">
                    <option value="avg">avg</option>
                    <option value="max">max</option>
                    <option value="count">count</option>
                    <option value="min">min</option>
                    <option value="sum">sum</option>
                </select>
                <br>
                <b>Type of graph: </b>
                <select name="type">
                    <option value="line">Line</option>
                    <option value="bar">Bar</option>
                </select>
                <input type="submit" value="OK">
            </form>
            </div>
            <!-- <div class="card-footer">
                        <div class="alert alert-danger" role="alert" v-if="form.errors.has('otherError')" v-text="form.errors.get('otherError')">
                        </div>
                    </div> -->
        </template>
    </modalcontainer>
</template>

<script>
import requestset from "./requestset.vue";
import modalcontainer from "./modalcontainer.vue";

export default {
  name: "specifydata",
  components: {
    modalcontainer: modalcontainer,
    requestset: requestset
  },
  props: ["project_id", "project_name"],
  data() {
    return {
      /* form: new Form({
                            project_id: this.project_id,
                            requestSets: [],
                            interval: '',
                            freq: '',
                            agg: '',
                            type: ''
                          }), */
      nbSets: 1
    };
  },
  methods: {
    /* onSubmit() {
      this.form
        .post("http://localhost:8000/projects/" + project_id + "/show_data")
        .then(
          response =>
            (window.location.href = `http://localhost:8000${response}`)
        );
    }, */
    addRequestSet() {
      this.nbSets++;
    }
  },
  computed: {
    actionLink() {
      return "http://localhost:8000/projects/".concat(
        this.project_id,
        "/show_data"
      );
    }
  },
  mounted() {
    var that = this;
    $("#".concat(`${this.project_id}`)).on("hidden.bs.modal", function(e) {});
  }
};
</script>


<style scoped>
</style>