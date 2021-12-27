export default class User {
    /**
     *
     * @param {number} id
     * @param {string} email
     * @param {string} name
     * @param {Array} roles
     * @param {Array} permissions
     * @param {Object} profile
     * @param {string} profile_type
     */
    constructor({id, email, name, roles, permissions, profile, profile_type, is_active}) {
        this.name = name
        this.id = id
        this.email = email
        this.roles = roles
        this.permissions = permissions
        this.profile = profile
        this.profile_type = profile_type
        this.is_active = is_active
    }
}
