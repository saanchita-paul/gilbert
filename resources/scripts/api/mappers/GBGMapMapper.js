import GBGModel from "@scripts/models/GBGModel";

export default {
    mapAddressList(addressList) {
        let list = [];
        addressList.forEach(n=>{ list.push(new GBGModel(n).getApplicationAddress()) })
        return list;
    },
    mapSingleAddress(address) {
        return new GBGModel(address).getApplicationAddress();
    }

};

