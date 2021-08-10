export default {
    /**
     * install plugin
     * @param Vue
     * @param options
     */
    install(Vue, options) {
        Vue.directive("permissions", directive(options.hasUserPermissions));
    }
};

/**
 *
 * @returns {{inserted: inserted}}
 * @param hasUserPermissions
 */
const directive = hasUserPermissions => ({
    inserted: (el, binding) => {
        if (!hasUserPermissions(binding.value.split(","))) {
            el.remove();
        }
    }
});
