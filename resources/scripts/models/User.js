export default class User {
    constructor({id, email, name, roles, permissions, profile, profile_type}) {
        this.name = name
        this.id = id
        this.email = email
        this.roles = roles
        this.permissions = permissions
        this.profile = profile
        this.profile_type = profile_type
    }
}
