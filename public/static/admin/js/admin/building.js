vm = new Vue({
    el: '.page-content',
    data: {
        pageNo: 1,   // 当前页数
        pages: 0,    //  多少页
        buildingList: {},
        msg: {},
        editid: '',
        editmsg: {},
        delid: '',
        delkey: '',
        s_name :'',

    },
    methods: {
        getBuildingList: function () {
            this.$http.post(ajaxUrl.getBuildingList, {
                current_page: this.pageNo,
                code:this.s_code,
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.buildingList = res.data.data['buildingList'];
                this.pages = res.data.data['count'];
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        addBuilding: function () {

            this.$http.post(ajaxUrl.addBuilding, {
                'msg': JSON.stringify(this.msg)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
               this.msg={};
               this.getBuildingList();

            }, function (res) {
                alert("程序崩掉了");
            });
        },

        editBuildingid: function (id, key) {
            this.editid = id;
            this.editmsg = this.buildingList[key];
        },
        editBuilding: function () {
            var pic = $('#result1').val();

            this.editmsg['newpic'] = pic;
            this.$http.post(ajaxUrl.editBuilding, {
                msg: JSON.stringify(this.editmsg)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    this.getBuildingList();
                    return false;
                }
                this.getBuildingList();

                this.editmsg = {};
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        delTmp: function (id, key) {
            this.delid = id;
            this.delkey = key;
        },
        delBuilding: function (id) {
            this.$http.post(ajaxUrl.delBuilding, {
                id: id
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.buildingList = this.buildingList.filter(function (item) {
                    return item.id != id;
                });
                this.deleteId = '';

            }, function (res) {
            });
        },
        pageList: function (curPage) {
            //根据当前页获取数据
            this.pageNo = curPage;
            this.getBuildingList(this.pageNo);
            // console.log("当前页：" + this.pageNo);
        },

    },
    mounted: function () {
        this.$nextTick(function () {
            this.getBuildingList();
        });
    }
});


