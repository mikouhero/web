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
        speedList :[{id:1,name:'10M'},{id:2,name:'20M'},{id:3,name:'50M'},{id:4,name:'100M'},{id:5,name:'200M'}],
        bus_status :[{id:1,name:'潜在'},{id:2,name:'正式'},{id:3,name:'过期'}],
        companyList:{},
        buildingList:{},
        prjCode:'',
        upath: '',
        result: '',
        uping:0
    },
    methods: {
        getList: function () {
            this.$http.post(ajaxUrl.getPrjList, {
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
        getCode:function () {
            this.$http.post(ajaxUrl.getPrjCode, {
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.prjCode = res.data.data;

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

            this.$http.post(ajaxUrl.addPrj, {
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
            this.$http.post(ajaxUrl.editPrj, {
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
            this.$http.post(ajaxUrl.delPrj, {
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
        upload: function () {
            //console.log(this.upath);
            var zipFormData = new FormData();
            zipFormData.append('fj', this.upath);//filename是键，file是值，就是要传的文件，test.zip是要传的文件名
            console.log(zipFormData);
            let config = { headers: { 'Content-Type': 'multipart/form-data' } };
            this.$http.post(ajaxUrl.uploadPrj, zipFormData,config).then(function (response) {
                // console.log(response);
                // console.log(response.data);
                // console.log(response.bodyText);

                // var resultobj = response.data;
                // this.uping = 0;
                // this.result = resultobj.msg;
            });
        },

        getFile: function (even) {
            this.upath = event.target.files[0];
        },


    },
    mounted: function () {
        this.$nextTick(function () {
            this.getList();
        });
    }
});


