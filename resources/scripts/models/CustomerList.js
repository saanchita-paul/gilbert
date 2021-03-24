export default class CustomerList {
    constructor({   customerName,
                    lastInteractionTime,
                    issueStatus,
                    connectionStatus,
                    sentiment,
                    location,
                    connectionDate,
                    profileLink,
                    messangerLink
                } = {}) {
        this.customerName = customerName || null;
        this.lastInteractionTime = lastInteractionTime || null;
        this.issueStatus = issueStatus || null;
        this.connectionStatus = connectionStatus || null;
        this.sentiment = sentiment || null;
        this.location = location || null;
        this.connectionDate = connectionDate || null;
        this.profileLink = profileLink || null;
        this.messangerLink = messangerLink || null;
    }
}
