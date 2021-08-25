import CrmUser from "@scripts/models/crm/CrmUser";
import PaginationMapper from "@scripts/api/mappers/crm/PaginationMapper";
import UserRoles from "@scripts/data/UserRoles";

function mapUser(user) {
    return new CrmUser({...user});
}

function mapRole(role) {
    const roles = [...UserRoles.HOOD, ...UserRoles.AGENCY]
    return (roles.find((r) => r.value === role))?.text;
}

export default {
    mapUserList: (userList)=> {

        const users =  userList?.data.map(user=> {
            user.role = mapRole(user.user.roles[0]);
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
        console.log(user, "Uyo")
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
