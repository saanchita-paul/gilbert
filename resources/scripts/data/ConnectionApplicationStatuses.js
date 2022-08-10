const LEAD_STATUS_UNASSIGNED = 1;
const LEAD_STATUS_ASSIGNED = 2;
const LEAD_STATUS_ESCALATED = 3;
const LEAD_STATUS_SUBMITTED = 4;
const LEAD_STATUS_ACCEPTED = 5;
const LEAD_STATUS_REJECTED = 6; //non payable
const LEAD_STATUS_NOT_SUBMITTED = 7;
const LEAD_STATUS_CLOSED = 8;
const LEAD_STATUS_CONSENT_PENDING = 20;

const LEAD_STATUS_TO_TEXT = {
    [LEAD_STATUS_UNASSIGNED]: "Unassigned",
    [LEAD_STATUS_ASSIGNED]: "Assigned",
    [LEAD_STATUS_ESCALATED]: "Escalated",
    [LEAD_STATUS_SUBMITTED]: "In Progress",
    [LEAD_STATUS_ACCEPTED]: "Connected",
    [LEAD_STATUS_REJECTED]: "Rejected", //non payable
    [LEAD_STATUS_NOT_SUBMITTED]: "Not Submitted",
    [LEAD_STATUS_CLOSED]: "Closed",
    [LEAD_STATUS_CONSENT_PENDING]: "Consent Pending",
}


const AGENT_IN_PROGRESS_STATUSES = [
    LEAD_STATUS_UNASSIGNED,
    LEAD_STATUS_ASSIGNED,
    LEAD_STATUS_ESCALATED,
    LEAD_STATUS_NOT_SUBMITTED,
    LEAD_STATUS_SUBMITTED
]


/**
 *
 * @param {Number} status
 * @param {Boolean} agent
 */
export const getApplicationStatusText = (status, agent = false) => {
    if (agent && AGENT_IN_PROGRESS_STATUSES.includes(status)) {
        return 'In Progress'
    }

    return LEAD_STATUS_TO_TEXT[status] ?? ''
}

