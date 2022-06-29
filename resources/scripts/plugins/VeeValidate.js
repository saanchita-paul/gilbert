import Vue from 'vue';
import {ValidationProvider, extend, ValidationObserver} from 'vee-validate';
import * as rules from 'vee-validate/dist/rules';
import {email, max, required} from "vee-validate/dist/rules";
import AuthService from "@scripts/services/AuthService";
import GBGService from "@scripts/services/GBGService";
import EAPlanService from "@scripts/services/ea/EAPlanService";
import dayJs from "dayjs";
import AgencyService from '@scripts/services/crm/AgencyService';
import dayjs from "dayjs";
import {now} from "lodash-es";

Object.keys(rules).forEach(rule => {
    extend(rule, rules[rule]);
});

Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver );

const medicareRules = function(value) {
    let pattern = /^[0-9]{2}\/[0-9]{2}$/;
    if(!pattern.test(value)){
       return false;
    }
    let dateMonth =  value.split("/");
    return dateMonth[0] > 12 ? false : true;
}

const mediExpireDate = function(value) {
    if (!value || value?.length < 0) {
        return true;
    }
    let spilitedData = value.split('/');
    let fullMonthYear = spilitedData[0] + '/' + '20' + spilitedData[1];
    let fullDateMonthYear =   dayjs().daysInMonth() + '/' + fullMonthYear;
    return !(dayjs(fullDateMonthYear,'DD/MM/YYYY').isBefore());
}

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
extend("password", {
    message: field =>
        `${field} must be at least eight characters in length. Must contain both uppercase and lowercase characters (e.g., a-z and A-Z), at least one number (e.g., 0-9) and a special character e.g., ! @`,
    validate: value => {
        return new Promise(resolve => {
            let isValid = value.match(
                "^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[!\"#$%&'()*+,-./:;<=>?@[\\]^_`{|}~])[A-Za-z\\d!\"#$%&'()*+,-./:;<=>?@[\\]^_`{|}~]{8,}$"
            );
            resolve({ valid: !!isValid });
        });
    }
});

extend("cv-phone", {
    message: field => `${field} should contain only numbers`,
    validate: value =>  {
        return new Promise(resolve => {
            let isValid = value.match('^[+]*[-\\s0-9 ]*$');
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

extend('unique-user-email', {
    message: field => `this email is already taken`,
    validate: value =>  {
        return new Promise(resolve => {
            AuthService.isUniqueEmail(value)
                .then( valid => {
                    resolve({ valid })
                })
        })
    }
});

extend('email-exist', {
    message: field => `Email not found`,
    validate: value =>  {
        return new Promise(resolve => {
            AuthService.isUniqueEmail(value)
                .then( valid => {
                   valid = !valid;
                    resolve({ valid })
                })
        })
    }
});

extend('email-exist-update', {
    message: field => `Email not found`,
    params: ['userId'],
    validate: async (value, {userId}) =>  {
        return new Promise(resolve => {
            AuthService.checkUniqueEmailUpdate(value , userId)
                .then( valid => {
                   valid = !valid;
                    resolve({ valid })
                })
        })
    }
});

extend('not-holiday', {
    message: field => `Date must not be a holiday`,
    params: ['target'],
    validate: async (value, {target}) =>  {
        const [day, month, year] = value.split('/');
        value = year + '-' + month + '-' + day;
        return !(await EAPlanService.checkIfDateIsHoliday({state: target, date: value}))
    }
});

extend('unique-email-update', {
    message: field => `Email already taken!`,
    params: ['target'],
    validate: async (value, {target}) =>  {
        return !(await AgencyService.emailUpdateValidationRule(value, target))
    }
});


extend('adult', {
    message: field => `must be 18 years old`,
    validate: value =>  {
        const timeDiff = dayJs().diff(dayJs(value, 'DD/MM/YYYY'),'year');
        return timeDiff>=18?true:false;
    }
});

extend('valid-date', {
    message: field => `DD/MM/YYYY is valid format`,
    validate(value) {
        console.log("dayJs(value, 'DD/MM/YYYY').isValid()", dayJs(value, 'DD/MM/YYYY').isValid())
        return dayJs(value, 'DD/MM/YYYY').isValid();
    }
});

extend('length', {
    ...length,
    message: 'Phone should contain 10 numbers',
})

extend('medicare-date', {
    message: field => `MM/YY is valid format`,
    validate(value) {
        let pattern = /^[0-9]{2}\/[0-9]{2}$/;
        if(!pattern.test(value)){
           return false;
        }
        let dateMonth =  value.split("/");
        return dateMonth[0] > 12 ? false : true;
    }
})

extend('medi-expire', {
    message: field => `Expired card. Please enter valid date`,
    validate: mediExpireDate
})

extend('passport-expire', {
    message: field => `Invalid expiry date`,
    validate(value) {
        let pattern = /^[0-9]{2}\/[0-9]{2}$/;
        // pattern.test(value);
        // let spilitedData = value.split('/');
        // let fullMonthYear = spilitedData[0] + '/' + '20' + spilitedData[1];
        // let fullDateMonthYear =   dayjs().daysInMonth() + '/' + fullMonthYear;
        return pattern.test(value);
    }
})

extend('required-medicare', {
    ...rules.required,
    message: field => `Medicare Card Number is required`,
});

extend('required-driving', {
    ...rules.required,
    message: field => `Driver’s License is required`,
});

extend('required-passport', {
    ...rules.required,
    message: field => `Passport Number is required`,
});

extend('required-special-number', {
    ...rules.required,
    message: field => `Special Number is required`,
});

extend('required-issuing-country', {
    ...rules.required,
    message: field => `Issuing Country is required`,
});

extend('gbg-email-validate', {
    message: field => `Cannot verify email, double check`,

    validate: async (value) =>  {
        return new Promise(resolve => {
            GBGService.validateEmail(value)
                .then( valid => {
                   valid = !valid;
                    resolve({ valid })
                })
        })
    }
});

export { medicareRules , mediExpireDate }
