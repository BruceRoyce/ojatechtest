<template>
  <div class="ojaPassChecks scaleUp" :class="state ? '_done': '_pending'">
    <div class="_icon"></div>
    <div class="_msg">{{ msg }}</div>
  </div>
</template>

<script>
export default {
  name: "CredentialValidityMarker",
  props: {
    msg: {
      type: String,
      required: true,
      default: "Default Message",
    },
    state: {
      type: Boolean,
      required: true,
      default: false
    },
  },
}
</script>

<style scoped>

</style>