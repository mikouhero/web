vm = new Vue({
    el: '.page-content',
    data: {
        name: '',
        tagList: {},
        random:['label-success','label-warning','label-danger','label-info','label-purple','label-inverse','label-pink','label-yellow','label-grey','label-primary','label-light'],
        banId:''
    },
    methods: {
        getTagList: function () {
            this.$http.post(ajaxUrl.getTagList, {}, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.tagList = res.data.data;
            }, function (res) {
                alert(res);
            });
        },

        addTag: function (ev) {
            if (!this.name) {
                alert('不能为空');
                return;
            }
            this.$http.post(ajaxUrl.addTag, {
                name: this.name
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.name = '';
                this.getTagList();

            }, function (res) {
            });
        },
        ban:function (id) {
            this.$http.post(ajaxUrl.banTag, {
                id: id
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.banId = '';
                this.getTagList();
            }, function (res) {
            });
        },
        openTag:function (id) {
            this.$http.post(ajaxUrl.openTag, {
                id: id
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.banId = '';
                this.getTagList();
            }, function (res) {
            });
        },


    },
    mounted: function () {
        this.$nextTick(function () {
            this.getTagList();
        });
    }
});