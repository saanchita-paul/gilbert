import COLOR from "@scripts/data/constants/COLOR";

export default {

    /**
     * energy usage analytics mapper
     * @param {Object} data
     * @returns {Object}
     */
    getAgeGroupSummaryData: (response) => {


        function getAgeGroup(data) {
            let labels = Object.keys(data);
            let values = Object.values(data);
            let totalValue = values.reduce((a, b) => a + b, 0);
            return {
                labels: labels,
                datasets: [{

                    borderWidth: 1,
                    data: values.map(d=> Math.floor (d/totalValue * 100)),
                }]
            }
        }

        return {
            age_group: getAgeGroup(response.ageGroupSummary)

        };
    },

}
