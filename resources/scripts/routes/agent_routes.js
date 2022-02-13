import AgentDashboardLayout from "@scripts/layouts/AgentDashboardLayout";
import AgentApplicationPage from "@scripts/pages/agent/AgentApplicationPage";
import AgentCreateNewApplication from "@scripts/components/crm/agent/AgentCreateNewApplication";

import {getAllAgentRoles} from "@scripts/data/UserRoles";

const all_agent_roles = getAllAgentRoles();

export default {
    path: '/agent',
    component: AgentDashboardLayout,
    name: 'agent',
    children: [
        {
            path: '',
            component: AgentApplicationPage,
            name: 'agent.application.dashboard',
            meta: {
                isProtected: true,
                roles: all_agent_roles
            }
        },
        {
            path: '/create-application',
            component: AgentCreateNewApplication,
            name: 'agent.create.application',
            meta: {
                isProtected: true,
                roles: all_agent_roles
            }
        },
    ]
}
