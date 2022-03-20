<template>
  <div class="ojaInnerBox _r ojaAlignSelfTop" style="flex-grow: 1">
    <Spinner :is-active="isModuleBusy" />
    <div v-if="Object.keys(usersReactive.users).length" v-for="(user, key, index) in listUsers" :key="user.id"
         class="ojaListGrid">
      <div class="_gird_lineNumber">
        <div class="text _encircled" v-html="isNaN(index) ? 1 : parseInt(index) + 1"></div>
      </div><!-- grid line number -->
      <div class="_gird_info">
        <div class="_btns">
          <div class="btn _thin _wAuto" @click="delUser(user.id)">delete</div>
          <div class="btn _thin _wAuto" @click="emailUser(user.id)">Contact</div>
        </div>
        <div class="_textual">
        <div class="infoLine">
          <span class="icons _lined">email</span>
          <span class="text _bold" :class="{'marked': user.mark=== 'email'}" v-html="user.email"></span>
        </div>
        <div class="infoLine">
          <span class="icons _lined">password</span>
          <span class="text" v-html="user.password"></span>
        </div>
        <div class="infoLine">
          <span class="icons _lined">fingerprint</span>
          <span class="text" v-html="user.id"></span>
        </div>
        <div class="infoLine">
          <span class="icons _lined">badge</span>
          <span class="text" :class="{'marked': user.mark=== 'name'}" v-html="user.name"></span>
        </div>
        <div class="infoLine">
          <span class="icons _lined">place</span>
          <span class="text" :class="{'marked': user.mark=== 'postcode'}" v-html="user.postcode"></span>
        </div>
      </div>
    </div><!-- grin info -->
      <div class="_grid_more"></div><!-- grid more -->
    </div>
    <div v-else class="flex _wFull _hFull _on_center _on_middle">
      <div class="text _bold">Nothing here yet! 🤔</div>
    </div>
  </div><!-- ojaInnerBox -->
  
  <div style="border: 1px solid silver; text-align: center; width: 70%" >
    <p style="font-size: 8pt">For development tests only</p>
    <button @click="emitError">Generate Api Error</button>
  </div>
  
  
</template>

<script>
import Spinner from "./Spinner.vue";
export default {
  name: "ListAll",
  components: {Spinner},
  props: ["isModuleBusy"],
  inject: ["usersReactive"],
  emits: ["error", "deleteUser", "emailUser"],
  methods: {
    emitError () {
      this.$emit("error");
    },
    delUser(user_id) {this.$emit("deleteUser", user_id)},
    emailUser(user_id) {this.$emit("emailUser", user_id)}
  },
  computed: {
    listUsers() { return this.usersReactive.users}
  }
}
</script>

<style scoped>
  .marked {
    background-color: yellowgreen !important;
  }
</style>