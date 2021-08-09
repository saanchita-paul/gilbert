import CrmUser from "@scripts/models/crm/CrmUser";

function mapUser(user) {
    return new CrmUser({...user});
}

export default {
    mapUserList: (userList)=> {
        return  userList.map(user=> {
            return mapUser(user);
        })

    },

    mapUser: (user) => {
        return mapUser(user);
    },

    mapuserToServer: (user, officeId) => {
        return {
            office_id :officeId,
            ...user

        };
    }

}
