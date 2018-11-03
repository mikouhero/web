vm = new Vue({
    el: '.page-content',
    data: {
        pageNo: 1,   // 当前页数
        pages: 0,    //  多少页
        List: {},
        msg: {},
        editid: '',
        editmsg: {},
        delid: '',
        delkey: '',
        s_prj :'',
        s_cid:'',
        s_prj_user:'',
        s_prj_manger:'',
        typeList :{},
        companyList:{},
        buildingList:{},
        prjCode:'',
        upath: '',
        fjid:'',
    },
    methods: {
        getList: function () {
            this.$http.post(ajaxUrl.getPrjpayList, {
                current_page: this.pageNo,
                s_prj:this.s_prj,
                s_cid:this.s_cid,
                s_prj_user:this.s_prj_user,
                s_prj_manger:this.s_prj_manger,

            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.List = res.data.data['list'];
                this.typeList = res.data.data['typeList'];
                this.companyList = res.data.data['companyList'];
                this.buildingList = res.data.data['buildingList'];
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


