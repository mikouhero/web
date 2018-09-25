vm = new Vue({
    el: '.page-content',
    data: {
        pageNo: 1,   // 当前页数
        pages: 0,    //  多少页
        companyList: {},
        msg: {},
        editid: '',
        editmsg: {},
        delid: '',
        delkey: '',
        s_cid :'',
        s_user:'',

    },
    methods: {
        getCompanyList: function () {
            this.$http.post(ajaxUrl.getCompanyList, {
                current_page: this.pageNo,
                user:this.s_user,
                cid:this.s_cid,
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.companyList = res.data.data['companyList'];
                this.pages = res.data.data['count'];
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        addCompany: function () {
            var pic = $('#result').val();
            this.msg['license_icon'] = pic;

            this.$http.post(ajaxUrl.addCompany, {
                'msg': JSON.stringify(this.msg)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
               this.getCompanyList();

            }, function (res) {
                alert("程序崩掉了");
            });
        },
        choosefile: function (name) {
            $("#" + name).hide().click();
        },
        base64: function (name1, name2, name3) {
            var file = $("#" + name1).get(0).files[0];
            //这里我们判断下类型如果不是图片就返回 去掉就可以上传任意文件
            if (!/image\/\w+/.test(file.type)) {
                alert("请确保文件为图像类型");
                return false;
            }
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function (e) {
                $("#" + name2).attr('src', this.result);
                $("#" + name2).attr('width', 300);
                $("#" + name3).html(this.result);
                // result.innerHTML = this.result;
                // img_area.innerHTML = '<div class="sitetip">图片img标签展示：</div><img src="'+this.result+'" alt=""/>';
            };
        },

        editUserid: function (id, key) {
            this.editid = id;
            this.editmsg = this.memberList[key];
        },
        editMember: function () {
            this.editmsg['status'] = Number(this.editmsg['status']);
            this.$http.post(ajaxUrl.editMember, {
                msg: JSON.stringify(this.editmsg)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    this.getCompanyList();
                    return false;
                }
                this.getCompanyList();

                this.editmsg = {};
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        delTmp: function (id, key) {
            this.delid = id;
            this.delkey = key;
        },
        delMember: function (id) {
            this.$http.post(ajaxUrl.delMember, {
                id: id
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.companyList = this.companyList.filter(function (item) {
                    return item.id != id;
                });
                this.deleteId = '';

            }, function (res) {
            });
        },
        pageList: function (curPage) {
            //根据当前页获取数据
            this.pageNo = curPage;
            this.getCompanyList(this.pageNo);
            // console.log("当前页：" + this.pageNo);
        },

    },
    mounted: function () {
        this.$nextTick(function () {
            this.getCompanyList();
        });
    }
});


