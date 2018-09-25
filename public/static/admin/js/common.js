var adminUrl = 'http://web.com/admin';
var ajaxUrl = {
    getMenuList: adminUrl + '/category/getMenuList',  // 菜单
    addMenu: adminUrl + '/category/addMenu',
    editMenu: adminUrl + '/category/editMenu',
    deleteMenu: adminUrl + '/category/deleteMenu',
    getTagList:adminUrl + '/tag/getTagList',           // 标签
    addTag: adminUrl + '/tag/addTag',
    banTag:adminUrl + "/tag/banTag",
    openTag:adminUrl + "/tag/openTag",
    addBlog:adminUrl + "/blog/insert",             // blog
    editBlog:adminUrl + "/blog/update",
    getBlogList:adminUrl+"/blog/getList",
    assignTag:adminUrl+"/blog/assignTag",
    deleteTag:adminUrl+"/blog/deleteTag",
    getUserList:adminUrl+"/user/getList", //user
    addUser:adminUrl+"/user/add",
    editUser:adminUrl+"/user/edit",
    delUser:adminUrl+"/user/delete",
    assignRole:adminUrl+"/user/assignRole",
    deleteUserRole:adminUrl+"/user/deleteUserRole",
    getRoleList:adminUrl+"/role/getList",       // role
    addRole:adminUrl+"/role/add",
    editRole:adminUrl+"/role/edit",
    delRole:adminUrl+"/role/delete",
    assignPermission:adminUrl+"/role/assignPermission",
    deleteRolePermission:adminUrl+"/role/deleteRolePermission",
    getPermissionList:adminUrl+"/permission/getList",   // permission
    addPermission:adminUrl+"/permission/add",
    editPermission:adminUrl+"/permission/edit",
    delPermission:adminUrl+"/permission/delete",
    login:adminUrl+"/login/dologin",
    getMemberList:adminUrl+"/member/getList",
    addMember:adminUrl+"/member/insert",
    editMember:adminUrl+"/member/update",
    delMember:adminUrl+"/member/delete",
    getCompanyList:adminUrl+"/company/getList",
    addCompany:adminUrl+"/company/insert",
    editCompany:adminUrl+"/company/update",
    delCompany:adminUrl+"/company/delete",
};