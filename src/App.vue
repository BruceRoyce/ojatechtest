<template>
  <teleport v-if="error.state" to="#fullscreen">
    <full-screen
        @ok="closeFullscreen"
        :title="error.title" :msg="error.msg" />
  </teleport>
  <oja-header />
  <NavBar v-if="isNavActive"
      @listAll="listAll"
      @registerNew = "registerNew"
      @deleteAll = "deleteAll"
      @search = "searchUsers"
  />
  
  <Registration :isModuleBusy="isModuleBusy" v-if="activeModule==='registration'" @error="apiError('registration')" />
  <list-all :isModuleBusy="isModuleBusy" v-if="activeModule==='listAll'"
            @error="apiError('listAll')"
            @emailUser="apiError"
            @deleteUser="deleteUser"/>
  
</template>

<script>
import { reactive, ref, provide } from "vue";
import OjaHeader from "./components/OjaHeader.vue";
import Registration from "./components/Registration.vue";
import NavBar from "./components/NavBar.vue";
import ListAll from "./components/ListAll.vue";
import FullScreen from "./components/FullScreen.vue";
import axios from "axios";

export default {
  components: {FullScreen, ListAll, NavBar, Registration, OjaHeader},
  setup () {
    const apiUrl = "./api/ojax.php";
    // users format is like the following example:
    // users = {
    // u1: {
    //   id: 11,
    //       name: "Bruce Royce",
    //       postcode: "SL6 1AN",
    //       email: "bruceroyce@yahoo.com",
    //       password: "70745089470987",
    //       mark: "name"
    // },
    // u2: {
    //   id: 12,
    //       name: "Mayank",
    //       postcode: "E16 2AB",
    //       email: "Mayank@oja.app",
    //       password: "tesTPass123",
    //       mark: ""
    // }
    // }
    var usersReactive = reactive({ users: {} });
    var error = reactive({
      state: false,
      title: "Sorry!",
      msg: "This is just a test.<br>That is not implemented in this exercise!"
    })
    var activeModule = ref("registration");
    var isModuleBusy = ref(false);
    var isNavActive = ref(true);
    
    provide("apiUrl", apiUrl);
    provide("usersReactive", usersReactive);
    provide("listAll", listAll);
    
    function closeFullscreen () {
      error.state = false;
    }
    function registerNew() {
      activeModule.value = "registration";
      console.log("Register a new Users");
    }
    function addUser(userData) {
      let postData = {
        operation: "add_user",
        data: userData
      };
      console.log(postData)
      ajax(postData, listAll);
    }
    provide("addUser", addUser);
    
    function listAll() {
      console.log("List All Users");
      activeModule.value = "listAll";
      let postData = {
        operation: "get_user",
        data: "all"
      };
      ajax(postData);
    }
    function deleteAll() {
      console.log("Delete All Users");
      let postData = {
        operation: "delete_all",
        data: "all"
      };
      ajax(postData, listAll);
    }
    function deleteUser(user_id) {
      console.log("Delete All Users");
      let postData = {
        operation: "delete_user",
        data: user_id
      };
      ajax(postData, listAll);
    }
    function searchUsers(searchTerm) {
      console.log("searching for: ", searchTerm);
      let postData = {
        operation: "find_user",
        data: searchTerm
      };
      ajax(postData);
    }
    function apiError(module) {
      switch (module) {
        case "registration":
          error.title = "Cannot Register!";
          error.msg = "Something just went wrong.<br>Please try again later.";
          break;
          
        case "listAll":
          error.title = "Oops!";
          error.msg = "There is an error communicating with the server.<br>Please try again later.";
          break;
        default:
          error.title = "This is an interview test!";
          error.msg = "This is just a test.<br>That is not implemented in this exercise!";
      }
      error.state = true;
    }
    
    
    
    function ajax(postData, callback = null, callBackArgs = null) {
      isModuleBusy.value = true;
      axios
          .post(apiUrl, postData)
          .then((response)=>{
            console.log("AJAX response ", response);
            if (typeof response.data === "object") {
              console.log("replacing new data");
              usersReactive.users = response.data;
            } else {
              usersReactive.users = {};
            }
          })
          .catch((err)=>{
            console.log(err);
            apiError("listAll");
          })
          .finally((response)=>{
            isModuleBusy.value = false;
            if (callback!== null) {
              if (callBackArgs!==null) {
                callback (...callBackArgs);
              } else {
                callback(response);
              }
            }
          })
    }
    
    return {
      error,
      isNavActive,
      activeModule,
      isModuleBusy,
      closeFullscreen,
      listAll,
      registerNew,
      searchUsers,
      deleteAll,
      deleteUser,
      apiError
    }
  }
}
</script>

<style>
  @import url('./assets/ojatestStyle.css');
</style>