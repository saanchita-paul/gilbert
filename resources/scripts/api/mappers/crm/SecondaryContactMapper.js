import dayjs from "dayjs";
import ApplicationMapper from "@scripts/api/mappers/crm/ApplicationMapper";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
import IDENTIFICATION from "@scripts/data/constants/IDENTIFICATION";

export default {
    mapContactToServer(contact)
    {
        return {
            ...contact,
            dob:  dayjs(contact.dob,'DD/MM/YYYY').format('YYYY-MM-DD'),
            expire_date : ApplicationMapper.mapSecondaryContactIdExpire(contact.identification_type, contact.expire_date)
        }
    },

    mapServerData(contact) {
        return {
            ...contact,
            dob: dayjs(contact.dob).format(DATE_FORMAT.DB_DATE),
            role: contact.role === 1? 'Enquiry Only': contact.role === 2? 'Fully Authorised':contact.role === 3? 'Financially Responsible' : '',
            identification_type: contact.identification_type === IDENTIFICATION.PASSPORT ?
                'Passport' : contact.identification_type === IDENTIFICATION.MEDICARE ?
                    'Medicare' : contact.identification_type === IDENTIFICATION.DL? 'Driver\'s License': '',
            expire_date: contact.expire_date ? (contact.identification_type === IDENTIFICATION.MEDICARE ? dayjs(contact.expire_date).format('MM/YY') :
                dayjs(contact.expire_date).format(DATE_FORMAT.DB_DATE)) : null,
            card_title: contact.identification_type === IDENTIFICATION.PASSPORT ?
                'Passport' : contact.identification_type === IDENTIFICATION.MEDICARE ?
                    'Card' : contact.identification_type === IDENTIFICATION.DL? 'License': ''


        }
    }
}
