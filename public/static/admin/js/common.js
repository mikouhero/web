var adminUrl = 'http://web.com/admin';
var ajaxUrl = {

    getMenuList:adminUrl+"/index/getMenu", //index
    getLogList:adminUrl+"/index/getList", //index

    getUserList:adminUrl+"/user/getList", //user
    addUser:adminUrl+"/user/insert",
    editUser:adminUrl+"/user/edit",
    delUser:adminUrl+"/user/delete",

    assignRole:adminUrl+"/user/assignRole",
    deleteUserRole:adminUrl+"/user/deleteRole",
    getRoleList:adminUrl+"/role/getList",       // role
    addRole:adminUrl+"/role/insert",
    editRole:adminUrl+"/role/edit",
    delRole:adminUrl+"/role/delete",

    assignPermission:adminUrl+"/role/assignPermission",
    deleteRolePermission:adminUrl+"/role/deletePermission",
    getPermissionList:adminUrl+"/permission/getList",   // permission
    addPermission:adminUrl+"/permission/insert",
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


    getIsp2List:adminUrl+"/isp2/getList",
    addIsp2:adminUrl+"/isp2/insert",
    editIsp2:adminUrl+"/isp2/update",         // 运营商
    delIsp2:adminUrl+"/isp2/delete",


    getBrjList:adminUrl+"/brj/getList",
    getBrjCode:adminUrl+"/brj/add",
    addBrj:adminUrl+"/brj/insert",
    editBrj:adminUrl+"/brj/update",         // brj
    delBrj:adminUrl+"/brj/delete",


    getPrjList:adminUrl+"/prj/getList",
    getPrjCode:adminUrl+"/prj/add",
    addPrj:adminUrl+"/prj/insert",
    editPrj:adminUrl+"/prj/update",         // prj
    delPrj:adminUrl+"/prj/delete",
    uploadPrj:adminUrl+"/prj/updatefj",



    getBrjopList:adminUrl+"/brjop/getList",
    addBrjop:adminUrl+"/brjop/insert",
    editBrjop:adminUrl+"/brjop/update",         // brjop
    delBrjop:adminUrl+"/brjop/delete",

    getCrjList:adminUrl+"/crj/getList",
    getCrjCode:adminUrl+"/crj/add",
    addCrj:adminUrl+"/crj/insert",
    editCrj:adminUrl+"/crj/update",         // crj
    delCrj:adminUrl+"/crj/delete",

    getCrjbtList:adminUrl+"/crjbt/getList",
    getCrjbtCode:adminUrl+"/crjbt/add",
    addCrjbt:adminUrl+"/crjbt/insert",
    editCrjbt:adminUrl+"/crjbt/update",         // crjbt
    delCrjbt:adminUrl+"/crjbt/delete",



    getCrjopList:adminUrl+"/crjop/getList",
    addCrjop:adminUrl+"/crjop/insert",
    editCrjop:adminUrl+"/crjop/update",         // crjop
    delCrjop:adminUrl+"/crjop/delete",


    getCrjcodeList:adminUrl+"/crjcode/getList",
    addCrjcode:adminUrl+"/crjcode/insert",
    editCrjcode:adminUrl+"/crjcode/update",         // crjcode
    delCrjcode:adminUrl+"/crjcode/delete",


    getServicetypeList:adminUrl+"/servicetype/getList",
    addServicetype:adminUrl+"/servicetype/insert",
    editServicetype:adminUrl+"/servicetype/update",         //type
    delServicetype:adminUrl+"/servicetype/delete",

    getPersonList:adminUrl+"/person/getList",   // person
    editPerson:adminUrl+"/person/update",


    getLoginlogList:adminUrl+"/loginlog/getList",   // loginlog


    getNoteList:adminUrl+"/note/getList",   // note
    getDetail:adminUrl+"/note/add",
    sendNote:adminUrl+"/note/insert",




};