import AppCloseReason from '@scripts/models/AppCloseReason';

export default {
    mapAppCloseReasonData: (closeReasons) => {
        return new AppCloseReason(closeReasons);
    }
};
