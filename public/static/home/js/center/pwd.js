vm = new Vue({
    el: '.vipRight',
    data: {
        old:'',
        newpwd:'',
        renew :''
    },
    methods: {
        update: function () {
            this.$http.post("/index/center/edit", {
                old:this.old,
                newpwd:this.newpwd,
                renew:this.renew,
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                alert('修改成功,请重新登录');
                location.href="/index/person/login"
            }, function (res) {
                alert("程序崩掉了");
            });
        },

    },

});

