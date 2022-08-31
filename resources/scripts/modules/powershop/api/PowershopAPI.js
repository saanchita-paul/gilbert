import axios from "axios";
import PowershopMapper from "@scripts/modules/powershop/api/mappers/PowershopMapper";
import {isNull} from "lodash-es";

const ROOT = `${process.env.MIX_BOT_ROOT_URL}`;

export default {
    getPowerShopData: async (query) => {
        try {
            if(query.nmi !== '') {
                query.nmi_prefix = query.nmi?.substr(0,3);
            }

            const data = (await axios.get(`https://demo.chatbot.hood.ai/hood-dashboard/api/power-shop-plan-details`, { params: query })).data.data;
            const plan = await PowershopMapper.mapPowershopData(data);
           return plan;

        } catch (error) {
            console.log("PowerShop Plan Details Fetch Error", error);
            return null;
        }
    },

    updatePaymentInformation: async (field, value, applicationId) => {
        const payload = {
            [field]: value,
            'app_id': applicationId
        }

        try {
            return await axios.post('/api/powershop/payment', payload);
        } catch (error) {
            console.log("Error!");
        }
    },
};
