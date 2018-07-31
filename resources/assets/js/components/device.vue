<template>
    <div>
        <input placeholder="group_name" :name="group_name_input" v-model="form.requestSets[numset]['devices'][numdevice]['group_name']"
            type="text">
        <span class="help is-danger invalid-feedback" style="display: inline" v-if="formErrorsHasGroupNameInput()" v-text="formErrorsGetGroupNameInput()"></span>
        <button class="specifyDevice" type="button" @click="specifyDevice()" v-if="showButtonSpecifyDevice">Just some devices of the group</button>
        <input type="text" placeholder="device_name (empty for all)" :name="device_name_input" v-model="form.requestSets[numset]['devices'][numdevice]['device_name']"
            v-if="showDeviceNameInput">
        <span class="help is-danger invalid-feedback" style="display: inline" v-if="formErrorsHasDeviceNameInput()" v-text="formErrorsGetDeviceNameInput()"></span>
    </div>
</template>

<script>
import Form from "../forms/form";

export default {
  name: "device",
  props: ["numset", "numdevice", "form"],
  data() {
    return {
      showButtonSpecifyDevice: true
    };
  },
  methods: {
    specifyDevice() {
      this.form.requestSets[this.numset]["devices"][this.numdevice] = {};
      this.form.requestSets[this.numset]["devices"][this.numdevice][
        "device_name"
      ] =
        "";
      this.showButtonSpecifyDevice = false;
    },
    formErrorsHasDeviceNameInput() {
      return this.form.errors.has(
        "requestSets.".concat(
          this.numset,
          ".devices.",
          this.numdevice,
          ".device_name"
        )
      );
    },
    formErrorsGetDeviceNameInput() {
      return this.form.errors.get(
        "requestSets.".concat(
          this.numset,
          ".devices.",
          this.numdevice,
          ".device_name"
        )
      );
    },
    formErrorsHasGroupNameInput() {
      return this.form.errors.has(
        "requestSets.".concat(
          this.numset,
          ".devices.",
          this.numdevice,
          ".group_name"
        )
      );
    },
    formErrorsGetGroupNameInput() {
      return this.form.errors.get(
        "requestSets.".concat(
          this.numset,
          ".devices.",
          this.numdevice,
          ".group_name"
        )
      );
    }
  },
  computed: {
    group_name_input() {
      return "requestSets[".concat(
        this.numset,
        "][devices][",
        this.numdevice,
        "][group_name]"
      );
    },
    device_name_input() {
      return "requestSets[".concat(
        this.numset,
        "][devices][",
        this.numdevice,
        "][device_name]"
      );
    },
    showDeviceNameInput() {
      return !this.showButtonSpecifyDevice;
    }
  },
  mounted() {},
  created() {}
};
</script>