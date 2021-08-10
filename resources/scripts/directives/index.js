import Vue from 'vue';
import AuthService from "@scripts/services/AuthService";
import Permissions from "@scripts/directives/Permissions";
import Roles from "@scripts/directives/Roles";

Vue.use(Permissions, { hasUserPermissions: AuthService.hasUserPermissions });

Vue.use(Roles, { hasUserRoles: AuthService.hasUserRoles });
