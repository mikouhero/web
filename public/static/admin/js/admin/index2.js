
vm1 = new Vue({
    el: '.infobox-container',
    data: {
        log:"",
        msg:'',
        member:'',
        brj:'',
        crj:'',
        memberlog:'',
        visit:''
    },
    methods: {
        getLogList: function () {
            this.$http.post(ajaxUrl.getLogList, {

            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.log = res.data.data;
                this.msg = this.log.msg;
                this.member = this.log.member;
                this.brj = this.log.brj;
                this.crj = this.log.crj;
                this.memberlog = this.log.memberlog;
                this.visit = this.log.visit;

            }, function (res) {
                alert("程序崩掉了");
            });
        },

    },
    mounted: function () {
        this.$nextTick(function () {
            this.getLogList();
        });
    }
});