export default {
    /**
     *
     * @param Vue
     * @param options
     */
    install(Vue, options) {
        Vue.directive("roles", directive(options.hasUserRoles));
    }
};

/**
 *
 * @param hasUserRoles
 * @return {{inserted: inserted}}
 */
const directive = hasUserRoles => ({
    inserted: (el, binding) => {
        if (!hasUserRoles(binding.value.split(","))) {
            el.remove();
        }
    }
});
