import ReportAPI from "@scripts/api/ReportAPI";

export default {
    /**
     * @param officeId
     * @param reportType
     * @param dateRange
     * */
    officeReport: (officeId, reportType, dateRange) => ReportAPI.officeReport(officeId, reportType, dateRange)
}
