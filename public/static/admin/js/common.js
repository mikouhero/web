var adminUrl = 'http://web.com/admin';
var ajaxUrl = {

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
    addMember:adminUrl+"/member/insert",      //客户
    editMember:adminUrl+"/member/update",
    delMember:adminUrl+"/member/delete",

    getCompanyList:adminUrl+"/company/getList",
    addCompany:adminUrl+"/company/insert",   // 公司
    editCompany:adminUrl+"/company/update",
    delCompany:adminUrl+"/company/delete",

    getBuildingList:adminUrl+"/building/getList",
    addBuilding:adminUrl+"/building/insert",
    editBuilding:adminUrl+"/building/update",   // 楼
    delBuilding:adminUrl+"/building/delete",

    getIspList:adminUrl+"/isp/getList",
    addIsp:adminUrl+"/isp/insert",
    editIsp:adminUrl+"/isp/update",         // 运营商
    delIsp:adminUrl+"/isp/delete",


    getBrjList:adminUrl+"/brj/getList",
    addBrj:adminUrl+"/brj/insert",
    editBrj:adminUrl+"/brj/update",         // brj
    delBrj:adminUrl+"/brj/delete",

    getServicetypeList:adminUrl+"/servicetype/getList",
    addServicetype:adminUrl+"/servicetype/insert",
    editServicetype:adminUrl+"/servicetype/update",         //type
    delServicetype:adminUrl+"/servicetype/delete",

    getPersonList:adminUrl+"/person/getList",
    editPerson:adminUrl+"/person/update",

};