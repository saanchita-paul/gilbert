import Vue from 'vue';
import {ValidationProvider, extend, ValidationObserver} from 'vee-validate';
import * as rules from 'vee-validate/dist/rules';
import {email, max, required} from "vee-validate/dist/rules";
import AuthService from "@scripts/services/AuthService";

Object.keys(rules).forEach(rule => {
    extend(rule, rules[rule]);
});

Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver );

extend('required', {
    ...rules.required,
    message: field => `${field} is required`,
});

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

extend('cv-phone', {
    message: field => `${field} should contain only number`,
    validate: value =>  {
        return new Promise(resolve => {
            let isValid = value.match('^[+]*[-\\s0-9]*$');
            resolve({ valid: !!isValid })
        })
    }
});

extend('date-range-check', {
    validate: (value) => {
        var startDate = value[0];
        var endDate = value[1];
        return (startDate instanceof Date) && (endDate instanceof Date);
    },
    message: '{_field_} should contain at least 2 valid datetimes.'
});

extend('custom-unique-email', {
    getMessage: field => `this email is already taken`,
    validate: value =>  {
        return new Promise(resolve => {
            AuthService.isUniqueEmail(value)
                .then( valid => {
                    resolve({ valid })
                })
        })
    }
});

extend('length', {
    ...length,
    message: 'Phone should contain 10 numbers',
})
