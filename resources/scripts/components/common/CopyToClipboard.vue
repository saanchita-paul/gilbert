<template>
    <div class="idDesign" @click="copyToClipboard">
        {{ data }}
        <div>
            <transition name="fade">
                <small
                    class="snackbarDesign"
                    v-if="showSnackbar"
                    transition="slide-x-transition"
                >
                    <v-icon color="green" class="pt-0" size="15">mdi-check</v-icon>
                    {{ title }} copied to clipboard!
                </small>
            </transition>
        </div>
    </div>
</template>

<script>
export default {
    name: "CopyToClipboard",
    props: {
        title: {
            default: "Text"
        },
        data: {
            default: null
        }
    },
    data() {
        return {
            showSnackbar: false
        };
    },
    methods: {
        copyToClipboard() {
            navigator.clipboard.writeText(this.applicationId);
            this.showSnackbar = true;
            setTimeout(() => {
                this.showSnackbar = false;
            }, 3000);
        }
    }
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
    font-size: 1.3em;
    font-weight: bold;
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
