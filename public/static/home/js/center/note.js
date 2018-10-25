vm = new Vue({
    el: '.vipRight',
    data: {
        msg:'',
        pageNo: 1,   // 当前页数
        pages: 0,    //  多少页
        List:{},
        delailList:{},
        topic:{},
        tmpid:'',
    },
    methods: {
        send: function () {
            this.$http.post("/index/center/sendnote", {
                msg:this.msg,
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.getList();
                this.msg = '';

            }, function (res) {
                alert("程序崩掉了");
            });
        },
        getList: function () {
            this.$http.post("/index/center/getnotelist", {
                current_page: this.pageNo,
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
            this.$http.post('/index/center/getDetail', {
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
            this.$http.post('/index/center/sendnew', {
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

