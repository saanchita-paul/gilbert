import Job from "@scripts/models/Job";

export default class Order {
    constructor(
        {
            id,
            total,
            jobs
        } = {}
    ) {
        this.id = id || null;
        this.total = total || 0;
        this.jobs = jobs.map(job => new Job(job))
    }
}
