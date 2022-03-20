<template>
  <div class="fullscreen" ref="fullscreen">
    <div class="ojaInnerBox _r scaleUpSquare">
      <div class="flex _ver _on_middle _on_center">
        <div class="text _bold" style="font-size: 120%" v-html="title"></div>
        <div class="text" style="text-align: center" v-html="msg"></div>
        <div class="btn" style="margin-top: 30px" @click="modalConfirmed">OK!</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "FullScreen",
  props: {
    title: String,
    msg: String
  },
  methods: {
    modalConfirmed: function () {
      this.$refs.fullscreen.classList.add("inActive");
      this.$emit("ok", true);
    }
  }
}
</script>

<style scoped>
  .fullscreen {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 500;
    background-color: rgba(255, 255, 255, 0.9);
    display: flex;
    justify-content: center;
    justify-items: center;
    align-content: center;
  }
  .inActive {
    display: none !important;
  }
</style>