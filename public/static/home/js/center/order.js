vm = new Vue({
    el: '.vipRight',
    data: {
        name:'',
        pwd:''
    },
    methods: {
        login: function () {
            this.$http.post("/index/person/signin", {
                user:this.name,
                password:this.pwd,
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                location.href="/index/center/index"
            }, function (res) {
                alert("程序崩掉了");
            });
        },

    },

});

