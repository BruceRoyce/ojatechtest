<template>
  <div class="flex _hor _gaped10 _on_start _on_middle _mt30"
    style="align-self: flex-start;">
    <div class="btn _wAuto" @click="listAll">List All Users</div>
    <div class="btn _wAuto" @click="registerNew">Register a new User</div>
    <div class="btn _wAuto" @click="deleteAll">Delete All Users</div>
    <div class="flex _wFull _noWrap _on_center" style="margin: -0px">
      <input type="text" name="search" id="search" class="searchBar"
             placeholder="Search name, email or postcode"
             v-model="searchTerm">
      <div class="searchBar_post" @click="search">search</div>
    </div>
  </div>
  
</template>

<script>
export default {
  name: "NavBar",
  data() {
    return {
      searchTerm: ""
    }
  },
  methods: {
    listAll() {this.$emit("listAll", true);},
    registerNew() {this.$emit("registerNew", true);},
    deleteAll() {this.$emit("deleteAll", true);},
    search() {this.$emit("search", this.searchTerm)}
  }
}
</script>

<style scoped>

</style>