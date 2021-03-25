import axios from 'axios';
import COLOR from "@scripts/data/constants/COLOR";
import LeadOverviewMapper from "@scripts/api/mappers/LeadOverviewMapper";
import ConnectionSummary from "@scripts/api/mappers/ConnectionSummary";
import AgeGroupSummaryMapper from "@scripts/api/mappers/AgeGroupSummaryMapper";
import LeadSentimentMapper from "@scripts/api/mappers/LeadSentimentMapper";
import LocationInsightMapper from "@scripts/api/mappers/LocationInsightMapper";

export default {
    getUtilityDashboardData: async () => {
        const data = (await axios.get(`${BOT_API}/utility/home`)).data;
        console.log('DashboardUtilityData', data);
        return {
            lead_overview : LeadOverviewMapper.mapLeadOverview(data.leadOverview),
            connection_summary: ConnectionSummary.getSummaryData(data),
            age_group_summary: AgeGroupSummaryMapper.getAgeGroupSummaryData(data),
            lead_sentiment : LeadSentimentMapper.mapLeadSentiment(data.leadSentiment),
            location_insight : LocationInsightMapper.map(data.locationInsight)
        }
    },
    getConnectionSummary: async () => {
        return {
            connection_plan: {
                title: "Utility Type",
                chart_data: {
                    labels: ['Total Plan', 'Basic Home', 'No Frills'],
                    datasets: [{
                        borderWidth: 1,
                        data: [40, 29, 31],
                        fill: false,
                        backgroundColor: [
                            COLOR.themes.light.primary,
                            COLOR.themes.light.secondary,
                            COLOR.themes.light.purple3,
                        ]
                    }]
                }
            },
            connection_type: {
                title: "Connection Type",
                chart_data: {
                    labels: ['Gas', 'Electricity', 'Dual Fuel'],
                    datasets: [{
                        borderWidth: 1,
                        data: [29, 51, 30],
                        fill: false,
                        backgroundColor: [
                            COLOR.themes.light.primary,
                            COLOR.themes.light.secondary,
                            COLOR.themes.light.purple3,
                        ]
                    }]
                }

            },
            tenancy_type: {
                title: "Tenancy Type",
                chart_data: {
                    labels: ['Own', 'Rent'],
                    datasets: [{
                        borderWidth: 1,
                        data: [40, 60],
                        fill: false,
                        backgroundColor: [
                            COLOR.themes.light.secondary,
                            COLOR.themes.light.purple3,
                        ]
                    }]
                }
            },
            age_group: {},
        }
    },
    utilityAnalytic: async () => {
        const data = (await axios.get(`${BOT_API}/utility/home`)).data;
    }
}
