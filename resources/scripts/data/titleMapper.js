import {forEach} from "lodash-es";
const titles = {
    Mr     : 'Mr.',
    Mrs    : 'Mrs.',
    Ms     : 'Ms.',
    Miss   : 'Miss',
    Dr     : 'Dr.',
}
const titlesMapperForDropdown = [];
forEach(titles , (value,key)=>{
    titlesMapperForDropdown.push({text: value, value: key});
})
export { titlesMapperForDropdown, titles };

// export const titleMapper = titleMapper;


// const titlesMapperForDropdown = [
//     {
//         text: 'Mr.',
//         value: 'Mr',
//     },
//     {
//         text: 'Mrs.',
//         value: 'Mrs',
//     },
//     {
//         text: 'Ms.',
//         value: 'Ms',
//     },
//     {
//         text: 'Miss',
//         value: 'Miss',
//     },
//     {
//         text: 'Dr.',
//         value: 'Dr',
//     },
// ];


// exports.titlesMapperForDropdown = titlesMapperForDropdown;
// export  titlesMapperForDropdown;
// module.exports = titlesMapperForDropdown;