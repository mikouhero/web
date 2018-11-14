vm = new Vue({
    el: '.page-content',
    data: {
        pageNo: 1,   // 当前页数
        pages: 0,    //  多少页
        List: {},
        msg:{'status':1},

        editid: '',
        editmsg: {},
        delid: '',
        delkey: '',
        s_brj :'',
        s_type:'',
        s_cid:'',
        s_bid:'',
        s_speed:'',
        s_contact:'',
        s_phone:'',
        s_status:'',
        s_time:'',
        s_out:'',
        typeList :{},
        bus_status :[{id:1,name:'正常'},{id:2,name:'过期'},{id:3,name:'拆机'}],

        companyList:{},
        buildingList:{},
        saleList:{},
        brjCode:'',
        s_cname:'',

        myData:[],
        t1:'',
        now:-1,
        show:true,

        myData1:[],
        t11:'',
        now1:-1,
        show1:true,

    },
    methods: {
        getList: function () {
            this.$http.post(ajaxUrl.getBrjList, {
                current_page: this.pageNo,
                s_brj:this.s_brj,
                s_type:this.s_type,
                s_cid:this.s_cid,
                s_bid:this.s_bid,
                s_speed:this.s_speed,
                s_contact:this.s_contact,
                s_phone:this.s_phone,
                s_status:this.s_status,
                s_time:this.s_time,
                s_out:this.s_out,

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
                this.saleList = res.data.data['saleList'];
                this.pages = res.data.data['count'];
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        getCompany(ev){
            this.show = true;

            if(ev.keyCode==38 || ev.keyCode==40)return;

            // if(ev.keyCode==13){
            //     window.open('https://www.baidu.com/s?wd='+this.t1);
            //     this.t1='';
            // }

            this.$http.post(ajaxUrl.getBrjCompany,{
                s_cname:this.t1
            },{
                emulateJSON: true
            }).then(function(res){
                this.myData=res.data.data;
            },function(){

            });
        },
        changeDown:function(){
            this.now++;
            if(this.now==this.myData.length)this.now=-1;
            if(this.now!=-1) {
                this.t1 = this.myData[this.now].name;
                this.s_cid = this.myData[this.now].id;
            }
        },
        changeUp:function(){
            this.now--;
            if(this.now==-2)this.now=this.myData.length-1;
            if(this.now !=-1){
                this.t1=this.myData[this.now].name;
                this.s_cid=this.myData[this.now].id;
            }
        },
        ulisshow:function () {
          this.show = false;
        },

        getCompany1(ev){
            this.show1 = true;

            if(ev.keyCode==38 || ev.keyCode==40)return;

            // if(ev.keyCode==13){
            //     window.open('https://www.baidu.com/s?wd='+this.t1);
            //     this.t1='';
            // }

            this.$http.post(ajaxUrl.getBrjCompany,{
                s_cname:this.t11
            },{
                emulateJSON: true
            }).then(function(res){
                this.myData1=res.data.data;
            },function(){

            });
        },
        changeDown1:function(){
            this.now1++;
            if(this.now1==this.myData1.length)this.now1=-1;
            if(this.now1!=-1) {
                this.t11 = this.myData1[this.now1].name;
                this.msg.cid = this.myData1[this.now1].id;
            }
        },
        changeUp1:function(){
            this.now1--;
            if(this.now1==-2)this.now1=this.myData1.length-1;
            if(this.now1 !=-1){
                this.t11=this.myData1[this.now1].name;
                this.msg.cid=this.myData1[this.now1].id;
            }
        },
        ulisshow1:function () {
            this.show1 = false;
        },

        getCode:function () {
            this.$http.post(ajaxUrl.getBrjCode, {
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.brjCode = res.data.data;

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

            this.$http.post(ajaxUrl.addBrj, {
                'msg': JSON.stringify(this.msg)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
               this.msg={'status':1};
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
            this.$http.post(ajaxUrl.editBrj, {
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
            this.$http.post(ajaxUrl.delBrj, {
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


