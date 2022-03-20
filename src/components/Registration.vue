<template>
  <!-- <OjaHeader /> -->
  <div class="ojaInnerBox _r">
    <Spinner :isActive="isModuleBusy" />
    <form id="registration">
      <div class="flex _hor _wFull">
        <div class="ojaInputRow">
          <label for="email" class="lbl _required">Enter Your email</label>
          <input id="email" name="email" ref="email" class="ojaInput" type="email"
                 :required="email.isRequired"
                 autocomplete="email"
                 @blur="checkEmailValidity()"
                 v-model="email.value">
          <div class="ojaPassCheckWrapper">
            <CredentialValidityMarker
                v-if="emailValidityCheck === false"
                msg="Check your email validity"
                :state="false" />
            <CredentialValidityMarker
                v-else-if="emailValidityCheck === true && email.value !== ''"
                msg="Looks Okay!"
                :state="true" />
            <div v-else style="height: 24px;"></div>
          </div><!--wrapper-->
        </div> <!-- email input -->
        <div class="ojaInputRow" style="margin-top: 16px;">
          <label for="password" class="lbl _required">Choose a password</label>
          <!-- also could have used a watcher to watch the password input value changes, but opted for keyup that
          does the same, for the sake of readability -->
          <div class="flex _hor _noWrap" ref="password">
            <input id="password" name="password" class="ojaInput"
                   autocomplete="new-password"
                   :type="passwordVisibility.inputType[passwordVisibility.state]"
                   @keyup="checkPasswordValidity()"
                   :required="password.isRequired"
                   v-model="password.value">
            <div class="ojaInput _post _isBtn" @click="togglePasswordVisibility">
              <div class="icons _lined">
                {{ passwordVisibility.state }}
              </div>
            </div>
          </div>
          <div class="ojaPassCheckWrapper">
            <CredentialValidityMarker
                msg="Include a lowercase"
                :state="!!(passwordValidityCheck.isValid || passwordValidityCheck.hasLowerCase)" />
            <CredentialValidityMarker
                msg="Include an Uppercase"
                :state="!!(passwordValidityCheck.isValid || passwordValidityCheck.hasUpperCase)" />
            <CredentialValidityMarker
                msg="Include a digit"
                :state="!!(passwordValidityCheck.isValid || passwordValidityCheck.hasDigit)" />
            <CredentialValidityMarker
                msg="Min. 8 characters"
                :state="!!(passwordValidityCheck.isValid || passwordValidityCheck.isLong)" />
          </div><!-- password checks wrapper -->
        </div> <!-- password input-->
        
        <div class="ojaInputRow">
          <div class="flex _hor _wFull _justBetween _gaped5">
            
            <div class="wHalf">
              <label for="fullName">Your name</label>
              <input type="text" name="fullName" id="fullName"
                     autocomplete="name" v-model="name.value" class="ojaInput">
            </div>
            <div class="wHalf">
              <label for="postcode">Postcode</label>
              <input type="text" name="postcode" id="postcode"
                     autocomplete="postal-code" v-model="postcode.value" class="ojaInput">
            </div>
          
          </div>
        </div><!-- name and postcode -->
      </div>
      
      <div class="ojaInputRow_btn">
        <div class="btn" @click="submitForm">Create an account</div>
      </div>
    </form>
  </div><!-- innerBox -->
  
  <div style="border: 1px solid silver; text-align: center; width: 70%" >
    <p style="font-size: 8pt">For development tests only</p>
    <button @click="emitError">Test Registration Error</button>
  </div>
  
</template>

<script>
import {reactive, toRefs, ref, inject } from "vue";
import CredentialValidityMarker from "./CredentialValidityMarker.vue"
import OjaHeader from "./OjaHeader.vue";
import Spinner from "./Spinner.vue";
import axios from "axios";

