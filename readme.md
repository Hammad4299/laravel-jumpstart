1. Require "user.login" route. Can be changed within middleware if not present to something appropiate
2. For frontend rollbar, use jumpstart/frontend rollbar implementation



## Resonable Role Based resource schema suggestion
1. User
2. Roles (can have a single role row for super admin and have multiple rows for other e.g. (LEVEL::SUPER,'super',1),(LEVEL::MANAGER,'manager',2),(LEVEL::MANAGER,'manager',3)
    level
    id
    name
3. User Roles (user_id, role_id) e.g. (1,1),(1,2),(1,3) denotes that user 1 is super admin, has manager access to all resources with role id 2, has manager access to all resources with role id 3
4. RoleResource (role_id, resource_type, resource_id) polymorphic