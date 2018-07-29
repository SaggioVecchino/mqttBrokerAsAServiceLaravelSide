<template>
    <div>
        <modal :modalId="modalid" v-show="isVisible">
            <template slot="header">
                Create/Add a new {{allowString}} on {{type}} for the group {{newTopicForm.group_name}}
            </template>
            <template slot="body">
                <div class="card">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action='/we-have-to-change-elater' accept-charset="UTF-8"
                                      @submit.prevent="newTopicFormOnSubmit"
                                      @keydown="newTopicForm.errors.clear($event.target.name)">

                                    <div class="form-group">

                                        <label :for="topicNameInputId">Create a new topic name</label>

                                        <input :id="topicNameInputId" v-model="newTopicForm.topic_name" type="text"
                                               placeholder="Topic name" class="form-control" name="topic_name">

                                        <span class="help is-danger invalid-feedback" style="display: inline"
                                              v-if="newTopicForm.errors.has('topic_name')"
                                              v-text="newTopicForm.errors.get('topic_name')"></span>


                                    </div>
                                    <input type="submit" :disabled="newTopicForm.errors.any()" class="btn btn-primary"
                                           value="submit">
                                </form>


                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action='/we-have-to-change-elater' accept-charset="UTF-8"
                                      @submit.prevent="selectTopicFormOnSubmit"
                                      @keydown="newTopicForm.errors.clear($event.target.name)">

                                    <div class="form-group">

                                        <label :for="topicNameSelectId">Choose a topic</label>
                                        <select class="custom-select" v-model="selectTopicForm.topic_name">
                                            <option value="">Select a topic</option>
                                            <option v-for="topic in topics" v-bind:value="topic" :key="topic">
                                                {{topic}}
                                            </option>
                                        </select>

                                        <span class="help is-danger invalid-feedback" style="display: inline"
                                              v-if="selectTopicForm.errors.has('topic_name')"
                                              v-text="selectTopicForm.errors.get('topic_name')"></span>

                                    </div>
                                    <!--:disabled="selectTopicForm.errors.any()"-->
                                    <input type="submit" class="btn btn-primary"
                                           value="submit">
                                </form>
                            </div>
                        </div>

                </div>
            </template>
        </modal>

        <button type="button" class="btn btn-primary" @click="showModal">+add</button>

    </div>

</template>

<script>
import modal from "./modal.vue";
import Form from "../forms/form";

export default {
  name: "permission",
  props: ["type", "allow", "group_id", "modalid"],
  data() {
    return {
      isVisible: false,
      topics: {},
      idModal: this.modalid,
      newTopicForm: new Form({
        group_name: "",
        topic_name: "",
        type: this.type,
        allow: this.allow,
        group_id: this.group_id
      }),
      selectTopicForm: new Form({
        group_name: "",
        type: this.type,
        topic_name: "",
        allow: this.allow,
        group_id: this.group_id
      })
    };
  },
  components: { modal: modal },
  methods: {
    showModal() {
      var url = `http://localhost:8000/device_groups_topics/create`;
      axios
        .get(url, {
          params: {
            group_id: this.group_id,
            type: this.type,
            allow: this.allow
          }
        })
        .then(response => {
          this.topics = response.data.topics_names;
          this.newTopicForm.group_name = response.data.group_name;
          this.selectTopicForm.group_name = response.data.group_name;
          this.isVisible = true;

          $("#".concat(`${this.idModal}`)).modal({ show: true });
        })
        .catch(error => {
          this.onFail(error.response.data.errors); //without .errors same result
          alert(error.response.data);
        });
    },
    hideModal() {
      this.isVisible = false;
    },
    topicNameInputId() {
      return `topicNameInputId`.concat(
        this.newTopicForm.type,
        this.newTopicForm.allow
      );
    },
    topicNameSelectId() {
      return `topicNameSelectId`.concat(
        this.newTopicForm.type,
        this.newTopicForm.allow
      );
    },
    newTopicFormOnSubmit() {
      this.newTopicForm
        .post(`http://localhost:8000/device_groups_topics`)
        .then(function(response) {
          window.location.href = response;
        });
    },
    selectTopicFormOnSubmit() {
      this.selectTopicForm
        .post(`http://localhost:8000/device_groups_topics`)
        .then(function(response) {
          // window.location.href = 'http://localhost:8000/projects'
        });
    }
  },
  mounted() {
    var that = this;
    $("#".concat(`${this.modalid}`)).on("hidden.bs.modal", function(e) {
      that.hideModal();
      that.selectTopicForm.reset();
      that.newTopicForm.reset();
      that.selectTopicForm.group_id = that.group_id;
      that.selectTopicForm.type = that.type;
      that.selectTopicForm.allow = that.allow;
      that.newTopicForm.group_id = that.group_id;
      that.newTopicForm.type = that.type;
      that.newTopicForm.allow = that.allow;
    });
  },
  computed: {
    allowString() {
      return this.allow === "1" ? "permission" : "prohibition";
    }
  }
};
</script>
