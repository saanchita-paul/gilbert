import {isNull} from "lodash-es";
import {leadSourceMapFromNumber} from '@scripts/data/LeadSourceMap';
import dayJs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";

const mapDuplicateLeadList = (data) => {
    const values = [];
    data.data.forEach((item) => {
        values.push(mapDuplicateLead(item));
    });

    return values;
}

const mapDuplicateLead = (data) => {
    return {
        id: data?.id,
        name: getFullName(data),
        mobile: getMobileNumber(data),
        source: leadSourceMapFromNumber[data?.source],
        connection_address: data?.address_text,
        email: data?.email,
        moving_date: dayJs(data.moving_date).format(DATE_FORMAT.DB_DATE),
        submitted_at: data.submitted_at !== ''? dayJs(data.submitted_at).format(DATE_FORMAT.DB_DATE): '',
        created_at: dayJs(data.created_at).format(DATE_FORMAT.DB_DATE),
        assigned_to: data.assigned_to,
        agent_profile: data.agent_profile,
    };
}

const getFullName = (data) => {
    return isNull(data?.middle_name) ? data?.first_name + ' ' + data?.last_name
        : data?.first_name + ' ' + data?.middle_name + ' ' + data?.last_name;
}

const getMobileNumber = (data) => {
    return data?.phone_type === 1 ? data?.phone
        : data?.homephone;
}

export default {
    mapDuplicateLeadList
};
