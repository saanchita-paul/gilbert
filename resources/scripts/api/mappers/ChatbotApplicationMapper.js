import IdDetail from "@scripts/models/chatbot/IdDetail";
import PersonalDetail from "@scripts/models/chatbot/PersonalDetail";
import PropertyDetail from "@scripts/models/chatbot/PropertyDetail";
import ChatbotApplication from "@scripts/models/chatbot/ChatbotApplication";
import ApplicationNote from "@scripts/models/chatbot/ApplicationNote";
import StatusLog from "@scripts/models/chatbot/StatusLog";
import * as dayjs from "dayjs";
import ConnectionService from "@scripts/models/chatbot/ConnectionService";
import ConcessionDetail from "@scripts/models/chatbot/ConcessionDetail";
import GBGAddress from "@scripts/models/chatbot/GBGAddress";
import {isNull} from "lodash-es";

function mapPropertyAddress(dt) {
    return {
        street_address :dt?.street_address,
        city : dt?.suburb,
        postcode : dt?.to_postcode,
        state : dt?.state,
        state_short : dt?.state,
        street_number : dt?.street_number,
        unit_number : dt?.flat_or_unit_number,
        street_name : dt?.street_name,
        street_name_only : dt?.street_name_only,
        street_type : dt?.street_type,
        mannual_address : false,
        billing_street_address : dt?.billing_street_address,
        billing_city : dt?.billing_city,
        billing_postcode : dt?.billing_postcode,
        billing_state : dt?.billing_state,
        billing_street_number : dt?.billing_street_number,
        billing_unit_number : dt?.billing_unit_number,
        billing_street_name : dt?.billing_street_name,
        billing_street_name_only : dt?.billing_street_name_only,
        billing_street_type : dt?.billing_street_type,
        billing_mannual_address : false,
        is_billing_same : dt.is_billing_same,
    };
}

function mapChatbotAppStatus(status) {
    switch (status) {
        case 'Manual Processing':
            return 'Manual_Processing';
        case '--':
        case 'Not Submitted':
            return null;
        default:
            return status;

    }

}

function mapApplicationNote(data, isInternalNote = true){
    return data?.filter(item => {
        if(isInternalNote && item.type === 'internal_note'){
            return item;
        }else if(!isInternalNote && item.type === 'status_log'){
            return item;
        }
    }).map(item => {
        if(isInternalNote){
            return new ApplicationNote(item)
        }else{
            return new StatusLog(item)
        }
    })
}

function isCafGenerated(connection_services) {
    return  !!connection_services.find((item) => {
        return item.is_caf_file_generated;
    })
}

export default {
    mapApplication: (application) => {
        console.log("applicationNote", application)
        const id_detail = new IdDetail(application);
        const personal_detail = new PersonalDetail(application);
        const property_detail = new PropertyDetail(application);
        const concession_details = new ConcessionDetail(application);
        const application_note = mapApplicationNote(application.application_notes);
        const status_log = mapApplicationNote(application.application_notes, false);
        const connection_service = application.connection_services?.map(item => new ConnectionService(item));
        const property_address = new GBGAddress(mapPropertyAddress(application));
        let eleService = null;
        let gasService = null;
        let cafStatus = isCafGenerated(application.connection_services);

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
            property_address: property_address,
            cafStatus: cafStatus,
            id: application.id,
            status_log : status_log
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
    },
    mapToUpdateAddress : address => {
        return{
            ...address,
            suburb : address.city,
            to_postcode : address.postcode,
            to_address: address.address_text,
            flat_or_unit_number : address.unit_number
        }
    },

    mapToUpdateServiceStatus: (eleService, gasService, caf_status) => {
        let appStatus = {};
        let service = [];
        if(!isNull(eleService))  {
            service.push({
                ...eleService,
                status: mapChatbotAppStatus(eleService.status)
            });
        }
        if(!isNull(gasService))  {
            service.push({
                ...gasService,
                status: mapChatbotAppStatus(gasService.status)
            });
        }
        appStatus.service = service
        if(!isNull(caf_status))  {
            appStatus.caf_status = caf_status
        }
        return appStatus;

    },
    mapNoteData(user, leadId) {
        return {
            created_by: user.profile.first_name,
            user_role: user.roles[0],
            type: 'regular',
            'moving_utility_data_id': leadId
        }

    },
    mapApplicationNote : (data) => {
        return {
            application_note : mapApplicationNote(data),
            status_log : mapApplicationNote(data, false)
        }
    }
}
