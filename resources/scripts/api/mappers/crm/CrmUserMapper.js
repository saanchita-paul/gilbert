import CrmUser from "@scripts/models/crm/CrmUser";
import PaginationMapper from "@scripts/api/mappers/crm/PaginationMapper";

function mapUser(user) {
    return new CrmUser({...user});
}

export default {
    // mapUserList: (userList)=> {
    //     return  userList.map(user=> {
    //         return mapUser(user);
    //     })
    //
    // },

    mapUserList: (userList)=> {

        const users =  userList?.data.map(user=> {
            return mapUser(user);
        });

        const pagination =  PaginationMapper.mapPagination(userList?.meta);

        return {
            users: users,
            pagination: pagination,
        };
    },

    mapUser: (user) => {
        return mapUser(user);
    },

    mapuserToServer: (user, officeId) => {

        return  {
            office_id :officeId,
            ...user,
            role: user.job_title
        };


    },
    mapMetaData: (meta) => {
        if(meta.sort_by === 'proerty_manager_name') meta.sort_by = 'first_name';
        if(meta.sort_by === 'submitted_lead') meta.sort_by = 'first_name';
        if(meta.sort_by === 'role') meta.sort_by = 'first_name';
        return meta;
    }

}