export default {
  name: "Registration",
  components: {Spinner, OjaHeader, CredentialValidityMarker},
  props: ["isModuleBusy"],
  setup(props, context) {
    // initializing form values
    var formData = reactive({
      email: {
        value: "",
        isRequired: true
      },
      password: {
        value: "",
        isRequired: true
      },
      name: {
        value: "",
        isRequired: false
      },
      postcode: {
        value: "",
        isRequired: false
      }
    });
    // initializing password check states
    var passwordValidityCheck = reactive({
      hasLowerCase: false,
      hasUpperCase: false,
      hasDigit: false,
      isLong: false,
      isValid: false
    });
    var passwordVisibility = reactive({
      state: "visibility_off",
      inputType: {
        visibility_off: "password",
        visibility: "text"
      },
      icon: {
        visible: "visibility_off",
        hidden: "visibility"
      }
    })
    // ref is used because emailValidityCheck has only a single member
    var emailValidityCheck = ref(null);
    // var isModuleBusy = ref(props.isModuleBusy);
    
    // internal function that actually runs the validity check
    function validatePassword (password) {
      // the regex to check the password against
      // should contain a-z , A-Z , 0-9 with the length of at least 8 (?=.{8,}
      let returnable = [];
      let passwordPatterns = {
        isValid: "^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})",
        hasLowerCase: /([a-z])/g,
        hasUpperCase: /([A-Z])/g,
        hasDigit: /([0-9])/g,
        isLong: /.{8,}/g
      };
      // we could just check the final state, but the use will be puzzled as why his password isn't valid
      // instead (for user convenience) this runs through all the patterns, one by one
      // and guides the user on what he's done right and what's left to do
      Object.keys(passwordPatterns).forEach((pattern)=>{
        let patternCheckResult = (new RegExp(passwordPatterns[pattern])).test(password);
        if (patternCheckResult) {
          if (pattern === "valid") return ["valid"];
          returnable.push(pattern);
        }
      })
      return returnable;
    }
    
    // the method that is being called when the password validity is being checked
    function checkPasswordValidity (password = formData.password.value) {
      const validationState = validatePassword(password);
      Object.keys(passwordValidityCheck).forEach((test)=>{
        passwordValidityCheck[test] = validationState.find((item)=> item===test);
      });
    }
    
    //internal function to use html 5 feature that checks the email validity
    function validateEmail (email) {
      let element = document.createElement("input");
      element.setAttribute("type", "email");
      element.setAttribute("required", formData.email.isRequired ? "true" : "false");
      element.setAttribute("value", email);
      return element.checkValidity();
    }
    
    // this method is called to check email validity
    function checkEmailValidity (email=formData.email.value) {
      const validationState = validateEmail(email);
      emailValidityCheck.value = validationState;
      console.log("Email validity = " + validationState);
      return validationState;
    }
    
    // Method to toogle password visibility
    function togglePasswordVisibility () {
      if (passwordVisibility.state === passwordVisibility.icon.visible) {
        passwordVisibility.state = passwordVisibility.icon.hidden;
      } else {
        passwordVisibility.state = passwordVisibility.icon.visible;
      }
    }
    
    function checkFormValidity () {
      let returnable = true;
      if (!passwordValidityCheck.isValid) {
        returnable = false;
        const element = ref(password).value;
        shakeWrong(element, true);
      }
      if (!emailValidityCheck.value) {
        returnable = false;
        const element = ref(email).value;
        shakeWrong(element, true);
      }
      return returnable;
      
      function shakeWrong(element, addBad = false) {
        if (addBad) element.classList.add("_bad");
        element.classList.add("wrongAnswer");
        setTimeout(()=>{
          if (addBad) element.classList.remove("_bad");
          element.classList.remove("wrongAnswer");
        }, 700);
      }
    }
    
    const addUser = inject("addUser");
    function submitForm() {
      if (checkFormValidity()) {
        let postDataObj = {};
        for (const formInput in formData) {
          postDataObj[formInput] = formData[formInput].value;
        }
        addUser(postDataObj);
      }
    }
    
    function emitError () {
      context.emit("error");
    }
    
    // // computing password checks will get rid of the boolean warning and
    // // could be slightly faster in a large application.
    // // but since this is a small application, I opted with inline statements
    // // commented section below is an example of one check computed for demo purposes
    // // 👇
    // const passHasLowercase =  computed(()=>{
    //       return passwordValidityCheck.hasLowerCase || passwordValidityCheck.isValid;
    //     })
    
    
    // composition returns to the template
    return {
      ...toRefs(formData),
      passwordValidityCheck,
      emailValidityCheck,
      passwordVisibility,
      checkPasswordValidity,
      checkEmailValidity,
      togglePasswordVisibility,
      submitForm,
      emitError
    }
  },
  emits: ["error"]
}
</script>



<style>

</style>
