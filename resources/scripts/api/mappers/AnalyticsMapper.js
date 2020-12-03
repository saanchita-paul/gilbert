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
        const highPercentage =  total === 0 ? 0 : Math.round((high / total) * 100);
        const mediumPercentage = total === 0 ? 0 : Math.round((medium / total) * 100);
        const lowPercentage = total === 0 ? 0 : Math.round((low / total) * 100);
        const unsurePercentage = total === 0 ? 0 : 100 - highPercentage - mediumPercentage - lowPercentage;
        const noAnswerPercentage = highPercentage === 0 && mediumPercentage === 0 && lowPercentage === 0 && unsurePercentage === 0
            ? 100
            : 0;

        // return noAnswerPercentage === 0
        //     ? {
        //         colors: ['red', 'orange', 'green', 'black'],
        //         data: [highPercentage, mediumPercentage, lowPercentage, unsurePercentage],
        //         labels: ['High', 'Medium', 'Low', 'Not sure']
        //     } : {
        //         colors: ['red', 'orange', 'green', 'black', 'grey'],
        //         data: [highPercentage, mediumPercentage, lowPercentage, unsurePercentage, noAnswerPercentage],
        //         labels: ['High', 'Medium', 'Low', 'Not sure', 'No Answer']
        //     };

        return {
                colors: ['red', 'orange', 'green', 'black', 'grey'],
                data: [highPercentage, mediumPercentage, lowPercentage, unsurePercentage, noAnswerPercentage],
                labels: ['High', 'Medium', 'Low', 'Not sure', 'No Answer']
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
        const rentPercentage = total === 0 ? 0 : Math.round((rent / total) * 100);
        const ownPercentage = total === 0 ? 0 : 100 - rentPercentage;
        const noAnswerPercentage = rentPercentage === 0 && ownPercentage === 0
            ? 100
            : 0;

        return {
                colors: ['red', 'orange', 'grey'],
                data: [ownPercentage, rentPercentage, noAnswerPercentage],
                labels: ['Own', 'Rent', 'No Answer']
            };
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
        const onePercentage = total === 0 ? 0 : Math.round((one / total) * 100);
        const twoPercentage = total === 0 ? 0 : Math.round((two / total) * 100);
        const threePercentage = total === 0 ? 0 : Math.round((three / total) * 100);
        const fourPercentage = total === 0 ? 0 : 100 - onePercentage - twoPercentage - threePercentage;
        const noAnswerPercentage = onePercentage === 0 && twoPercentage === 0 && threePercentage === 0 && fourPercentage === 0
            ? 100
            : 0;

        return {
                colors: ['red', 'orange', 'green', 'black', 'grey'],
                data: [onePercentage, twoPercentage, threePercentage, fourPercentage, noAnswerPercentage],
                labels: ['1-2', '2-3', '3-4', '4+', 'No Answer']
            };
    },

    /**
     *  top destination analytics mapper
     *  @param {Object} data
     * @returns {Array}
     */
    topDestinationDetail: (data) => {
        let total = 0;
        if (Array.isArray(data) && data.length > 0) {
            data.forEach(({ count = 0 }) => {
                total += count;
            });
        }

        if (total === 0) {
            return [];
        }

        let output = [];
        let totalPercentageCount = 0;
        for (let i = 0; i < data.length; i++) {
            const { count = 0 } = data[i];
            const customer_percentage = i === data.length - 1
                ? 100 - totalPercentageCount
                : Math.round((count / total) * 100);
            output = [...output, { ...data[i], customer_percentage } ];
            totalPercentageCount += customer_percentage;
        }
        return output;
    }
};
