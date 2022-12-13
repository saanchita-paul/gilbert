export const APP_STATUS = {
    0: 'not_submitted',
    1: 'unassigned',
    2: 'assigned',
    3: 'escalated',
    4: 'submitted',
    5: 'accepted',
    6: 'rejected',
    7: 'processing',
    8: 'closed',
};

export const SERVICE_STATUS = {
    0: 'not_submitted',
    1: 'unassigned',
    2: 'assigned',
    3: 'escalated',
    4: 'submitted',
    5: 'accepted',
    6: 'rejected',
    7: 'processing',
    8: 'processing',
    9: 'closed',
    10: "can't_connect",
    11: 'need_more_info',
    12: 'ac_manual_precessing',
    13: 'failed',
};

export const getAppStatus = (key) => APP_STATUS[Number(key)];

export const getServiceStatus = (key) => SERVICE_STATUS[Number(key)];


export default {
    APP_STATUS,
    SERVICE_STATUS,
    getAppStatus,
    getServiceStatus,
};
