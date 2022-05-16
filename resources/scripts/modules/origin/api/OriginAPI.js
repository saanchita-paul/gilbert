import axios from "axios";
import OriginMapper from "@scripts/modules/origin/api/mappers/OriginMapper";

const ROOT = `${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api`;

export default {
    getOriginData: async () => {
        try {
            // const planDetails = (await axios.get(`${ROOT}/origin-power-plan-details`, { params: '3044' })).data.data;
            // return mapAllPlan(data)

            let data = {
                title: "Great rates that wont change over 12 months",
                short_des:
                    "Rates guaranteed for 12 months, simplified pricing, Everyday Rewards points",
                plans: [
                    {
                        title: "Electricity",
                        charge: "$123/Year",
                        slogan:
                            "80% off the Victorian Default Offer Reference Price",
                        details:
                            "Estimated cost inc GST for an average household using 3900 kWh/yearly on a flat rate tariff in the Ausgrid network.",
                        supply_charge: [
                            {
                                title: "Daily Supply Charge (c/day)",
                                value: "77"
                            }
                        ],
                        usage_charge: [
                            {
                                title: "Peak Usage (c/kWh)",
                                value: "23.87"
                            },
                            {
                                title: "Temporary Peak Usage (c/kWh)",
                                value: "23.87"
                            }
                        ]
                    },
                    {
                        title: "Gas",
                        charge: "$74/Year",
                        slogan:
                            "70% off the Victorian Default Offer Reference Price",
                        details:
                            "Estimated cost inc GST for an average household using 3900 kWh/yearly on a flat rate tariff in the Ausgrid network.",
                        supply_charge: [
                            {
                                title: "Daily Supply Charge (c/day)",
                                value: "76.99"
                            }
                        ],
                        usage_charge: [
                            {
                                title: "Peak Usage (c/kWh)",
                                value: "23.87"
                            },
                            {
                                title: "Peak Usage (c/kWh)",
                                value: "12.13"
                            }
                        ]
                    }
                ],
                rates: "Guaranteed",
                exit_fees: "No",
                benefit_period: "12 months",
                green_options: "Everyday Rewards members enjoy 25% GreenPower and 100% Green Gas for nothing extra.",
                bpid_links: [
                    {
                        title: "Energy Fact Sheet (Electricity)",
                        url: "https://google.com"
                    },
                    {
                        title: "Energy Fact Sheet (Gas)",
                        url: "https://google.com"
                    },
                    {
                        title: "Terms and conditions",
                        url: "https://google.com"
                    }
                ]
            };
            return OriginMapper.mapOriginData(data);

        } catch (error) {
            console.log('Origin Plan Details Fetch Error', error);
            return error.data;
        }
    }
};
