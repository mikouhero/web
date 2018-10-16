vm = new Vue({
    el: '.page-content',
    data: {
        pageNo: 1,   // 当前页数
        pages: 0,    //  多少页
        List: {},
        delailList:{},
        topic:{},
        tmpid:'',
        msg:'',
    },
    methods: {
        getList: function () {
            this.$http.post(ajaxUrl.getNoteList, {
                current_page: this.pageNo,
                name:this.s_name,
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.List = res.data.data['list'];
                this.pages = res.data.data['count'];
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        getDetail: function (id) {

            this.tmpid = id;
            this.$http.post(ajaxUrl.getDetail, {
                id:id
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.delailList = res.data.data['list'];
                this.topic = res.data.data['topic'];
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        save:function () {
            this.$http.post(ajaxUrl.sendNote    , {
                id:this.tmpid,
                msg:this.msg,
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.getDetail(this.tmpid);
                this.msg ='';
            }, function (res) {
                alert("程序崩掉了");
            });
        },

        pageList: function (curPage) {
            //根据当前页获取数据
            this.pageNo = curPage;
            this.getList(this.pageNo);
            // console.log("当前页：" + this.pageNo);
        },

    },
    mounted: function () {
        this.$nextTick(function () {
            this.getList();
        });
    }
});


