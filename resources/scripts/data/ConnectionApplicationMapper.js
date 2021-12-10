export const emailBillingMapper = {
    EMAIL_BILLING_EMAIL  : 1,
    EMAIL_BILLING_PAPER  : 2,
}


export const connectionApplicationMapper = {
    '1' : 'STATUS_UNASSIGNED',
    '2' : 'STATUS_ASSIGNED',
    '3' : 'STATUS_ESCALATED',
    '4' : 'STATUS_SUBMITTED',
    '5' : 'STATUS_ACCEPTED',
    '6' : 'STATUS_REJECTED', //non payable
    '7' : 'STATUS_EA_PROCESSINF',
    '8' : 'STATUS_CLOSED',
}


export const connectionServicesMapper = {
     'STATUS_UNASSIGNED'      : 1,
     'STATUS_ASSIGNED'        : 2,
     'STATUS_ESCALATED'       : 3,
     'STATUS_SUBMITTED'       : 4,
     'STATUS_ACCEPTED'        : 5,
     'STATUS_REJECTED'        : 6, //non payable
     'STATUS_EA_SUBMIT'       : 12,
     'STATUS_IN_PROGRESS'   : 7,
     'STATUS_CLOSED'          : 8,
     'STATUS_CANT_CONNECT'    : 9,
     'STATUS_NEEDS_MORE_INFO' : 10,
     'STATUS_AC_MANUAL_PROCESSING'   : 11,
}

export const STATUSES_FOR_ENERGY_SUBMIT = [
    connectionServicesMapper.STATUS_ASSIGNED,
    connectionServicesMapper.STATUS_ASSIGNED,
    connectionServicesMapper.STATUS_UNASSIGNED,
    connectionServicesMapper.STATUS_CANT_CONNECT,
    connectionServicesMapper.STATUS_REJECTED,
    connectionServicesMapper.STATUS_IN_PROGRESS,
]

export const STATUSES_FOR_WATER_SUBMIT = [
    connectionServicesMapper.STATUS_IN_PROGRESS,
    connectionServicesMapper.STATUS_CANT_CONNECT,
]

