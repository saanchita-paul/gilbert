import {isNull} from "lodash-es";

const mapDuplicateLeadList = (data) => {
    const values = [];
    data.forEach((item) => {
        values.push(mapDuplicateLead(item));
    });

    return values;
}

const mapDuplicateLead = (data) => {
    return {
        id: data.id,
        name: getFullName(data),
        mobile: getMobileNumber(data),
        source: data.source,
        connection_address: data.connection_address,
        email: data.email
    };
}

const getFullName = (data) => {
    return isNull(data.middle_name) ? data.first_name + ' ' + data.last_name
        : data.first_name + ' ' + data.middle_name + ' ' + data.last_name;
}

const getMobileNumber = (data) => {
    // do something
}

export default {
    mapDuplicateLeadList
};
