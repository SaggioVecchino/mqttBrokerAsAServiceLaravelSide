<template>
    <div>
        <label :for="'label'+this.numset"><b>Name of the set: </b></label><input placeholder="Name of the set" :name="'label'+this.numset" type="text" v-model="form.requestSets[numset]['label']" class="form-control"><br>
        <span class="help is-danger invalid-feedback" style="display: inline" v-if="formErrorsHasSetNameError()" v-text="formErrorsGetSetNameError()"></span>
        <ul>
            <li v-for="index in this.nbTopics" :key="index">
                <input placeholder="Topic name" :name="topicinputname(index)" type="text" v-model="form.requestSets[numset]['topics'][index-1]" class="form-control">
                <span class="help is-danger invalid-feedback" style="display: inline" v-if="formErrorsHasTopicInput(index-1)" v-text="formErrorsGetTopicInput(index-1)"></span>
            </li>
        </ul>
        <br>
        <button type="button" @click="addTopic()" class="btn btn-secondary">Add a topic</button>
        <br><br>
        <button type="button" @click="notAllDevices()" v-if="allDevicesButton" class="btn btn-secondary">Not all devices of the project</button><br>
        <ul v-if="nbDevices>0"><!-- -->
            <li v-for="index in nbDevices" :key="index">
                <device :numset="numset" :numdevice="index-1" :form="form"></device><br>
            </li>
            <br>
            <button type="button" @click="addDevice()" class="btn btn-secondary">Add devices</button>
        </ul>
        <hr>
    </div>
</template>

<script>
import device from "./device.vue";
import Form from "../forms/form";

export default {
  name: "requestset",
  components: {
    device: device
  },
  props: ["numset", "form"],
  data() {
    return {
      nbTopics: 1,
      nbDevices: 0,
      allDevicesButton: true
    };
  },
  methods: {
    topicinputname(index) {
      return "requestSets[".concat(this.numset, "][topics][", index - 1, "]");
    },
    addTopic() {
      this.form.requestSets[this.numset]["topics"][this.nbTopics] = "";
      this.nbTopics++;
    },
    notAllDevices() {
      this.form.requestSets[this.numset]["devices"] = [];
      this.addDevice();
      this.allDevicesButton = false;
    },
    addDevice() {
      this.form.requestSets[this.numset]["devices"][this.nbDevices] = {};
      this.form.requestSets[this.numset]["devices"][this.nbDevices][
        "group_name"
      ] =
        "";
      this.nbDevices++;
    },
    formErrorsHasTopicInput(index) {
      return this.form.errors.has(
        "requestSets.".concat(this.numset, ".topics.", index)
      );
    },
    formErrorsGetTopicInput(index) {
      return this.form.errors.get(
        "requestSets.".concat(this.numset, ".topics.", index)
      );
    },
    formErrorsHasSetNameError() {
      return this.form.errors.has("requestSets.".concat(this.numset, ".label"));
    },
    formErrorsGetSetNameError() {
      return this.form.errors.get("requestSets.".concat(this.numset, ".label"));
    }
  },
  computed: {},
  mounted() {},
  created() {}
};
</script>