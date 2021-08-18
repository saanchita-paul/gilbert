export default class User {
    constructor({id, email, name, roles, permissions, profile}) {
        this.name = name
        this.id = id
        this.email = email
        this.roles = roles
        this.permissions = permissions
        this.profile = profile
    }
}
