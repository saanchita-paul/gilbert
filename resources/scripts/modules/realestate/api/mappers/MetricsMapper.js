import SingleMetric from "@scripts/modules/realestate/models/SingleMetric";
import OfficeApplicationMetric from "@scripts/modules/realestate/models/OfficeApplicationMetric";
import OfficeUtilityMetrics from "@scripts/modules/realestate/models/OfficeUtilityMetrics";
import OfficeMetrics from "@scripts/modules/realestate/models/OfficeMetrics";

export default {
    mapMetrics(data) {
        let application_metrics = this.mapOffAppMetrics(data[0]);
        let utility_metrics = this.mapUitlityMetrics(data[0]);

        return (new OfficeMetrics({application_metrics, utility_metrics}));

    },

    mapOffAppMetrics(metricsData)
    {
        let app_created = new SingleMetric({
            title: 'Applications Created',
            value: metricsData['app_total']
        });

        let app_waiting_tenant = new SingleMetric({
            title: 'Waiting Tenant Confirmation',
            value: metricsData['app_unassigned']
        })

        let app_submitted_retialer = new SingleMetric({
            title: 'Submitted to Retailer',
            value: metricsData['app_submitted']
        })

        let app_successful = new SingleMetric({
            title: 'Successfully Connected',
            value: metricsData['app_connected']
        })
        let app_closed = new SingleMetric({
            title: 'Closed',
            value: metricsData['app_closed']
        })

        let officeAppMetrics = new OfficeApplicationMetric({
            app_created,
            app_waiting_tenant,
            app_submitted_retialer,
            app_successful,
            app_closed
        });

        return officeAppMetrics;

    },

    mapUitlityMetrics(metricsData)
    {
        let ele_metric = {
            submitted: metricsData['power_submitted'],
            connected: metricsData['power_connected']

        };

        let gas_metric = {
            submitted:  metricsData['gas_submitted'],
            connected: metricsData['gas_connected']
        };
        let internet_metric = {
            submitted: metricsData['internet_submitted'],
            connected: metricsData['internet_connected']

        };
        let utilityMetrics = new OfficeUtilityMetrics({
            ele_metric,
            gas_metric,
            internet_metric,
        });

        return utilityMetrics;
    }
}
