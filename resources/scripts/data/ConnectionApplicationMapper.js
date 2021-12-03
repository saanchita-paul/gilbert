export const emailBillingMapper = {
    EMAIL_BILLING_EMAIL  : 1,
    EMAIL_BILLING_PAPER  : 2,  
}

// export const connectionApplicationMapper = {
//     STATUS_UNASSIGNED    : 1,
//     STATUS_ASSIGNED      : 2,
//     STATUS_ESCALATED     : 3,
//     STATUS_SUBMITTED     : 4,
//     STATUS_ACCEPTED      : 5,
//     STATUS_REJECTED      : 6, //non payable
//     STATUS_EA_PROCESSINF : 7,
//     STATUS_CLOSED        : 8,
// }


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

export const tenancyTypeMapper = {
    Renter    : 1,
    HomeOwner : 2,
}