export default class Customer {
    constructor({
                    id,
                    name,
                    profilePic,
                    lastInteractiveTime,
                    hoodUid,
                    messagerId,
                    email,
                    ph,
                    avatar,
                    issueStatus,
                    connectionStatus,
                    sentiment,
                    location,
                    connectionDate
                } = {}) {
        this.id = id || null;
        this.name = name || 'Sazzad';
        this.profile_pic = profilePic || 'https://fiverr-res.cloudinary.com/images/q_auto,f_auto/gigs/106438752/original/3c4d95e3604313ecca407541a45b6a58dcc67c5c/update-your-online-dating-profile-bio-to-get-you-more-matches.jpg';
        this.last_interactive_time = lastInteractiveTime || '10m';
        this.hood_uid = hoodUid || '#1671408219574925';
        this.messager_id = messagerId || '#1671408219574925';
        this.email = email || 'sazzadahmed41@gmail.com';
        this.ph = ph || '1671408219574925';
        this.avatar = avatar || null;
        this.issue_status = issueStatus || null;
        this.connection_status = connectionStatus || null;
        this.sentiment = sentiment || null;
        this.location = location || null;
        this.connection_date = connectionDate || null;

    }

}
