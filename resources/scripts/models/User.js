export default class User {
    constructor({id, email, name, roles, permissions}) {
        this.name = name
        this.id = id
        this.email = email
        this.roles = roles
        this.permissions = permissions
    }
}
