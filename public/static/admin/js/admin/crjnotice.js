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
        s_crj :'',
        s_isp:'',
        s_cid:'',
        s_type:'',
        s_status:'',
        s_time:'',
        s_out:'',
        s_address:'',
        s_isp_manager:'',
        typeList :{},
        bus_status :[{id:0,name:'未支付'},{id:1,name:'已支付'}],
        threelist :{},
        methodlist :[{'id':1,'name':'月结'},{'id':2,'name':'季度结'},{'id':3,'name':'年结'}],
        companyList:{},
        buildingList:{},
        ispList:{},
        saleList:{},
        crjCode:'',
    },
    methods: {
        getList: function () {
            this.$http.post(ajaxUrl.getCrjnoticeList, {
                current_page: this.pageNo,
                s_crj:this.s_crj,
                s_isp:this.s_isp,
                s_cid:this.s_cid,
                s_status:this.s_status,
                s_time:this.s_time,
                s_out:this.s_out,
                s_type:this.s_type,
                s_isp_manager:this.s_isp_manager,
                s_address:this.s_address,

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
                this.ispList = res.data.data['ispList'];
                this.threelist = res.data.data['threeList'];
                this.saleList = res.data.data['saleList'];
                this.pages = res.data.data['count'];
            }, function (res) {
                alert("程序崩掉了");
            });
        },

        edittmpid: function (id, key) {
            this.editid = id;
            this.editmsg = this.List[key];
            $('#edit_start').val(this.List[key]['pay_time']);
            $('#edit_end').val(this.List[key]['pay_notice_time']);
        },
        edit: function () {
            var start = $('#edit_start').val();
            var end = $('#edit_end').val();
            this.editmsg['pay_time'] = start;
            this.editmsg['pay_notice_time'] = end;
            this.$http.post(ajaxUrl.editCrjnotice, {
                msg: JSON.stringify(this.editmsg)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    this.getList();
                    return false;
                }
                this.getList();

                this.editmsg = {};
            }, function (res) {
                alert("程序崩掉了");
            });
        },


        updatestatus: function (id) {
            this.$http.post(ajaxUrl.updatestatusCrjnotice, {
                id: id
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }

                this.getList();

            }, function (res) {
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


