vm = new Vue({
    el: '.vipRight',
    data: {
        msg:{}
    },
    methods: {
        getList: function () {
            this.$http.post("/index/center/getuser", {
                user:this.name,
                password:this.pwd,
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    // location.href="/index/person/login"
                    return false;
                }
                this.msg = res.data.data;
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

