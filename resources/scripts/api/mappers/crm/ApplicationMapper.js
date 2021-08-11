import Application from "@scripts/models/crm/Application";
import ApplicationSummary from "@scripts/models/crm/ApplicationSummary";
import Plan from "@scripts/models/crm/Plan";
import Note from "@scripts/models/crm/Note";
import ServiceProvider from "@scripts/models/crm/ServiceProvider";
import COMMISSION from "@scripts/data/constants/COMMISSION";
export default {
    mapApplication(data) {
        let model = Object.assign(new Application(), { ...data });
        return model;
    },
    mapApplicationList(data) {
        const models = [];
        data.forEach((item) => {
            models.push(this.mapApplication(item));
        });
        return models;
    },
    mapApplicationSummary(data) {
        let model = Object.assign(new ApplicationSummary(), { ...data });
        return model;
    },

    mapPlans(data) {
        return data.map(dt=> {
            return Object.assign(new Plan(), { ...dt });
        });
    },

    mapNotes(data) {
        return data.map(dt=> {
            return Object.assign(new Note(), { ...dt });
        });
    },
    mapNote(data) {
        return Object.assign(new Note(), { ...data });
    },

    mapServiceProvider(data) {
        return data.map(dt=> {
            return Object.assign(new ServiceProvider(), { ...dt });
        });
    },

    mapToServer(data) {
        let commsission = [];
        data.service_interests.forEach(service => {
            if(service === COMMISSION.GAS.text)
            {
                commsission.push({
                    service_type: COMMISSION.GAS.type
                })
            }
            if(service === COMMISSION.INTERNET.text)
            {
                commsission.push({
                    service_type: COMMISSION.GAS.type
                })
            } if(service === COMMISSION.WATER.text)
            {
                commsission.push({
                    service_type: COMMISSION.WATER.type
                })
            } if(service === COMMISSION.POWER.text)
            {
                commsission.push({
                    service_type: COMMISSION.POWER.type
                })
            }


        });
        data.service_interests = commsission;
       return {
           ...data,
           dob: data.date_of_birth,
           is_email_billing: data.email_billing
       }
    }
};
