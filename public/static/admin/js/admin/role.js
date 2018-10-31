vm = new Vue({
    el: '.page-content',
    data: {
        random: ['label-success', 'label-warning', 'label-danger', 'label-info', 'label-purple', 'label-inverse', 'label-pink', 'label-yellow', 'label-grey', 'label-primary', 'label-light'],
        roleList: '',
        pageNo: 1,   // 当前页数
        pages: 0,    //  多少页
        permissionList:{},
        msg: {},
        msg: {'status': 1},
        editid: '',
        editmsg: {},
        delid: '',
        delkey: '',
        permissionkey: '',    // click 用户在列表中的key
        waitRoleId:'',  // 分配角色用户id
        hasPermission: {},   //指定用户的角色 列表
        nohasPermission: {}  // 用户没有的角色列表
    },
    methods: {
        getRoleList: function () {
            this.$http.post(ajaxUrl.getRoleList, {
                current_page: this.pageNo
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.roleList = res.data.data['list'];
                this.pages = res.data.data['count'];
                this.permissionList = res.data.data['permissionList'];
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        addRole: function () {
            this.msg['status'] = Number(this.msg['status']);
            this.$http.post(ajaxUrl.addRole, {
                'msg': JSON.stringify(this.msg)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.msg.id = res.data.data;
                this.roleList.push(this.msg);
                this.msg = {};
                this.msg.status = 1;
                this.getRoleList();
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        editRoleid: function (id, key) {
            this.editid = id;
            this.editmsg = this.roleList[key];
        },
        editRole: function () {
            this.editmsg['status'] = Number(this.editmsg['status']);
            this.$http.post(ajaxUrl.editRole, {
                msg: JSON.stringify(this.editmsg)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    this.getRoleList();
                    return false;
                }
                this.editmsg = {};
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        delTmp: function (id, key) {
            this.delid = id;
            this.delkey = key;
        },
        delRole: function (id) {
            this.$http.post(ajaxUrl.delRole, {
                id: id
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.roleList = this.roleList.filter(function (item) {
                    return item.id != id;
                });
                this.deleteId = '';

            }, function (res) {
            });
        },
        pageList: function (curPage) {
            //根据当前页获取数据
            this.pageNo = curPage;
            this.getRoleList(this.pageNo);
            // console.log("当前页：" + this.pageNo);
        },
        roleTmp: function (key) {   //
            this.permissionkey = key;
            this.waitRoleId  = this.roleList[key]['id'];
            var tmprole = this.hasPermission = this.roleList[key]['permission'];
            var tmpall = this.permissionList;

            this.nohasPermission = tmpall.filter(function (item) {
                return  JSON.stringify(tmprole).indexOf(JSON.stringify(item)) == -1;
            })
        },
        assignPermission:function (permission_id) {
            this.$http.post(ajaxUrl.assignPermission, {
                role_id: this.waitRoleId,
                permission_id:permission_id
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.hasPermission.push((this.permissionList.filter(function (item) {
                    return item.id == permission_id;
                }))[0]);
                console.log(this.hasPermission);
                var tmprole = this.hasPermission = this.roleList[this.permissionkey]['permission'];
                var tmpall = this.permissionList;
                this.nohasPermission = tmpall.filter(function (item) {
                    return JSON.stringify(tmprole).indexOf(JSON.stringify(item)) == -1;
                })
            }, function (res) {
                alert('程序崩掉了');
            });
        },
        deleteRolePermission:function (permission_id) {
            this.$http.post(ajaxUrl.deleteRolePermission, {
                role_id: this.waitRoleId,
                permission_id:permission_id
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.nohasPermission.push((this.permissionList.filter(function (item) {
                    return item.id == permission_id;
                }))[0]);
                var tmprole = this.nohasPermission ;
                var tmpall = this.permissionList;
                this.hasPermission = tmpall.filter(function (item) {
                    return JSON.stringify(tmprole).indexOf(JSON.stringify(item)) == -1;
                });
                this.roleList[this.permissionkey]['permission'] = this.hasPermission;
            }, function (res) {
                alert('程序崩掉了');
            });
        }
    },
    mounted: function () {
        this.$nextTick(function () {
            this.getRoleList();
        });
    }
});