vm = new Vue({
    el: '.page-content',
    data: {
      user:{},
        msg:{}
    },
    methods: {
        getList: function () {
            this.$http.post(ajaxUrl.getPersonList, {

            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.user = res.data.data;

            }, function (res) {
                alert("程序崩掉了");
            });
        },
        edit: function () {
            this.$http.post(ajaxUrl.editPerson, {
                msg: JSON.stringify(this.msg)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                alert('修改成功，请重新登录');
                location.href = adminUrl + '/login';

            }, function (res) {
                alert("程序崩掉了");
            });
        },


    },
    mounted: function () {
        this.$nextTick(function () {
            this.getList();
        });
    }
});


