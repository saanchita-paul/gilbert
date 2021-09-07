import Application from "@scripts/models/crm/Application";
import ApplicationSummary from "@scripts/models/crm/ApplicationSummary";
import Plan from "@scripts/models/crm/Plan";
import Note from "@scripts/models/crm/Note";
import ServiceProvider from "@scripts/models/crm/ServiceProvider";
import COMMISSION from "@scripts/data/constants/COMMISSION";
import PaginationMapper from "@scripts/api/mappers/crm/PaginationMapper";
import DayJS from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
export default {
    mapApplication(data) {
        let model = Object.assign(new Application(), { ...data });
        model.status = this.mapStatus(model.status);
        model.moving_date = new DayJS(model.moving_date).format(DATE_FORMAT.DB_DATE);
        return model;
    },

    mapStatus(status)
    {
        status = status - 1;
        if(status < 0) return  '';
        const statusList = ['UnAssigned','Assigned', 'Escalated','Submitted', 'Accepted', 'Rejected'];
        return statusList[status];
    },

    mapApplicationList(data) {
        const models = [];
        data.data.forEach((item) => {
            models.push(this.mapApplication(item));
        });
        const pagination =  PaginationMapper.mapPagination(data?.meta);
        return  {
            applications: models,
            pagination: pagination,
        };
    },
    mapApplicationSummary(data) {
        // let model = Object.assign(new ApplicationSummary(), { ...data });
        let model = new ApplicationSummary({...data});
        return model;
    },

    mapPlans(data) {
        return data.map(dt=> {
            return Object.assign(new Plan(), { ...dt });
        });
    },

    mapNotes(data) {
        console.log(data);
        return data.map(dt=> {
              return  new Note({...dt});


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
                    service_type: COMMISSION.GAS.text
                })
            }
            if(service === COMMISSION.INTERNET.text)
            {
                commsission.push({
                    service_type: COMMISSION.GAS.text
                })
            } if(service === COMMISSION.WATER.text)
            {
                commsission.push({
                    service_type: COMMISSION.WATER.text
                })
            } if(service === COMMISSION.POWER.text)
            {
                commsission.push({
                    service_type: COMMISSION.POWER.text
                })
            }


        });
        data.service_interests = commsission;
       return {
           ...data,
           dob: data.date_of_birth,
           is_email_billing: data.email_billing?data.email_billing:0
       }
    }
};
