import CrmUser from "@scripts/models/crm/CrmUser";

function mapUser(user) {
    console.log('user', user);
    return new CrmUser({...user});
}

export default {
    mapUserList: (userList)=> {
        return  userList.map(user=> {
            return mapUser(user);
        })

    },
    mapUser: (user) => {
        console.log('user', user);
        return mapUser(user);
    }
}
