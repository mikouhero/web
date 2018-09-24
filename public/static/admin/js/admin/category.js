vm = new Vue({
    el: '.page-content',
    data: {
        menuList: '',
        addName:'',
        addUrl:'',
        addStatus:1,
        addParentname:'',
        addParentid:'',
        addSort:1,
        editMsg: Array(),
        deleteId:''

    },
    methods: {
        getHomeMenu:function () {
            this.$http.post(ajaxUrl.getMenuList, {
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.menuList = res.data.data;
            }, function (res) {
                alert(res);
            });
        },
        addTemp:function (id,name) {
            this.addParentname = name;
            this.addParentid= id;
        },
        addMenu:function () {

            this.$http.post(ajaxUrl.addMenu, {
                name:this.addName,
                url: this.addUrl,
                sort :this.addSort,
                status:Number(this.addStatus),
                parent_id:this.addParentid
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.addName='';
                this.addUrl='';
                this.addSort=1;
                this.addSatus=1;
                this.addParentname='';
                this.addParentid='';
                this.getHomeMenu();

            }, function (res) {
            });
        },
        editaddStatus:function () {
            this.addSatus= this.addSatus || 1;
        },
        editMenutmp:function (key,k) {
            if(k == 'tmp'){
                this.menuList[key].child='';
                this.editMsg = this.menuList[key];

            }else{
                this.editMsg = this.menuList[key].child[k];
            }
        },
        editMenu:function () {
           this.editMsg['status'] = Number(this.editMsg['status']);

            this.$http.post(ajaxUrl.editMenu, {
               msg:this.editMsg
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.editMsg=[];
                this.getHomeMenu();
            }, function (res) {
            });
        },
        deleteMenu: function (id) {
            this.deleteId = id;
            this.$http.post(ajaxUrl.deleteMenu, {
                id:this.deleteId
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.deleteId = '';
                this.getHomeMenu();

            }, function (res) {
            });

        }

    },
    mounted: function () {
        this.$nextTick(function () {
            this.getHomeMenu();
        });
    }
});