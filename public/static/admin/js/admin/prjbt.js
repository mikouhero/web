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
        s_address:'',

    },
    methods: {
        getList: function () {
            this.$http.post(ajaxUrl.getPrjbtList, {
                current_page: this.pageNo,
                s_prj:this.s_prj,
                s_cid:this.s_cid,
                s_prj_user:this.s_prj_user,
                s_prj_manger:this.s_prj_manger,
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
                this.pages = res.data.data['count'];
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        getCode:function () {
            this.$http.post(ajaxUrl.getPrjbtCode, {
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

            this.$http.post(ajaxUrl.addPrjbt, {
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
            this.$http.post(ajaxUrl.editPrjbt, {
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
            this.$http.post(ajaxUrl.delPrjbt, {
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
        fjtmpid: function (id) {
            this.fjid = id;
        },

        upload: function () {
            //console.log(this.upath);
            var zipFormData = new FormData();
            zipFormData.append('fj', this.upath);//filename是键，file是值，就是要传的文件，test.zip是要传的文件名
            // console.log(zipFormData);
            let config = { headers: { 'Content-Type': 'multipart/form-data' ,'id':this.fjid} };
            this.$http.post(
                ajaxUrl.uploadPrjbt,
                zipFormData,
                config
            ).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    this.getList();
                    return false;
                }
                $('#fj').val('');
                this.getList();

                this.fjid = '';
            }, function (res) {
                alert("程序崩掉了");
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


