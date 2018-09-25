vm = new Vue({
    el: '.page-content',
    data: {
        memberList: '',
        pageNo: 1,   // 当前页数
        pages: 0,    //  多少页
        companyList: {},
        msg: {},
        msg: {'status': 1},
        editid: '',
        editmsg: {},
        delid: '',
        delkey: '',
        s_cid :'',
        s_user:'',

    },
    methods: {
        getMemberList: function () {
            this.$http.post(ajaxUrl.getMemberList, {
                current_page: this.pageNo,
                user:this.s_user,
                cid:this.s_cid,
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.memberList = res.data.data['memberList'];
                this.pages = res.data.data['count'];
                this.companyList = res.data.data['companyList'];
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        addMember: function () {
            this.msg['status'] = Number(this.msg['status']);
            this.$http.post(ajaxUrl.addMember, {
                'msg': JSON.stringify(this.msg)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
               this.getMemberList();

            }, function (res) {
                alert("程序崩掉了");
            });
        },
        editUserid: function (id, key) {
            this.editid = id;
            this.editmsg = this.memberList[key];
        },
        editMember: function () {
            this.editmsg['status'] = Number(this.editmsg['status']);
            this.$http.post(ajaxUrl.editMember, {
                msg: JSON.stringify(this.editmsg)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    this.getMemberList();
                    return false;
                }
                this.getMemberList();

                this.editmsg = {};
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        delTmp: function (id, key) {
            this.delid = id;
            this.delkey = key;
        },
        delMember: function (id) {
            this.$http.post(ajaxUrl.delMember, {
                id: id
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.memberList = this.memberList.filter(function (item) {
                    return item.id != id;
                });
                this.deleteId = '';

            }, function (res) {
            });
        },
        pageList: function (curPage) {
            //根据当前页获取数据
            this.pageNo = curPage;
            this.getMemberList(this.pageNo);
            // console.log("当前页：" + this.pageNo);
        },

    },
    mounted: function () {
        this.$nextTick(function () {
            this.getMemberList();
        });
    }
});


