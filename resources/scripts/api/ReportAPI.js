import axios from 'axios';

export default {
    /**
     *  downloading REA office pdf report 
     *
     */
    officeReport: async (officeId, reportType, dateRange) => {
        try {
            return await axios.get("/api/rea-extract/office-report", { params: { officeId, reportType, ...dateRange}});
        } catch (error) {
            return error.data;
        }
    }
}
