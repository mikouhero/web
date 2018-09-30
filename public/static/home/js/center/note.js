vm = new Vue({
    el: '.vipRight',
    data: {
        msg:''
    },
    methods: {
        send: function () {
            this.$http.post("/index/center/sendnote", {
                msg:this.msg,

            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                alert('发送成功');
                this.msg = '';

            }, function (res) {
                alert("程序崩掉了");
            });
        },

    },

});

