import Vue from 'vue';
import {
    ValidationProvider,
    ValidationObserver,
    extend, setInteractionMode
} from 'vee-validate';
import {email, max, required} from "vee-validate/dist/rules";


setInteractionMode('eager')

extend('required', {
    ...required,
    message: '{_field_} can not be empty',
})

extend('max', {
    ...max,
    message: '{_field_} may not be greater than {length} characters',
})

extend('email', {
    ...email,
    message: 'Email must be valid',
})
// Add a rule.
extend('secret', {
    validate: value => value === 'example',
    message: 'This is not the magic word'
});

// Register it globally
Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);
