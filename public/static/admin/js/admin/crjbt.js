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
        s_isp_manager:'',
        typeList :{},
        bus_status :[{id:1,name:'正常'},{id:2,name:'过期'}],
        threelist :{},
        methodlist :[{'id':1,'name':'月结'},{'id':2,'name':'季度结'},{'id':3,'name':'年结'}],
        companyList:{},
        buildingList:{},
        ispList:{},
        crjCode:'',
    },
    methods: {
        getList: function () {
            this.$http.post(ajaxUrl.getCrjbtList, {
                current_page: this.pageNo,
                s_crj:this.s_crj,
                s_isp:this.s_isp,
                s_cid:this.s_cid,
                s_status:this.s_status,
                s_time:this.s_time,
                s_out:this.s_out,
                s_type:this.s_type,
                s_isp_manager:this.s_isp_manager,

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

                this.pages = res.data.data['count'];
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        add: function () {
            var start = $('#add_start').val();
            var end = $('#add_end').val();
            var teardown = $('#add_teardowm').val();
            this.msg['start_time'] = start;
            this.msg['end_time'] = end;
            this.msg['teardown'] = teardown;

            this.$http.post(ajaxUrl.addCrjbt, {
                'msg': JSON.stringify(this.msg)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
               this.msg={};
               this.getList();

            }, function (res) {
                alert("程序崩掉了");
            });
        },
        getCode:function () {
            this.$http.post(ajaxUrl.getCrjbtCode, {
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.crjCode = res.data.data;

            }, function (res) {
                alert("程序崩掉了");
            });
        },
        edittmpid: function (id, key) {
            this.editid = id;
            this.editmsg = this.List[key];
            $('#edit_start').val(this.List[key]['start_time']);
            $('#edit_end').val(this.List[key]['end_time']);
            $('#edit_teardowm').val(this.List[key]['teardown']);
        },
        edit: function () {
            var start = $('#edit_start').val();
            var end = $('#edit_end').val();
            var teardown = $('#edit_teardowm').val();
            this.editmsg['start_time'] = start;
            this.editmsg['end_time'] = end;
            this.editmsg['teardown'] = teardown;
            console.log(this.editmsg);
            this.$http.post(ajaxUrl.editCrjbt, {
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

        delTmp: function (id, key) {
            this.delid = id;
            this.delkey = key;
        },
        del: function (id) {
            this.$http.post(ajaxUrl.delCrjbt, {
                id: id
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.List = this.List.filter(function (item) {
                    return item.id != id;
                });
                this.deleteId = '';

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


