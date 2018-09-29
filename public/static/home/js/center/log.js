vm = new Vue({
    el: '.vipRight',
    data: {
        msg:{},
        pageNo: 1,   // 当前页数
        pages: 0,    //  多少页
    },
    methods: {
        getList: function () {
            this.$http.post("/index/center/getlog", {
                current_page: this.pageNo,
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    // location.href="/index/person/login"
                    return false;
                }
                this.msg = res.data.data['list'];
                this.pages = res.data.data['count'];
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

