vm = new Vue({
    el: '.page-content',
    data: {
        random: ['label-success', 'label-warning', 'label-danger', 'label-info', 'label-purple', 'label-inverse', 'label-pink', 'label-yellow', 'label-grey', 'label-primary', 'label-light'],
        menuList: '',
        child: {},
        msg: {"recommend": 0},
        blogList: '',
        tagList:{},
        pageNo: 1,   // 当前页数
        pages: 0,    //  多少页
        div: '',
        editItem: {},
        tagkey: '',    // click 用户在列表中的key
        waitBlogId:'',  // 分配角色用户id
        hasTag: {},   //指定用户的角色 列表
        nohasTag: {}  // 用户没有的角色列表
    },
    methods: {
        getBlogList: function () {
            this.$http.post(ajaxUrl.getBlogList, {
                current_page: this.pageNo
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.blogList = res.data.data['list'];
                this.pages = res.data.data['count'];
                this.tagList = res.data.data['tag'];
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        getHomeMenu: function () {
            this.$http.post(ajaxUrl.getMenuList, {}, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.menuList = res.data.data;
            }, function (res) {
                alert("程序崩掉了");
            });
        },
        mchange: function () {
            var category = $('#category').val();
            this.child = [];
            var child = '';
            if (category) {
                var list = this.menuList;
                for (var i = 0; i < list.length; i++) {
                    if (list[i]['id'] == category) {
                        this.child = list[i]['child'];
                        if (list[i]['child'].length) {
                            child = $('#categorychild').val();
                        }
                        break;
                    }
                }

            }
            this.msg['category_id'] = child || category;
        },
        addBlog: function () {
            var content = $('.markdown-body').html();
            var mackdown = $('#mackdown').val();
            var pic = $('#result').val();
            this.msg['content'] = content;
            this.msg['mackdown'] = mackdown;
            this.msg['pic'] = pic;
            this.msg['recommend'] = Number(this.msg['recommend']);
            this.$http.post(ajaxUrl.addBlog, {
                'msg': JSON.stringify(this.msg)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                location.href =adminUrl + '/blog';

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
        pageList: function (curPage) {
            //根据当前页获取数据
            this.pageNo = curPage;
            this.getBlogList(this.pageNo);
            // console.log("当前页：" + this.pageNo);
        },
        tmp: function (key) {
            this.div = this.blogList[key].content;
        },
        edit: function (id, key) {
            //点击的存存储在缓存中
           localStorage.setItem(key,JSON.stringify(this.blogList[key]));
           location.href = adminUrl + '/blog/edit?id=' + id + '&key=' + key;
        },
        editmsg: function () {
            var key =this.getKey('key');
            this.editItem=JSON.parse(localStorage.getItem(key));
        },
        update:function () {
            var content = $('.markdown-body').html();
            var mackdown = $('#mackdown').val();
            var pic = $('#result').val();
            this.editItem['content'] = content;
            this.editItem['mackdown'] = mackdown;
            this.editItem['newpic'] = pic;
            this.editItem['recommend'] = Number(this.msg['recommend']);
            this.$http.post(ajaxUrl.editBlog, {
                'msg': JSON.stringify(this.editItem)
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
            location.href =adminUrl + '/blog';
            }, function (res) {
                alert("程序崩掉了");
            });

        },
        getKey: function (paraName) {

            var url = document.location.toString();
            var arrObj = url.split("?");
            if (arrObj.length > 1) {
                var arrPara = arrObj[1].split("&");
                var arr;

                for (var i = 0; i < arrPara.length; i++) {
                    arr = arrPara[i].split("=");
                    if (arr !== null && arr[0] == paraName) {
                        return arr[1];
                    }
                }
                return false;
            }
            else {
                return false;
            }
        },
        tagtmp: function (key) {
            this.tagkey = key;
            this.waitBlogId  = this.blogList[key]['id'];
            var tmpblog = this.hasTag = this.blogList[key]['tag'];
            var tmpall = this.tagList;

            this.nohasTag = tmpall.filter(function (item) {
                return JSON.stringify(tmpblog).indexOf(JSON.stringify(item)) == -1;
            })
        },
        assignTag:function (tag_id) {
            this.$http.post(ajaxUrl.assignTag, {
                blog_id: this.waitBlogId,
                tag_id:tag_id
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.tagList.filter(function (item) {
                    return item.id == tag_id;
                })
                this.hasTag.push((this.tagList.filter(function (item) {
                    return item.id == tag_id;
                }))[0]);
                var tmpblog = this.hasTag = this.blogList[this.tagkey]['tag'];
                var tmpall = this.tagList;
                this.nohasTag = tmpall.filter(function (item) {
                    return JSON.stringify(tmpblog).indexOf(JSON.stringify(item)) == -1;
                })
            }, function (res) {
                alert('程序崩掉了');
            });
        },
        deleteTag:function (tag_id) {
            this.$http.post(ajaxUrl.deleteTag, {
                blog_id: this.waitBlogId,
                tag_id:tag_id
            }, {
                emulateJSON: true
            }).then(function (res) {
                if (res.data.code != 200) {
                    alert(res.data.msg);
                    return false;
                }
                this.nohasTag.push((this.tagList.filter(function (item) {
                    return item.id == tag_id;
                }))[0]);
                var tmpuser = this.nohasTag ;
                var tmpall = this.tagList;
                this.hasTag = tmpall.filter(function (item) {
                    return JSON.stringify(tmpuser).indexOf(JSON.stringify(item)) == -1;
                });
                this.blogList[this.tagkey]['tag'] = this.hasTag;
            }, function (res) {
                alert('程序崩掉了');
            });
        }
    },
    mounted: function () {
        this.$nextTick(function () {
            this.getHomeMenu();
            this.getBlogList();
            this.editmsg();
        });
    }
});