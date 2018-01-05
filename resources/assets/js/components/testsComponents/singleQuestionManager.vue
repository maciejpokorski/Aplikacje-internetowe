<style scoped>
.Test-list {
  margin: 0;
  padding: 0;
}
.Test-list--element {
  display: flex;
  flex-wrap: no-wrap;
  flex-direction: row;
  justify-content: space-between;
  padding: 5px 10px;
}
.Test-list--element-btn {
  background-color: #e75151;
  color: #fff;
}
.Test-list--editable {
  padding-right: 5px;
}
</style>

<template>
    <simple-panel-wrapper title="Go back to questions list"  href="/questions" size="col-md-12" offset="col-md-offset-0">
      <div>
        <div>
        Name: <editable class="Test" :value.sync='question.name' @change='update(question)'></editable>
        </div>
        <div>
        Description: <editable class="Test" :value.sync='question.description' @change='update(question)'></editable>
        </div>
        <br>
      </div>
      <slot name="question-answers"></slot>
    </simple-panel-wrapper>
  
</template>

<script>
import Editable from "vue-xeditable/src/Editable.vue";
export default {
  data() {
    return {
      question: {}
    };
  },

  props: ["question_id"],

  mounted() {
    this.getQuestion();
  },

  components: {
    editable: Editable
  },

  methods: {
    getQuestion() {
      axios.get("/api/questions/" + this.question_id).then(response => {
        this.question = response.data;
      });
    },
    update(question) {
      axios({
        method: "put",
        url: "/api/questions/" + question.id,
        data: {
          name: question.name,
          description: question.description
        }
      }).then(function(response) {});
    }
  }
};
</script>
