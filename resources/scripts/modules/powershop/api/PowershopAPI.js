import axios from "axios";
import PowershopMapper from "@scripts/modules/powershop/api/mappers/PowershopMapper";
import {isNull} from "lodash-es";

const ROOT = `${process.env.MIX_BOT_ROOT_URL}`;

export default {
    getPowerShopData: async (query) => {
        try {

            isNull()
            if(query.nmi !== '') {
                query.nmi_prefix = query.nmi?.substr(0,3);
            }
            //   const data = (await axios.get(`${ROOT}/api/power-shop-plan-details`, { params: query })).data.data;
             const data = {
                 "plans": {
                     "electricity": [
                         {
                             "price": [
                                 {
                                     "title": "Manual connection (insert fuse)",
                                     "fees": "45.86"
                                 },
                                 {
                                     "title": "Manual connection (meter read only)",
                                     "fees": "12.79"
                                 },
                                 {
                                     "title": "After-hours manual connection (insert fuse)",
                                     "fees": "126.14"
                                 }
                             ],
                             "vdo": {
                                 "id": 7,
                                 "power_shop_electricity_distributor_id": 5,
                                 "marketing_offer_name": "Powershop 100% Carbon Neutral",
                                 "customer": "Residential",
                                 "state": "NSW",
                                 "tariff": "EA010",
                                 "vdo_dmo_percentage": "-6",
                                 "vdo_dmo_amount": "1409",
                                 "consumption": "3900",
                                 "description": "We estimate the annual spend on this offer will be $1409. This estimate is based on an average Residential customer who uses 3900 kWh per year on a Single Rate tariff in the Ausgrid Network. Your actual bills will vary depending on your usage and any price changes in the future. This is an ongoing contract (until you or we end it) with no exit fees.",
                                 "created_at": "2022-08-16 20:55:44",
                                 "updated_at": "2022-08-16 20:55:44"
                             },
                             "offers": {
                                 "title": "$100/Year ",
                                 "line_1": "For an average household using 3900 kWh/year, the estimated annual cost of this electricity plan is $1409 in the AusGrid network with single rate tariff.",
                                 "line_2": "6% less than the"
                             },
                             "distributor_name": "AusGrid",
                             "bpid_links": [
                                 {
                                     "service_type": "electricity",
                                     "title": "SingleRate-ControlLoad1.pdf",
                                     "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\SingleRate-ControlLoad1.pdf",
                                     "distributor_group": "ausgrid"
                                 },
                                 {
                                     "service_type": "electricity",
                                     "title": "SingleRate-ControlLoad2.pdf",
                                     "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\SingleRate-ControlLoad2.pdf",
                                     "distributor_group": "ausgrid"
                                 },
                                 {
                                     "service_type": "electricity",
                                     "title": "SingleRate.pdf",
                                     "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\SingleRate.pdf",
                                     "distributor_group": "ausgrid"
                                 },
                                 {
                                     "service_type": "electricity",
                                     "title": "TimeOfUse-ControlLoad1.pdf",
                                     "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\TimeOfUse-ControlLoad1.pdf",
                                     "distributor_group": "ausgrid"
                                 },
                                 {
                                     "service_type": "electricity",
                                     "title": "TimeOfUse.pdf",
                                     "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\TimeOfUse.pdf",
                                     "distributor_group": "ausgrid"
                                 },
                                 {
                                     "service_type": "electricity",
                                     "title": "tou-cl2.pdf",
                                     "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\tou-cl2.pdf",
                                     "distributor_group": "ausgrid"
                                 },
                                 {
                                     "service_type": "electricity",
                                     "title": "tou-transitional-cl1.pdf",
                                     "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\tou-transitional-cl1.pdf",
                                     "distributor_group": "ausgrid"
                                 },
                                 {
                                     "service_type": "electricity",
                                     "title": "tou-transitional-cl2.pdf",
                                     "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\tou-transitional-cl2.pdf",
                                     "distributor_group": "ausgrid"
                                 },
                                 {
                                     "service_type": "electricity",
                                     "title": "tou-transitional.pdf",
                                     "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\tou-transitional.pdf",
                                     "distributor_group": "ausgrid"
                                 }
                             ],
                             "plan_name": "Powershop 100% Carbon Neutral",
                             "plan_code": "Powershop 100% Carbon Neutral",
                             "daily_charge": "233",
                             "anytime_charge": "100"
                         }
                     ],
                     "gas": [{
                         "distributor_name": "AusGrid",
                         "daily_charge": "233",
                         "anytime_charge": "100",
                         "price": [
                             {
                                 "title": "Reconnection",
                                 "fees": "45.86"
                             }
                         ],
                         "bpid_links": [
                             {
                                 "service_type": "electricity",
                                 "title": "SingleRate-ControlLoad1.pdf",
                                 "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\SingleRate-ControlLoad1.pdf",
                                 "distributor_group": "ausgrid"
                             },
                             {
                                 "service_type": "electricity",
                                 "title": "SingleRate-ControlLoad2.pdf",
                                 "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\SingleRate-ControlLoad2.pdf",
                                 "distributor_group": "ausgrid"
                             },
                             {
                                 "service_type": "electricity",
                                 "title": "SingleRate.pdf",
                                 "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\SingleRate.pdf",
                                 "distributor_group": "ausgrid"
                             },
                             {
                                 "service_type": "electricity",
                                 "title": "TimeOfUse-ControlLoad1.pdf",
                                 "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\TimeOfUse-ControlLoad1.pdf",
                                 "distributor_group": "ausgrid"
                             },
                             {
                                 "service_type": "electricity",
                                 "title": "TimeOfUse.pdf",
                                 "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\TimeOfUse.pdf",
                                 "distributor_group": "ausgrid"
                             },
                             {
                                 "service_type": "electricity",
                                 "title": "tou-cl2.pdf",
                                 "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\tou-cl2.pdf",
                                 "distributor_group": "ausgrid"
                             },
                             {
                                 "service_type": "electricity",
                                 "title": "tou-transitional-cl1.pdf",
                                 "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\tou-transitional-cl1.pdf",
                                 "distributor_group": "ausgrid"
                             },
                             {
                                 "service_type": "electricity",
                                 "title": "tou-transitional-cl2.pdf",
                                 "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\tou-transitional-cl2.pdf",
                                 "distributor_group": "ausgrid"
                             },
                             {
                                 "service_type": "electricity",
                                 "title": "tou-transitional.pdf",
                                 "link": "C:\\projects\\laragon\\www\\hood-chatbot\\storage\\C:\\projects\\laragon\\www\\hood-chatbot\\storage\\app/power_shop/processed/BPID/Out_OF_Victoria/Elec Residential\\ausgrid\\tou-transitional.pdf",
                                 "distributor_group": "ausgrid"
                             }],
                     }]
                 }
             };
            const dd =  await PowershopMapper.mapPowershopData(data);
            return dd;
            return PowershopMapper.mapPowershopData(data);
        } catch (error) {
            console.log("PowerShop Plan Details Fetch Error", error);
            return null;
        }
    }
};
