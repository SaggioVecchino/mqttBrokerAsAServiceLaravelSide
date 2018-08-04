<template>
    <modalcontainer :modalId="idModal" btncontent="Show statistics">
        <template slot="header">Specify data you want to see from the project:
            <br>
            <b>ID</b>: {{this.project_id}}
            <br>
            <b>Project name</b>: {{this.project_name}}
        </template>
        <template slot="body">
            <div class="card px-3 py-3">
                <form method="POST" :action="actionLink" accept-charset="UTF-8" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
                <div class="card-body">
                    <!-- -->
                    <div class="requestSets" v-for="index in nbSets" :key="index">
                        <requestset :numset="index-1" :form="form"></requestset>
                    </div>
                    <button @click="addRequestSet()" class="btn btn-secondary" type="button">Add a set</button>

                </div>
                <br>
                <b>Interval: </b>
                <select name="interval" v-model="form.interval" class="form-control">
                    <option value="Y">Year</option>
                    <option value="M">Month</option>
                    <option value="W">Week</option>
                    <option value="D">Day</option>
                    <option value="H">Hour</option>
                </select>
                <span class="help is-danger invalid-feedback" style="display: inline" v-if="form.errors.has('interval')" v-text="form.errors.get('interval')"></span>
                <br>
                <b>Frequence: </b>
                <select name="freq" v-model="form.freq" class="form-control">
                    <option value="M">Month</option>
                    <option value="W">Week</option>
                    <option value="D">Day</option>
                    <option value="H">Hour</option>
                    <option value="Mn">Minute</option>
                </select>
                <span class="help is-danger invalid-feedback" style="display: inline" v-if="form.errors.has('freq')" v-text="form.errors.get('freq')"></span>
                <br>
                <b>Aggregate: </b>
                <select name="agg" v-model="form.agg" class="form-control">
                    <option value="avg">avg</option>
                    <option value="max">max</option>
                    <option value="count">count</option>
                    <option value="min">min</option>
                    <option value="sum">sum</option>
                </select>
                <span class="help is-danger invalid-feedback" style="display: inline" v-if="form.errors.has('agg')" v-text="form.errors.get('agg')"></span>
                <br>
                <b>Type of graph: </b>
                <select name="type" v-model="form.type" class="form-control">
                    <option value="line">Line</option>
                    <option value="bar">Bar</option>
                </select>
                <span class="help is-danger invalid-feedback" style="display: inline" v-if="form.errors.has('type')" v-text="form.errors.get('type')"></span>
                <br><input type="submit" value="OK" class="btn btn-primary">
              </form>
              <div class="card-footer" v-if="form.errors.has('otherError')">
                  <div class="alert alert-danger" role="alert" v-text="form.errors.get('otherError')"></div>
              </div>
            </div>
        </template>
    </modalcontainer>
</template>

<script>
import requestset from "./requestset.vue";
import modalcontainer from "./modalcontainer.vue";
import Form from "../forms/form";

export default {
  name: "specifydata",
  components: {
    modalcontainer: modalcontainer,
    requestset: requestset
  },
  props: ["project_id", "project_name"],
  data() {
    return {
      form: new Form({
        project_id: this.project_id,
        requestSets: [],
        interval: "Y",
        freq: "M",
        agg: "avg",
        type: "line"
      }),
      nbSets: 1
    };
  },
  methods: {
    onSubmit() {
      var that = this;
      var myForm = this.form.data();
      this.form.post(this.actionLink).then(function(response) {
        var util = {};
        util.post = function(url, fields) {
          var $form = $("<form>", {
            action: url,
            method: "post"
          });
          $.each(fields, function(key, val) {
            if (key !== "requestSets")
              $("<input>")
                .attr({
                  type: "hidden",
                  name: key,
                  value: val
                })
                .appendTo($form);
          });
          for (var i = 0; i < that.nbSets; i++) {
            var f = fields["requestSets"];
            $("<input>")
              .attr({
                type: "hidden",
                name: "requestSets[" + i + "][label]",
                value: f[i]["label"]
              })
              .appendTo($form);
            $.each(f[i]["topics"], function(key, val) {
              $("<input>")
                .attr({
                  type: "hidden",
                  name: "requestSets[" + i + "][topics][" + key + "]",
                  value: val
                })
                .appendTo($form);
            });
            $.each(f[i]["devices"], function(key, val) {
              $("<input>")
                .attr({
                  type: "hidden",
                  name:
                    "requestSets[" + i + "][devices][" + key + "][group_name]",
                  value: val["group_name"]
                })
                .appendTo($form);
            });
            $.each(f[i]["devices"], function(key, val) {
              if (typeof f[i]["devices"][key]["device_name"] !== "undefined")
                $("<input>")
                  .attr({
                    type: "hidden",
                    name:
                      "requestSets[" +
                      i +
                      "][devices][" +
                      key +
                      "][device_name]",
                    value: val["device_name"]
                  })
                  .appendTo($form);
            });
          }
          $form.appendTo("body").submit();
        };
        util.post(that.actionLink, myForm);
      });
    },
    addRequestSet() {
      this.form.requestSets[this.nbSets] = {};
      this.form.requestSets[this.nbSets]["label"] = "Set-".concat(
        this.nbSets + 1
      );
      this.form.requestSets[this.nbSets]["topics"] = [];
      this.form.requestSets[this.nbSets]["topics"][0] = "";
      this.nbSets++;
    }
  },
  computed: {
    actionLink() {
      return "http://iot2.brainiac.dz/projects/".concat(
        this.project_id,
        "/show_data"
      );
    },
    idModal() {
      return "specify_data".concat(this.project_id);
    }
  },
  mounted() {
    var that = this;
    /* $("#".concat(this.idModal)).on("hidden.bs.modal", function(e) {
      that.form.reset();
    }); */
  },
  created() {
    this.form.requestSets = [];
    this.form.requestSets[0] = {};
    this.form.requestSets[0]["label"] = "Set-1";
    this.form.requestSets[0]["topics"] = [];
    this.form.requestSets[0]["topics"][0] = "";
  }
};
</script>


<style scoped>
</style>