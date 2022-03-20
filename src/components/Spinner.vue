<template>
  <div class="overAll" :class="{ '_active': isActive }">
    <div class="_isBusy"></div>
  </div>
</template>

<script>
export default {
  name: "Spinner",
  props: {
    isActive: {
      type: Boolean,
      required: true,
      default: false
    }
  }
}
</script>

<style scoped>

</style>