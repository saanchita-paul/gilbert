export const STATES = [
    {text: 'NSW', value: 'New South Wales'},
    {text: 'VIC', value: 'Victoria'},
    {text: 'QLD', value: 'Queensland'},
    {text: 'SA', value: 'South Australia'},
    {text: 'NT', value: 'Northern Territory'},
    {text: 'TAS', value: 'Tasmania'},
    {text: 'ACT', value: 'Australian Capital Territory'},
    { text: 'WA', value: 'Western Australia' },
];

/**
 * finding state full name by its key
 *
 * @param {string} key
 *
 * @return {string | null}
 */
export const getStateValue = key => STATES.find(state => state.text === key)?.value;

/**
 * finding state key by its value
 *
 * @param value
 *
 * @return {*}
 */
export const getStateKey = value => STATES.find(state => state.value === value)?.text;
