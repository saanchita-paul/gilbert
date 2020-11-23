export default {

    /**
     *  energy usage analytics mapper
     *  @param {Object} data
     * @returns {Object}
     */
    energyUsageDetail: (data) => {
        const {
            total_high_energy_usage : high = 0,
            total_medium_energy_usage : medium = 0,
            total_low_energy_usage : low = 0,
            total_unsure_energy_usage : unsure = 0
        } = data || {};

        const total = high + medium + low + unsure;
        const highPercentage = Math.round((high / total) * 100);
        const mediumPercentage = Math.round((medium / total) * 100);
        const lowPercentage = Math.round((low / total) * 100);
        const unsurePercentage = 100 - highPercentage - mediumPercentage - lowPercentage;

        return {
            colors: ['red', 'orange', 'green', 'black'],
            data: [highPercentage, mediumPercentage, lowPercentage, unsurePercentage],
            labels: ['High', 'Medium', 'Low', 'Not sure']
        };
    },

    /**
     *  property profile analytics mapper
     *  @param {Object} data
     * @returns {Object}
     */
    propertyProfileDetail: (data) => {
        const {
            total_rent : rent = 0,
            total_own : own = 0
        } = data || {};

        const total = rent + own;
        const rentPercentage = Math.round((rent / total) * 100);
        const ownPercentage = 100 - rentPercentage;

        return {
            colors: ['red', 'orange'],
            data: [ownPercentage, rentPercentage],
            labels: ['Own', 'Rent']
        }
    },

    /**
     *  household profile analytics mapper
     *  @param {Object} data
     * @returns {Object}
     */
    householdProfileDetail: (data) => {
        const {
            total_one_household : one = 0,
            total_two_household : two = 0,
            total_three_household : three = 0,
            total_four_more_household : four = 0
        } = data || {};

        const total = one + two + three + four;
        const onePercentage = Math.round((one / total) * 100);
        const twoPercentage = Math.round((two / total) * 100);
        const threePercentage = Math.round((three / total) * 100);
        const fourPercentage = 100 - onePercentage - twoPercentage - threePercentage;

        return {
            colors: ['red', 'orange', 'green', 'black'],
            data: [onePercentage, twoPercentage, threePercentage, fourPercentage],
            labels: ['1-2', '2-3', '3-4', '4+']
        }
    }
};
