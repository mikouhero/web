vm = new Vue({
    el: '.page-content',
    data: {
        permissionList: '',
        pageNo: 1,   // 当前页数
        pages: 0,    //  多少页
        msg: {},
        msg: {'status': 1},
        editid: '',
        editmsg: {},
        delid: '',
        delkey: '',
    },
    methods: {
        getPermissionList: function () {
            this.$http.post(ajaxUrl.getPermissionList, {
                current_page: this.pageNo
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.permissionList = res.data.data['list'];
                this.pages = res.data.data['count'];
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        addPermission: function () {
            this.msg['status'] = Number(this.msg['status']);
            this.$http.post(ajaxUrl.addPermission, {
                'msg': JSON.stringify(this.msg)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.msg.id = res.data.data;
                this.permissionList.push(this.msg);
                this.msg = {};
                this.msg.status = 1;

            }, function (res) {
                alert("程序崩掉了");
            });
        },
        editPermissionid: function (id, key) {
            this.editid = id;
            this.editmsg = this.permissionList[key];
        },
        editPermission: function () {
            this.editmsg['status'] = Number(this.editmsg['status']);
            this.$http.post(ajaxUrl.editPermission, {
                msg: JSON.stringify(this.editmsg)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    this.getPermissionList();
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
        delPermission: function (id) {
            this.$http.post(ajaxUrl.delPermission, {
                id: id
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.permissionList = this.permissionList.filter(function (item) {
                    return item.id != id;
                });
                this.deleteId = '';

            }, function (res) {
            });
        },
        pageList: function (curPage) {
            //根据当前页获取数据
            this.pageNo = curPage;
            this.getPermissionList(this.pageNo);
            // console.log("当前页：" + this.pageNo);
        },
    },
    mounted: function () {
        this.$nextTick(function () {
            this.getPermissionList();
        });
    }
});