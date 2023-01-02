import { forEach } from "lodash-es";
const titles = {
    Mr     : 'Mr.',
    Mrs    : 'Mrs.',
    Ms     : 'Ms.',
    Miss   : 'Miss',
    Dr     : 'Dr.',
}
const titlesMapperForDropdown = [];
const titlesMapperForDropdownCb = [];
forEach(titles , (value,key)=>{
    titlesMapperForDropdown.push({text: value, value: key});
    titlesMapperForDropdownCb.push({text: value, value: key.toLowerCase()});
})
export { titlesMapperForDropdown, titles, titlesMapperForDropdownCb};
