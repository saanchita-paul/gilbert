import AppCloseReason from '@scripts/models/AppCloseReason';

export default {
    mapAppCloseReasonData: (closeReasons) => {

        let reasons = [];
        closeReasons.forEach(element=>{ reasons.push(new AppCloseReason(element)) })

        // console.log("mapper", reasons);

        return reasons;
    }
};
