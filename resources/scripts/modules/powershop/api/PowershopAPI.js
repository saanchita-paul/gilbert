import axios from "axios";
import PowershopMapper from "@scripts/modules/powershop/api/mappers/PowershopMapper";

const ROOT = `${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api`;

export default {
    getPowershopData: async (query) => {
        try {
            // const data = (await axios.get(`${ROOT}/power-shop-plan-details`, { params: query })).data.data;
            let data = {
                "plans": {
                    "electricity": {
                        "fees": {
                            "manual_connection_fees": "38.28",
                            "remote_connection_fees": "free"
                        },
                        "vdo": {
                            "id": "power_shop_electricity_distributor_id",
                            "marketing_offer_name": "AAAAA",
                            "tarif": "AAAAA",
                            "state": "Vic",
                            "customer_type": "Residential",
                            "consumptions": 4000,
                            "vdo_dmo_percentage": -1,
                            "vdo_dmo_amount": 1619,
                            "mandatory_scripting": "This Powershop 100% Carbon Neutral offer is 1% below the Victorian Default Offer."
                        },
                        "offers": {
                            "title": "$1,249/year",
                            "line_1": "1% less than the",
                            "line_2": "Estimated cost and comparison for a residential customer using 4000kWh on a Single rate tariff in the CitiPower network."
                        },
                        "distributor_name": "AusGrid",
                        "supply_charge": {
                            "description": "Daily Supply Charge",
                            "value": "114.90",
                            "unit": "¢/day"
                        },
                        "usage_charge": {
                            "description": "Daily Usage Charge",
                            "value": "23.05",
                            "unit": "¢/kWh"
                        },
                        "bpid_links": [
                            {
                                "id": 65,
                                "origin_electricity_distributor_id": 9,
                                "title": "OR2381566MR",
                                "state": "VIC",
                                "offer_name": "Origin Home Assist (Single Rate)",
                                "fuel_type": "Electricity",
                                "tarif_type": "Single rate",
                                "file_url": "https:\\/\\/www.originenergy.com.au\\/content\\/dam\\/pricing\\/energy-fact-sheets\\/2021\\/OR2381566MR.pdf",
                                "created_at": "2022-07-01 01:49:12",
                                "updated_at": "2022-07-01 01:49:12"
                            },
                            {
                                "id": 66,
                                "title": "OR2381566MR",
                                "origin_electricity_distributor_id": 9,
                                "state": "VIC",
                                "offer_name": "Origin Home Assist (Single Rate with Dedicated Circuit)",
                                "fuel_type": "Electricity",
                                "tarif_type": "Two rate: single rate with controlled load",
                                "file_url": "https:\\/\\/www.originenergy.com.au\\/content\\/dam\\/pricing\\/energy-fact-sheets\\/2021\\/OR2381567MR.pdf",
                                "created_at": "2022-07-01 01:49:12",
                                "updated_at": "2022-07-01 01:49:12"
                            }
                        ]
                    },
                    "gas": {
                        "distributor_name": "Jamena",
                        "fees": {
                            "reconnection": "$66.63"
                        },
                        "supply_charge": {
                            "description": "Daily Supply Charge",
                            "value": "74.70",
                            "unit": "¢/day"
                        },
                        "usage_charge": {
                            "description": "Anytime",
                            "value": "2.12",
                            "unit": "¢/MJ"
                        },
                        "solar_fees": "7.7c/kWh",
                        "bpid_links": [
                            {
                                "id": 65,
                                "origin_electricity_distributor_id": 9,
                                "title": "OR2381566MR",
                                "state": "VIC",
                                "offer_name": "Origin Home Assist (Single Rate)",
                                "fuel_type": "Electricity",
                                "tarif_type": "Single rate",
                                "file_url": "https:\\/\\/www.originenergy.com.au\\/content\\/dam\\/pricing\\/energy-fact-sheets\\/2021\\/OR2381566MR.pdf",
                                "created_at": "2022-07-01 01:49:12",
                                "updated_at": "2022-07-01 01:49:12"
                            }
                        ]
                    }
                }
            }

            return PowershopMapper.mapPowershopData(data);
        } catch (error) {
            console.log("PowerShop Plan Details Fetch Error", error);
            return error.data;
        }
    }
};
