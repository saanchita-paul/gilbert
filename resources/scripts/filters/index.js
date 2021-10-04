import Vue from 'vue';
import { replace, round } from "lodash-es";

Vue.filter('price_with_4_decimal', value => {
    if (!value) {
        return value;
    }
    let floatRegx = /(\d+.\d+)/g
    let newValue = replace(value, floatRegx, '#####')

    let num = value.match(floatRegx)
    let roundedPrice = num ?  round(num[0], 4) : '';
    return num ?  replace(newValue, '#####', roundedPrice) : value;
})
