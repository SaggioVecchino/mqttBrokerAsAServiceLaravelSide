<template>
    <div>
        <h1>Set {{this.numset+1}}:</h1><br>
        <ul>
            <li v-for="index in this.nbTopics" :key="index">
                <input placeholder="topic_name" :name="topicinputname" type="text" value="">
            </li>
        </ul>
        <br>
        <button type="button" @click="addTopic()">Add a topic</button>
        <br><br>
        <button type="button" @click="notAllDevices()" v-if="allDevicesButton">Not all devices of the project</button><br>
        <ul v-if="nbDevices>0"><!-- -->
            <li v-for="index in nbDevices" :key="index">
                <device :numset="numset" :numdevice="index-1"></device>
            </li>
            <br>
            <button type="button" @click="addDevice()">Add devices</button>
        </ul>
        <hr>
    </div>
</template>

<script>
import device from "./device.vue";

export default {
  name: "requestset",
  components: {
    device: device
  },
  props: ["numset"],
  data() {
    return {
      nbTopics: 1,
      nbDevices: 0,
      allDevicesButton: true
    };
  },
  methods: {
    addTopic() {
      this.nbTopics++;
    },
    notAllDevices() {
      this.allDevicesButton = false;
      this.nbDevices++;
    },
    addDevice() {
      this.nbDevices++;
    }
  },
  computed: {
    topicinputname() {
      return "requestSets[".concat(this.numset, "][topics][]");
    }
  },
  mounted() {}
};
</script>