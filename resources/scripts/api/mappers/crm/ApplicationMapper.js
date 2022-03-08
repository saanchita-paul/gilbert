import Application from "@scripts/models/crm/Application";
import ApplicationSummary from "@scripts/models/crm/ApplicationSummary";
import Plan from "@scripts/models/crm/Plan";
import Note from "@scripts/models/crm/Note";
import ServiceProvider from "@scripts/models/crm/ServiceProvider";
import COMMISSION from "@scripts/data/constants/COMMISSION";
import PaginationMapper from "@scripts/api/mappers/crm/PaginationMapper";
import DayJS from "dayjs";
import dayjs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
import IDENTIFICATION from "@scripts/data/constants/IDENTIFICATION";
import {isNull} from "lodash-es";
import {getApplicationStatusText} from "../../../data/ConnectionApplicationStatuses";
import AuthService from "../../../services/AuthService";

export default {
    mapApplication(data) {
        let model = Object.assign(new Application(), { ...data });
        model.status = this.mapStatus(model.status);
        model.moving_date = new DayJS(model.moving_date).format(DATE_FORMAT.DB_DATE);
        if(isNull(data.created_by_agent))
        {
            model.created_by = '';
        } else {
            model.created_by = (data.created_by_agent?.first_name + ' ' + data.created_by_agent?.last_name);
        }
        return model;
    },

    mapStatus(status)
    {
        const office = AuthService.getUserOffice()
        return getApplicationStatusText(status, !!office)
    },

    mapApplicationList(data) {
        // console.log('data',data);
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

        let model = new ApplicationSummary({...data});
        return model;
    },

    mapPlans(data) {
        return data.map(dt=> {
            return Object.assign(new Plan(), { ...dt });
        });
    },

    mapNotes(data) {
        let notes;

        notes =  data.map(dt=> {
              return  new Note({...dt});
        });

        if(notes.length > 0) {
            notes[0].active = true;
        }

        return notes;
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

        // console.log(data);
        let commission = [];
        data.application.service_interests.forEach(service => {
            if(service === COMMISSION.GAS.text)
            {
                commission.push({
                    service_type: COMMISSION.GAS.text
                })
            }
            if(service === COMMISSION.INTERNET.text)
            {
                commission.push({
                    service_type: COMMISSION.INTERNET.text
                })
            } if(service === COMMISSION.WATER.text)
            {
                commission.push({
                    service_type: COMMISSION.WATER.text
                })
            } if(service === COMMISSION.POWER.text)
            {
                commission.push({
                    service_type: COMMISSION.POWER.text
                })
            }


        });

        data.application.service_interests = commission;
       return {
           ...data.application,
           identification: this.mapIdentification(data.identification),
           dob: this.mapDateToServer(data.application.date_of_birth),
           moving_date: this.mapDateToServer(data.application.moving_date),
           connection_end_date: this.mapDateToServer(data.application.connection_end_date),
           is_email_billing: data.application.email_billing?data.application.email_billing:0,
           authorized_person: {
               ...data.authorized_person,
               dob: this.mapDateToServer(data.authorized_person.dob),
               expire_date : this.mapSecondaryContactIdExpire(data.authorized_person.identification_type, data.authorized_person.expire_date)
           }

       }
    },

    mapIdentification(identification) {
        if(identification.type === IDENTIFICATION.PASSPORT)
        {
            return {
                type: identification.type,
                card_number: identification.card_number,
                expire_date: this.mapDateToServer(identification.expire_date),
                country: identification.country,
            };
        }
        if(identification.type === IDENTIFICATION.MEDICARE)
        {
            return {
                type: identification.type,
                card_number: identification.card_number,
                expire_date: this.mapMadecareDateToServer(identification.expire_date),
                special_number: identification.special_number,
                card_color: identification.card_color,
            };
        }
        if(identification.type === IDENTIFICATION.DL)
        {
            return {
                type: identification.type,
                card_number: identification.card_number,
                expire_date: this.mapDateToServer(identification.expire_date),
                state: identification.state,
            };
        }
        return '';
    },

    mapSecondaryContactIdExpire(type, expire_date)
    {


        let mappedDate = null;
        switch (type) {
            case IDENTIFICATION.PASSPORT:
            case IDENTIFICATION.DL:
                mappedDate = this.mapDateToServer(expire_date);
                break;
            case IDENTIFICATION.MEDICARE:
                mappedDate = this.mapMadecareDateToServer(expire_date);
                break;
            default:
                break;
        }
        console.log(type, expire_date, mappedDate);
        return mappedDate;

    },

    mapDateToServer(dt) {
        let dateCheck =  dayjs(dt,'DD/MM/YYYY').format('YYYY-MM-DD');
        return dayjs(dateCheck).isValid() ? dateCheck : null;
    },

    mapMadecareDateToServer(dt, isDatabaseFormat = true) {
        if (dt === null) {
            return null;
        }
        let siltedData = dt.split('/');
        let fullMonthYear = siltedData[0] + '/' + '20' + siltedData[1];
        let fullDateMonthYear = dayjs(fullMonthYear, 'MM/YYYY').daysInMonth() + '/' + fullMonthYear;
        if(isDatabaseFormat) return dayjs(fullDateMonthYear,'DD/MM/YYYY').format('YYYY-MM-DD');
        return fullDateMonthYear;
    }

};
