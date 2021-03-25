export default {
    /**
     *
     * @param data
     * @returns {*[]|*}
     */
    map: data => {
        if (Array.isArray(data)) {
            return data.map(item => ({
                text: item.state,
                value: item.percentage
            }));
        }
        return [];
    }
}
