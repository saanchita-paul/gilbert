<template>
  <div>
    <small class="idDesign" @click="copyToClipboard"
      >App ID: <b>{{ applicationId }}</b></small
    >
    <div>
      <transition name="fade">
        <small
          class="snackbarDesign"
          v-if="showSnackbar"
          transition="slide-x-transition"
        >
          <v-icon color="green" class="pt-0" size="15">mdi-check</v-icon> App ID
          copied to clipboard!</small
        >
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  name: "IdCopyToClipboard",
  props: {
    applicationId: {
      default: 1000,
    },
  },
  data() {
    return {
      showSnackbar: false,
    };
  },
  methods: {
    copyToClipboard() {
        const el = document.createElement("textarea");
        el.value = this.applicationId;
        document.body.appendChild(el);
        el.select();
        document.execCommand("copy");
        document.body.removeChild(el);
        this.showSnackbar = true;
        setTimeout(() => {
            this.showSnackbar = false;
        }, 2000);
    },
  },
};
</script>

<style lang="scss" scoped>
.snackbarDesign {
  color: green;
  font-weight: normal;
  font-size: 14px;
  padding: 5px 10px;
  position: absolute;
  background: white;
  border-radius: 6px;
  box-shadow: 0px 9px 24px 6px rgba(0, 0, 0, 0.22);
  -webkit-box-shadow: 0px 9px 24px 6px rgba(0, 0, 0, 0.22);
  -moz-box-shadow: 0px 9px 24px 6px rgba(0, 0, 0, 0.22);
  &:hover {
    cursor: pointer;
  }
}
.idDesign {
  font-weight: normal;
  font-size: 14px;
  &:hover {
    cursor: pointer;
  }
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
