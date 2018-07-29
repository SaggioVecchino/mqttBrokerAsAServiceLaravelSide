<template>
    <div>
        <input placeholder="group_name" :name="group_name_input" value="" type="text">
        <button class="specifyDevice" type="button" @click="specifyDevice()" v-if="showButtonSpecifyDevice">Just some devices of the group</button>
        <input type="text" placeholder="device_name (empty for all)" :name="device_name_input" value="" v-if="showDeviceNameInput">
    </div>
</template>

<script>
    export default {
        name: "device",
        props: ["numset", "numdevice"],
        data() {
            return {
                showButtonSpecifyDevice: true
            };
        },
        methods: {
            specifyDevice() {
                this.showButtonSpecifyDevice = false;
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
        mounted() { }
    };
</script>