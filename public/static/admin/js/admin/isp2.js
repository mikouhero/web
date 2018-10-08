vm = new Vue({
    el: '.page-content',
    data: {
        pageNo: 1,   // 当前页数
        pages: 0,    //  多少页
        ispList: {},
        msg: {},
        editid: '',
        editmsg: {},
        delid: '',
        delkey: '',
        s_name :'',

    },
    methods: {
        getIspList: function () {
            this.$http.post(ajaxUrl.getIsp2List, {
                current_page: this.pageNo,
                name:this.s_name,
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.ispList = res.data.data['isplist'];
                this.pages = res.data.data['count'];
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        addIsp: function () {

            this.$http.post(ajaxUrl.addIsp2, {
                'msg': JSON.stringify(this.msg)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
               this.msg={};
               this.getIspList();

            }, function (res) {
                alert("程序崩掉了");
            });
        },

        editIspid: function (id, key) {
            this.editid = id;
            this.editmsg = this.ispList[key];
        },
        editIsp: function () {
            this.$http.post(ajaxUrl.editIsp2, {
                msg: JSON.stringify(this.editmsg)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    this.getIspList();
                    return false;
                }
                this.getIspList();

                this.editmsg = {};
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        delTmp: function (id, key) {
            this.delid = id;
            this.delkey = key;
        },
        delIsp: function (id) {
            this.$http.post(ajaxUrl.delIsp2, {
                id: id
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.ispList = this.ispList.filter(function (item) {
                    return item.id != id;
                });
                this.deleteId = '';

            }, function (res) {
            });
        },
        pageList: function (curPage) {
            //根据当前页获取数据
            this.pageNo = curPage;
            this.getIspList(this.pageNo);
            // console.log("当前页：" + this.pageNo);
        },

    },
    mounted: function () {
        this.$nextTick(function () {
            this.getIspList();
        });
    }
});


