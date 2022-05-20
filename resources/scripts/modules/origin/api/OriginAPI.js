import axios from "axios";
import OriginMapper from "@scripts/modules/origin/api/mappers/OriginMapper";

// const ROOT = `${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api`;
const ROOT = "https://demo.chatbot.hood.ai/hood-dashboard/api"

export default {
    getOriginData: async query => {
        try {
            const data = (await axios.get(`${ROOT}/origin-plan-details`, { params: query })).data.data;
            return OriginMapper.mapOriginData(data);

             // let data = {
            //     plans: {
            //       electricity: {
            //         vdo: {
            //           id: 5,
            //           origin_electricity_distributor_id: 3,
            //           campaign_code: "C-00104500",
            //           marketing_offer_name: "Origin Home Assist",
            //           tarif: "flat rate",
            //           condumtions: 4000,
            //           vdo_dmo_percentage: 7,
            //           vdo_dmo_amount: 1417,
            //           more_or_less: "Less than",
            //           created_at: "2022-05-17 09:06:11",
            //           updated_at: "2022-05-17 09:06:11"
            //         },
            //         distributor_name: "Ausnet",
            //         supply_charge: [
            //           {
            //             id: 72,
            //             origin_electricity_distributor_id: 3,
            //             state: "VIC",
            //             fuel_type: "ELECTRICITY",
            //             group_1: "Residential, single rate",
            //             group_2: "Residential Peak Anytime (GD/GR)",
            //             unit: "cents per day",
            //             description: "Daily Supply Charge",
            //             gst_excl: "107.2728",
            //             gst_inc: "118.0000",
            //             gst_inc_round_2: "118.00",
            //             created_at: "2022-05-17 09:00:19",
            //             updated_at: "2022-05-17 09:00:19"
            //           }
            //         ],
            //         usage_charge: [
            //           {
            //             id: 70,
            //             origin_electricity_distributor_id: 3,
            //             state: "VIC",
            //             fuel_type: "ELECTRICITY",
            //             group_1: "Residential, single rate",
            //             group_2: "Residential Peak Anytime (GD/GR)",
            //             unit: "cents per kWh",
            //             description: "Peak Usage of first 11.1781 kWh/Day",
            //             gst_excl: "22.4000",
            //             gst_inc: "24.6400",
            //             gst_inc_round_2: "24.64",
            //             created_at: "2022-05-17 09:00:19",
            //             updated_at: "2022-05-17 09:00:19"
            //           },
            //           {
            //             id: 71,
            //             origin_electricity_distributor_id: 3,
            //             state: "VIC",
            //             fuel_type: "ELECTRICITY",
            //             group_1: "Residential, single rate",
            //             group_2: "Residential Peak Anytime (GD/GR)",
            //             unit: "cents per kWh",
            //             description: "Peak Usage - Balance kWh/Day",
            //             gst_excl: "23.7728",
            //             gst_inc: "26.1500",
            //             gst_inc_round_2: "26.15",
            //             created_at: "2022-05-17 09:00:19",
            //             updated_at: "2022-05-17 09:00:19"
            //           }
            //         ],
            //         bpid_links: [
            //           {
            //             id: 4,
            //             origin_electricity_distributor_id: 3,
            //             state: "VIC",
            //             offer_name: "Origin Home Assist (Single Rate with Dedicated Circuit)",
            //             fuel_type: "Electricity",
            //             tarif_type: "Two rate: single rate with controlled load",
            //             file_url: "https://www.originenergy.com.au/content/dam/pricing/energy-fact-sheets/2021/OR2381561MR.pdf",
            //             created_at: "2022-05-17 09:14:36",
            //             updated_at: "2022-05-17 09:14:36"
            //           },
            //           {
            //             id: 6,
            //             origin_electricity_distributor_id: 3,
            //             state: "VIC",
            //             offer_name: "Origin Home Assist (Single Rate)",
            //             fuel_type: "Electricity",
            //             tarif_type: "Single rate",
            //             file_url: "https://www.originenergy.com.au/content/dam/pricing/energy-fact-sheets/2021/OR2381563MR.pdf",
            //             created_at: "2022-05-17 09:14:36",
            //             updated_at: "2022-05-17 09:14:36"
            //           },
            //           {
            //             id: 11,
            //             origin_electricity_distributor_id: 3,
            //             state: "VIC",
            //             offer_name: "Origin Home Assist (2 Period TOU)",
            //             fuel_type: "Electricity",
            //             tarif_type: "Time of use",
            //             file_url: "https://www.originenergy.com.au/content/dam/pricing/energy-fact-sheets/2021/OR2381572MR.pdf",
            //             created_at: "2022-05-17 09:14:36",
            //             updated_at: "2022-05-17 09:14:36"
            //           },
            //           {
            //             id: 16,
            //             origin_electricity_distributor_id: 3,
            //             state: "VIC",
            //             offer_name: "Origin Home Assist (2 Period TOU with Dedicated Circuit)",
            //             fuel_type: "Electricity",
            //             tarif_type: "Time of use with controlled load",
            //             file_url: "https://www.originenergy.com.au/content/dam/pricing/energy-fact-sheets/2021/OR2381577MR.pdf",
            //             created_at: "2022-05-17 09:14:36",
            //             updated_at: "2022-05-17 09:14:36"
            //           }
            //         ]
            //       },
            //       gas: {
            //         supply_charge: [
            //           {
            //             id: 36,
            //             origin_gas_distributor_id: 27,
            //             state: "VIC",
            //             fuel_type: "NATURAL GAS",
            //             group_1: "TRUenergy East",
            //             group_2: "Domestic - General (Tariff 03)",
            //             unit: "cents per day",
            //             description: "Daily Supply Charge",
            //             gst_excl: "65.9091",
            //             gst_inc: "72.5000",
            //             gst_inc_round_2: "72.50",
            //             created_at: "2022-05-18 10:45:02",
            //             updated_at: "2022-05-18 10:45:02"
            //           }
            //         ],
            //         usage_charge: [
            //           {
            //             id: 33,
            //             origin_gas_distributor_id: 27,
            //             state: "VIC",
            //             fuel_type: "NATURAL GAS",
            //             group_1: "TRUenergy East",
            //             group_2: "Domestic - General (Tariff 03)",
            //             unit: "cents per MJ",
            //             description: "Usage of first 100 MJ/Day",
            //             gst_excl: "2.2364",
            //             gst_inc: "2.4600",
            //             gst_inc_round_2: "2.46",
            //             created_at: "2022-05-18 10:45:02",
            //             updated_at: "2022-05-18 10:45:02"
            //           },
            //           {
            //             id: 34,
            //             origin_gas_distributor_id: 27,
            //             state: "VIC",
            //             fuel_type: "NATURAL GAS",
            //             group_1: "TRUenergy East",
            //             group_2: "Domestic - General (Tariff 03)",
            //             unit: "cents per MJ",
            //             description: "Usage of next 100 MJ/Day",
            //             gst_excl: "2.1273",
            //             gst_inc: "2.3400",
            //             gst_inc_round_2: "2.34",
            //             created_at: "2022-05-18 10:45:02",
            //             updated_at: "2022-05-18 10:45:02"
            //           },
            //           {
            //             id: 35,
            //             origin_gas_distributor_id: 27,
            //             state: "VIC",
            //             fuel_type: "NATURAL GAS",
            //             group_1: "TRUenergy East",
            //             group_2: "Domestic - General (Tariff 03)",
            //             unit: "cents per MJ",
            //             description: "Remaining Usage MJ/Day",
            //             gst_excl: "1.8455",
            //             gst_inc: "2.0300",
            //             gst_inc_round_2: "2.03",
            //             created_at: "2022-05-18 10:45:02",
            //             updated_at: "2022-05-18 10:45:02"
            //           }
            //         ],
            //         bpid_links: [
            //           {
            //             id: 4,
            //             origin_gas_distributor_id: 27,
            //             state: "VIC",
            //             offer_name: "Origin Advantage Variable - HOOD",
            //             fuel_type: "Gas",
            //             tarif_type: "Single rate",
            //             file_url: "https://www.originenergy.com.au/content/dam/pricing/residential/energy-price-fact-sheets/1April2022/OR2383674MR.pdf",
            //             created_at: "2022-05-17 09:27:53",
            //             updated_at: "2022-05-17 09:27:53"
            //           },
            //           {
            //             id: 38,
            //             origin_gas_distributor_id: 27,
            //             state: "VIC",
            //             offer_name: "Origin Advantage Variable - HOOD",
            //             fuel_type: "Gas",
            //             tarif_type: "Single rate",
            //             file_url: "https://www.originenergy.com.au/content/dam/pricing/residential/energy-price-fact-sheets/1April2022/OR2383674MR.pdf",
            //             created_at: "2022-05-17 09:50:14",
            //             updated_at: "2022-05-17 09:50:14"
            //           }
            //         ]
            //       }
            //     },
            //     rates: "Guaranteed",
            //     exit_fees: "No",
            //     benefit_period: "12 months",
            //     green_options: "Everyday Rewards members enjoy 25% GreenPower and 100% Green Gas for nothing extra."
            //   }

            // return OriginMapper.mapOriginData(data);
        } catch (error) {
            console.log("Origin Plan Details Fetch Error", error);
            return error.data;
        }
    }
};
