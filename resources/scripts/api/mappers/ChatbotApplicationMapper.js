import IdDetail from "@scripts/models/chatbot/IdDetail";
import PersonalDetail from "@scripts/models/chatbot/PersonalDetail";
import PropertyDetail from "@scripts/models/chatbot/PropertyDetail";
import ChatbotApplication from "@scripts/models/chatbot/ChatbotApplication";
import ApplicationNote from "@scripts/models/chatbot/ApplicationNote";
import * as dayjs from "dayjs";
import ConnectionService from "@scripts/models/chatbot/ConnectionService";
import ConcessionDetail from "@scripts/models/chatbot/ConcessionDetail";

export default {
    mapApplication: (application) => {
        const id_detail = new IdDetail(application);
        const personal_detail = new PersonalDetail(application);
        const property_detail = new PropertyDetail(application);
        const concession_details = new ConcessionDetail(application);
        const application_note = application.application_notes?.map(item => new ApplicationNote(item));
        const connection_service = application.connection_services?.map(item => new ConnectionService(item));
        let eleService = null;
        let gasService = null;

        let electricityServiceOnly = application.connection_services.find((item) => {
            return item.service_type === 'electricity'
        })

        if(electricityServiceOnly){
            eleService = new ConnectionService(electricityServiceOnly);
        }

        let gasServiceOnly = application.connection_services.find((item) => {
            return item.service_type === 'gas'
        })

        if(gasServiceOnly){
            gasService = new ConnectionService(gasServiceOnly);
        }


        return new ChatbotApplication({
            id_detail: id_detail,
            personal_details: personal_detail,
            property_details: property_detail,
            application_notes : application_note,
            connection_services : connection_service,
            concession_details: concession_details,
            eleService : eleService,
            gasService : gasService,
        });
    },

    mapTosavePersonalData: data =>
    {
        return {
            ...data,
            dob:dayjs(data.dob,'DD/MM/YYYY').isValid()?
                dayjs(data.dob,'DD/MM/YYYY')
                    .format('YYYY-MM-DD'): ''
        }
    },

    mapTosavePropertyData: data =>
    {
        return {
            ...data,
            moved_at:dayjs(data.moved_at,'DD/MM/YYYY').isValid()?
                dayjs(data.moved_at,'DD/MM/YYYY')
                    .format('YYYY-MM-DD'): ''
        }
    },


    mapTosaveIdData : data=> {
        return {
            ...data,
            identification_expire_date:dayjs(data.identification_expire_date,'DD/MM/YYYY').isValid()?
                dayjs(data.identification_expire_date,'DD/MM/YYYY')
                    .format('YYYY-MM-DD'): ''
        }
    },
    mapTosaveConcessionData : data=> {
        return {
            ...data,
            concession_card_start_date:dayjs(data.concession_card_start_date,'DD/MM/YYYY').isValid()?
                dayjs(data.concession_card_start_date,'DD/MM/YYYY')
                    .format('YYYY-MM-DD'): '' ,
            concession_end_date:dayjs(data.concession_end_date,'DD/MM/YYYY').isValid()?
                dayjs(data.concession_end_date,'DD/MM/YYYY')
                    .format('YYYY-MM-DD'): ''
        }
    }
}
