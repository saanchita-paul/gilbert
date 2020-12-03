export default class Job {
    constructor(
        {
            id,
            workers,
            vehicle,
            loading_elevator,
            unloading_elevator,
            service_type,
            loading_hours,
            unloading_hours,
            loading_helpers,
            unloading_helpers
        } = {}
    ) {
        this.id = id || null;
        this.workers = workers || 0;
        this.vehicle = vehicle || null;
        this.loading_elevator = loading_elevator || false;
        this.unloading_elevator = unloading_elevator || false;
        this.service_type = service_type || null;
        this.loading_hours = loading_hours || 0;
        this.unloading_hours = unloading_hours || 0;
        this.loading_helpers = loading_helpers || 0;
        this.unloading_helpers = unloading_helpers || 0;
    }
}
