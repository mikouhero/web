vm = new Vue({
    el: '.vipRight',
    data: {
        msg:{},
        pageNo: 1,   // 当前页数
        pages: 0,    //  多少页
        s_brj:'',
        s_address:'',
        s_speed:'',
        s_time:'',
        s_type:'',
        speedList :[{id:1,name:'10M'},{id:2,name:'20M'},{id:3,name:'50M'},{id:4,name:'100M'},{id:5,name:'200M'}],
        bus_status :[{id:1,name:'潜在'},{id:2,name:'正式'},{id:3,name:'过期'}],
        typelist:{}

    },
    methods: {
        getList: function () {
            this.$http.post("/index/center/getOrder", {
                current_page: this.pageNo,
                s_brj:this.s_brj,
                s_speed:this.s_speed,
                s_address:this.s_address,
                s_status:this.s_status,
                s_time:$('#s_time').val(),
                s_type:this.s_type
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.msg = res.data.data['list'];
                this.pages = res.data.data['count'];
                this.typelist = res.data.data['typelist'];
                
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

