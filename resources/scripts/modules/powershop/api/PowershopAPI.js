import PowershopMapper from "@scripts/modules/powershop/api/mappers/PowershopMapper";

export default {
    getPowershopData: async () => {
        try {
            let data = {
                    "plans": {
                        "electricity": {
                            "fees": {
                                "manual_connection_fees": "12",
                                "remote_connection_fees": "12"
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
                                "title": "$100/Year ",
                                "line_1": "For an average household using 4000 kWh/year, the estimated annual cost of this electricity plan is $100 in the Jamena network with single rate tariff.",
                                "line_2": "This is currently 5% 200 than the Victorian Default Offer reference price"
                            },
                            "distributor_name": "Jamena",
                            "supply_charge": {
                                "id": 93,
                                "origin_electricity_distributor_id": 9,
                                "state": "VIC",
                                "fuel_type": "ELECTRICITY",
                                "group_1": "Residential, single rate",
                                "group_2": "Residential Peak Anytime (GD\\/GR)",
                                "unit": "cents per day",
                                "description": "Daily Supply Charge",
                                "gst_excl": "104.2000",
                                "gst_inc": "114.6200",
                                "gst_inc_round_2": "114.62",
                                "created_at": "2022-07-01 01:49:12",
                                "updated_at": "2022-07-01 01:49:12"
                            },
                            "usage_charge": {
                                "id": 92,
                                "origin_electricity_distributor_id": 9,
                                "state": "VIC",
                                "fuel_type": "ELECTRICITY",
                                "group_1": "Residential, single rate",
                                "group_2": "Residential Peak Anytime (GD\\/GR)",
                                "unit": "cents per kWh",
                                "description": "All Usage",
                                "gst_excl": "20.9000",
                                "gst_inc": "22.9900",
                                "gst_inc_round_2": "22.99",
                                "created_at": "2022-07-01 01:49:12",
                                "updated_at": "2022-07-01 01:49:12"
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
                            "fees": {
                                "standard_connection_fee": "15"
                            },
                            "distributor_name": "AusNet Central",
                            "supply_charge": {
                                "id": 93,
                                "origin_electricity_distributor_id": 9,
                                "state": "VIC",
                                "fuel_type": "ELECTRICITY",
                                "group_1": "Residential, single rate",
                                "group_2": "Residential Peak Anytime (GD\\/GR)",
                                "unit": "cents per day",
                                "description": "Daily Supply Charge",
                                "gst_excl": "104.2000",
                                "gst_inc": "114.6200",
                                "gst_inc_round_2": "114.62",
                                "created_at": "2022-07-01 01:49:12",
                                "updated_at": "2022-07-01 01:49:12"
                            },
                            "usage_charge": {
                                "id": 92,
                                "origin_electricity_distributor_id": 9,
                                "state": "VIC",
                                "fuel_type": "ELECTRICITY",
                                "group_1": "Residential, single rate",
                                "group_2": "Residential Peak Anytime (GD\\/GR)",
                                "unit": "cents per kWh",
                                "description": "All Usage",
                                "gst_excl": "20.9000",
                                "gst_inc": "22.9900",
                                "gst_inc_round_2": "22.99",
                                "created_at": "2022-07-01 01:49:12",
                                "updated_at": "2022-07-01 01:49:12"
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
                        }
                    },
                    "rates": "Guaranteed",
                    "exit_fees": "No",
                    "benefit_period": "12 months",
                    "green_options": "Everyday Rewards members enjoy 25% GreenPower and 100% Green Gas for nothing extra."
            }

            return PowershopMapper.mapPowershopData(data);
        } catch (error) {
            console.log("PowerShop Plan Details Fetch Error", error);
            return error.data;
        }
    }
};
