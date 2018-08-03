<template>
    <div>
        <!--v-if="isVisible"-->
        <modal  :modalId="idModal" class="fade" v-show="isVisible">
            <template slot="header" >
                {{title}}
            </template>
            <template slot="body">
                <button type="button" :class="'btn btn-'+type" @click="confirm" v-text="confirmButtonText"></button>
                <button type="button" class="btn btn-secondary" @click="deny" v-text="denyButtonText"></button>
            </template>
        </modal>
        <button type="button" :class="'btn btn-'+type+' btn-sm'" @click="showModal" v-text="btncontent"></button>
    </div>
</template>

<script>
import modal from "./modal.vue";

export default {
  name: "confirm",
  props: [
    "btncontent",
    "modalId",
    "title",
    "type",
    "confirmButtonText",
    "denyButtonText"
  ],
  data() {
    return {
      isVisible: false,
      idModal: this.modalId
    };
  },
  components: { modal: modal },
  methods: {
    deny() {
      this.$emit("deny");
      $("#".concat(`${this.modalId}`)).modal("hide");
    },
    confirm() {
      this.$emit("confirm");
    },
    showModal() {
      this.isVisible = true;
      $("#".concat(`${this.modalId}`)).modal({ show: true });
    },
    hideModal() {
      this.isVisible = false;
    }
  },
  mounted() {
    var f = this.hideModal;
    $("#".concat(`${this.modalId}`)).on("hidden.bs.modal", function(e) {
      f();
    });
  }
};
</script>
