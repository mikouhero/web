vm = new Vue({
    el: '.vipRight',
    data: {
        msg:'',
        pageNo: 1,   // 当前页数
        pages: 0,    //  多少页
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
                alert('发送成功');
                this.msg = '';

            }, function (res) {
                alert("程序崩掉了");
            });
        },
        getList: function () {
            this.$http.post("/index/center/sendnote", {
                current_page: this.pageNo,
                msg:this.msg,

            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                alert('发送成功');
                this.msg = '';

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

});

