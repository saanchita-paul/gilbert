import axios from 'axios';
import COLOR from "@scripts/data/constants/COLOR";
import LeadOverviewMapper from "@scripts/api/mappers/LeadOverviewMapper";
import LeadSentimentMapper from "@scripts/api/mappers/LeadSentimentMapper";
import LocationInsightMapper from "@scripts/api/mappers/LocationInsightMapper";

export default {
    getUtilityDashboardData: async () => {
        let data =  {
            "leadOverview":{
               "total_lead":{
                  "total":8,
                  "charts":[
                     {
                        "total":2,
                        "date":"2021-03-21"
                     }
                  ]
               },
               "qualified_lead":{
                  "total":10,
                  "charts":[
                     {
                        "total":2,
                        "date":"2021-03-21"
                     }
                  ]
               },
               "total_energy_connection":{
                  "total":2,
                  "charts":[
                     {
                        "total":1,
                        "date":"2021-03-20"
                     },
                     {
                        "total":1,
                        "date":"2021-03-21"
                     }
                  ]
               },
               "conversion_rate":{
                  "total":25,
                  "charts":[
                     {
                        "date":"2021-03-21",
                        "total_lead":2,
                        "total_conversion":1
                     }
                  ],
                  "qualified_lead":125,
                  "total_conversion":20
               },
               "automation_rate":{
                  "total":745,
                  "charts":[
                     {
                        "date":"",
                        "full_automation":90,
                        "manual_intervention":10
                     }
                  ],
                  "full_automation":10,
                  "manual_intervention":90
               }
            },
            "leadSentiment":[
               {
                  "total":10,
                  "sentiment_text":"NEGATIVE",
                  "percentage": 9
               },
               {
                  "total":43,
                  "sentiment_text":"NEUTRAL",
                  "percentage": 41
               },
               {
                  "total":47,
                  "sentiment_text":"POSITIVE",
                  "percentage": 48
               }
            ],
            "leadToConnection":{
               "lead_by_channel":8,
               "service_connection":0,
               "connection_type":0,
               "connection_submitted":11,
               "connection_accepted":2
            },
            "locationInsight":[
               {
                  "total":3,
                  "state":"ACT"
               },
               {
                  "total":2,
                  "state":"NSW"
               },
               {
                  "total":2,
                  "state":"SA"
               },
               {
                  "total":3,
                  "state":"VIC"
               }
            ],
            "connectionSummary":{
               "plan":[
                  {
                     "total":10,
                     "utility_plan":null
                  },
                  {
                     "total":1,
                     "utility_plan":"Total Plan (Home)"
                  }
               ],
               "connectionType":[
                  {
                     "total":9,
                     "which_utility":"electricity_and_gas"
                  },
                  {
                     "total":2,
                     "which_utility":"gas"
                  }
               ],
               "tenancyType":[
                  {
                     "total":11,
                     "rent":"1"
                  }
               ]
            },
            "ageGroupSummary":{
               "18-24":3,
               "25-34":4,
               "35-44":4,
               "45-54":0,
               "55-64":0,
               "65+":0
            }
         }
         return {
            lead_overview : LeadOverviewMapper.mapLeadOverview(data.leadOverview),
            lead_sentiment : LeadSentimentMapper.mapLeadSentiment(data.leadSentiment)
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
        console.log(LocationInsightMapper.map(data.locationInsight))
    }
}
