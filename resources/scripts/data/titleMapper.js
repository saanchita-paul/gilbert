import { forEach } from "lodash-es";
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
