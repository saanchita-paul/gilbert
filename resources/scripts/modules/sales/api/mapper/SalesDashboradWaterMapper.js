
export default {

    /**
     * water usage analytics mapper
     * @param {Object} data
     * @returns {Object}
     */
     getWaterDashboardData: (response) => {


        function getWaterData(data, isRejected = false) {

            let lables = [
                'Great Western Water',
                'South Eastern Water',
                'Yarra Valley Water'
            ];

            const greatWesternWaterData = data?.great_western_water;
            const southEasternWaterData = data?.south_eastern_water;
            const yarraValleyWaterData = data?.yarra_valley_water;

            const chartData = [greatWesternWaterData, southEasternWaterData, yarraValleyWaterData];

            const manualSubmissionData = data?.manual_submission;
            const automatedSubmissionData = data?.automated_submission;

            const segmentationData = {
                manualSubmission: manualSubmissionData,
                automatedSubmission: automatedSubmissionData
            }
            
            const backgroundColorList = [
                '#542E89',
                '#03A9F4',
                '#9C27B0',
            ];

            const rejectedbackgroundColor = [
                '#542E89',
                '#03A9F4',
                '#9C27B0',
            ]


            return {
                labels: lables,
                total: greatWesternWaterData + southEasternWaterData + yarraValleyWaterData,
                segmentationData: segmentationData,
                datasets: [{
                    label: 'Water Dataset',
                    data: chartData,
                    backgroundColor: !isRejected ? backgroundColorList : rejectedbackgroundColor,
                }
                ]
            };

        }

        function getSubmittedData(data) {
            return {
                waterChartData: getWaterData(data),
                total: data.total

            }
        }

        function getConvertedData(data, submittedData) {

            let converstionRate = null;
            if(data.total === 0 && submittedData.total ===0) {
                converstionRate = 0;
            } else {
                converstionRate = ((data.total / submittedData.total) * 100).toFixed(1);
            }

            return {
                waterChartData: getWaterData(data),
                total: data.total,
                conversiton_rate: converstionRate
            }
        }

        function getRejectedData(data, declined) {
            return {
                waterChartData: getWaterData(data, true),
                total: data.total,
                declined: declined.total
            }
        }

        return {

            submitted: getSubmittedData(response.submission),
            converted: getConvertedData(response.conversions, response.submission),
            rejected: getRejectedData(response.rejected, response.declined),
            total_open_application: response.total_open_application,
            total_consent_pending: response.total_consent_pending,
            total_closed: response.total_closed,

        };
    },

}
