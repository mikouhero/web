vm = new Vue({
    el: '.nav-list',
    data: {
        menu:""
    },
    methods: {
        getMenuList: function () {
            this.$http.post(ajaxUrl.getMenuList, {
                current_page: this.pageNo
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
               this.menu = res.data.data.toString();
            }, function (res) {
                alert("程序崩掉了");
            });
        },

    },
    mounted: function () {
        this.$nextTick(function () {
            this.getMenuList();
        });
    }
});