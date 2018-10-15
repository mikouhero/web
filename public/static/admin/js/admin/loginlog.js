vm = new Vue({
    el: '.page-content',
    data: {
        pageNo: 1,   // 当前页数
        pages: 0,    //  多少页
        logList: {},
        msg: {},
        editid: '',
        editmsg: {},
        delid: '',
        delkey: '',
        s_name :'',
        add_id:'',
        account:'',
    },
    methods: {
        getList: function () {
            this.$http.post(ajaxUrl.getLoginlogList, {
                current_page: this.pageNo,
                name:this.s_name,
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.logList = res.data.data['logList'];
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


