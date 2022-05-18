import axios from "axios";
import OriginMapper from "@scripts/modules/origin/api/mappers/OriginMapper";

const ROOT = `${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api`;

export default {
    getOriginData: async query => {
        try {
            // const planDetails = (await axios.get(`${ROOT}/origin-plan-details`, { params: query })).data.data;
            // return OriginMapper.mapOriginData(planDetails);

            // let data = {
            //     title: "Great rates that wont change over 12 months",
            //     short_des:
            //         "Rates guaranteed for 12 months, simplified pricing, Everyday Rewards points",
            //     plans: [
            //         {
            //             title: "Electricity",
            //             charge: "$123/Year",
            //             slogan:
            //                 "80% off the Victorian Default Offer Reference Price",
            //             details:
            //                 "Estimated cost inc GST for an average household using 3900 kWh/yearly on a flat rate tariff in the Ausgrid network.",
            //             supply_charge: [
            //                 {
            //                     title: "Daily Supply Charge (c/day)",
            //                     value: "77"
            //                 }
            //             ],
            //             usage_charge: [
            //                 {
            //                     title: "Peak Usage (c/kWh)",
            //                     value: "23.87"
            //                 },
            //                 {
            //                     title: "Temporary Peak Usage (c/kWh)",
            //                     value: "23.87"
            //                 }
            //             ],
            //             fees: [
            //                 {
            //                     title: "Standard Connection Fee",
            //                     value: "$293.00"
            //                 },
            //                 {
            //                     title: "Same Day Connection Fee",
            //                     value: "$293.00"
            //                 }
            //             ]
            //         },
            //     ],
            //     rates: "Guaranteed",
            //     exit_fees: "No",
            //     benefit_period: "12 months",
            //     green_options:
            //         "Everyday Rewards members enjoy 25% GreenPower and 100% Green Gas for nothing extra.",
            //     bpid_links: [
            //         {
            //             title: "Energy Fact Sheet (Electricity)",
            //             url: "https://google.com"
            //         },
            //         {
            //             title: "Energy Fact Sheet (Gas)",
            //             url: "https://google.com"
            //         },
            //         {
            //             title: "Terms and conditions",
            //             url: "https://google.com"
            //         }
            //     ]
            // };

            let data = {
                  plans: {
                    electricity_plan: {
                      vdo_dmo_percentage: 7,
                      vdo_dmo_amount: 123,
                      condumtions: 400,
                      tarif: "flat rate",
                      distributor_name: "CityPower",
                      supply_charge: [
                        {
                          price_description: "Daily Supply Charge",
                          price_unit: "Cents per KWh",
                          gst_inc: 111.82,
                          gst_excl: 110.2
                        }
                      ],
                      usage_charge: [
                        {
                          price_description: "Peak Usage",
                          price_unit: "Cents per KWh",
                          gst_inc: 23.87,
                          gst_excl: 20.2
                        },
                        {
                          price_description: "Temporary Peak Usage",
                          price_unit: "Cents per KWh",
                          gst_inc: 23.87,
                          gst_excl: 200.2
                        }
                      ],
                      bpid_links: [
                        {
                          offer_name: "Energy Fact Sheet (Electricity)",
                          link: "https://google.com"
                        }
                      ]
                    },
                    gas_plan: {
                      supply_charge: [
                        {
                          price_description: "Daily Supply Charge",
                          price_unit: "Cents per KWh",
                          gst_inc: 111.82,
                          gst_excl: 110.2
                        }
                      ],
                      usage_charge: [
                        {
                          price_description: "Peak Usage",
                          price_unit: "Cents per KWh",
                          gst_inc: 23.87,
                          gst_excl: 20.2
                        },
                        {
                          price_description: "Temporary Peak Usage",
                          price_unit: "Cents per KWh",
                          gst_inc: 23.87,
                          gst_excl: 200.2
                        }
                      ],
                      bpid_links: [
                        {
                          offer_name: "Energy Fact Sheet (Gas)",
                          link: "https://google.com"
                        }
                      ]
                    }
                  },
                  rates: "Guaranteed",
                  exit_fees: "No",
                  benefit_period: "12 months",
                  green_options: "Everyday Rewards members enjoy 25% GreenPower and 100% Green Gas for nothing extra."
            }

            return OriginMapper.mapOriginData(data);
        } catch (error) {
            console.log("Origin Plan Details Fetch Error", error);
            return error.data;
        }
    }
};
