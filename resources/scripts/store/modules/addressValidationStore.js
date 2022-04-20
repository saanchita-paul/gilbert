export default {
    state: {
        isInvalidAddress: false
    },
    getters: {
        isInvalidAddress: state => state.isInvalidAddress
    },
    mutations: {
        setInvalidAddress(state, type) {
            state.isInvalidAddress = type;
        }
    }
};
