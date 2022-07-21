import ApplicationCafFile from "@scripts/models/caf/ApplicationCafFile";
import DayJS from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
import {capitalize, forEach, isNull} from "lodash-es";

const mapGilbertApplicationList =  data => {
    const values = [];
    data.data.forEach((item) => {
        values.push(item);
    });
    return values;
}

export default {
    mapGilbertApplicationList,
}
