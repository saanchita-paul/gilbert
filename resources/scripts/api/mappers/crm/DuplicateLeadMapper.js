import {isNull} from "lodash-es";
import {leadSourceMapFromNumber} from '@scripts/data/LeadSourceMap';

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
        email: data?.email
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
